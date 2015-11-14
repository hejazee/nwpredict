<pre>
<?php
require_once "common.inc.php";

// Load analyzed data
$data = file_get_contents('data-analized/file.txt.json');
$graph = json_decode($data, TRUE);

// Some sample text. predict next word
$text = 'In its most popular';
$next_words = predict($text, $graph);
print_r($next_words);
if (!empty($next_words)) {
  echo $text . ' <strong>';
  echo isset($next_words[0]) ? $next_words[0] : '';
  echo '</strong>';
}

function predict($text, $graph) {
  // This function gives three suggestions
  $suggestions = array();
  
  // Sanitize text and get last three words
  $words = text_sanitize($text);
  
  $words_last_index = count($words) - 1;
  $word1 = isset($words[$words_last_index - 2]) ? $words[$words_last_index - 2] : '';
  $word2 = isset($words[$words_last_index - 1]) ? $words[$words_last_index - 1] : '';
  $word3 = isset($words[$words_last_index]) ? $words[$words_last_index] : '';
  
  // Search the graph
  // First look for longest path (3 bridges)
  if (isset($graph[$word1][$word2][$word3])) {
    $sug = get_best_three_scores($graph[$word1][$word2][$word3]);
    
    // Remove empty elements
    $sug = array_filter($sug);
    
    // Append to suggestions
    $suggestions += $sug;
  }
  
  // If we have not three suggestions yet, look for 2-bridges paths
  if ((count($suggestions) < 3) && isset($graph[$word2][$word3])) {
    $sug = get_best_three_scores($graph[$word2][$word3]);
    
    // Remove empty elements
    $sug = array_filter($sug);
    
    // Append to suggestions
    $suggestions += $sug;
  }
  
  // If we have not three suggestions yet, look for 1-bridge paths
  if ((count($suggestions) < 3) && isset($graph[$word3])) {
    $sug = get_best_three_scores($graph[$word3]);
    
    // Remove empty elements
    $sug = array_filter($sug);
    
    // Append to suggestions
    $suggestions += $sug;
  }
  
  // Remove duplicate suggestions
  // Duplicates can be genereted between above three levels of lookup.
  $suggestions = array_unique($suggestions);
  
  return $suggestions;
}
