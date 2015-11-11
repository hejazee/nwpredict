<pre>
<?php
require_once "common.inc.php";

// Load analyzed data
$data = file_get_contents('data-analized/file.txt.json');
$graph = json_decode($data, TRUE);

// Some sample text. predict next word
$text = 'A new car worda lifehacker and';
$next_words = predict($text, $graph);
print_r($next_words);
echo $text . ' <strong>' . $next_words[0] . '</strong>';

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
    $suggestions += get_best_three_scores($graph[$word1][$word2][$word3]);
  }
  
  // If we have not three suggestions yet, look for 2-bridges paths
  if ((count($suggestions) < 3) && isset($graph[$word2][$word3])) {
    $suggestions += get_best_three_scores($graph[$word2][$word3]);
  }
  
  // If we have not three suggestions yet, look for 1-bridge paths
  if ((count($suggestions) < 3) && isset($graph[$word3])) {
    $suggestions += get_best_three_scores($graph[$word3]);
  }
  
  // TODO: Remove duplicate suggestions
  // Duplicates can be genereted between above three levels of lookup.
  
  return $suggestions;
}
