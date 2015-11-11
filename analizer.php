<pre>
<?php
require_once "common.inc.php";

// Read input
$text = file_get_contents('data/file.txt');
$words = text_sanitize($text);

// Create graph array
$graph = create_graph($words);

// Serialize graph and save it to a file
file_put_contents('data-analized/file.txt.json', json_encode($graph));

/**
 * Create graph
 */
function create_graph($words) {
  // Create a sorted array of unique words
  $unique_words = $words;
  if (!sort($unique_words, SORT_STRING)) {
    return FALSE;
  }
  $unique_words = array_unique($unique_words);

  // Words should be array keys (not values)
  $graph = array_flip($unique_words);
  
  $words_count = count($words);
  for ($i = 0; $i <= $words_count - 1; $i++) {
    if ($i >= 1) {
      // Add first level childs of graph
      // We start from 1 because first word has not a previous word.
      
      $prev_word = $words[$i - 1];
      $cur_word = $words[$i];
      
      if (!is_array($graph[$prev_word])) {
        $graph[$prev_word] = array();
      }
      
      if (!isset($graph[$prev_word][$cur_word]['#score'])) {
        $graph[$prev_word][$cur_word]['#score'] = 0;
      }
      
      $graph[$prev_word][$cur_word]['#score']++;
    }
    
    if ($i >= 2) {
      // Add second level childs of graph
      $prev_prev_word = $words[$i - 2];
      $prev_word = $words[$i - 1];
      $cur_word = $words[$i];
      
      if (!is_array($graph[$prev_prev_word])) {
        $graph[$prev_prev_word] = array();
      }
      
      if (!is_array($graph[$prev_prev_word][$prev_word])) {
        $graph[$prev_prev_word][$prev_word] = array();
      }
      
      if (!isset($graph[$prev_prev_word][$prev_word][$cur_word]['#score'])) {
        $graph[$prev_prev_word][$prev_word][$cur_word]['#score'] = 0;
      }
      
      $graph[$prev_prev_word][$prev_word][$cur_word]['#score']++;
    }
    if ($i >= 3) {
      // Add third level childs of graph
      $prev_prev_prev_word = $words[$i - 3];
      $prev_prev_word = $words[$i - 2];
      $prev_word = $words[$i - 1];
      $cur_word = $words[$i];
      
      if (!is_array($graph[$prev_prev_prev_word])) {
        $graph[$prev_prev_prev_word] = array();
      }
      
      if (!is_array($graph[$prev_prev_prev_word][$prev_prev_word])) {
        $graph[$prev_prev_prev_word][$prev_prev_word] = array();
      }
      
      if (!is_array($graph[$prev_prev_prev_word][$prev_prev_word][$prev_word])) {
        $graph[$prev_prev_prev_word][$prev_prev_word][$prev_word] = array();
      }
      
      if (!isset($graph[$prev_prev_prev_word][$prev_prev_word][$prev_word][$cur_word]['#score'])) {
        $graph[$prev_prev_prev_word][$prev_prev_word][$prev_word][$cur_word]['#score'] = 0;
      }
      
      $graph[$prev_prev_prev_word][$prev_prev_word][$prev_word][$cur_word]['#score']++;
    }
  }
  
  return $graph;
}
