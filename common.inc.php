<?php

/**
 * Santize input text and produce an array of words
 */
function text_sanitize($text) {
  // Convert all non-alphabetic chars to spaces
  $text = preg_replace('/[^a-z\ الف-ی]/i', ' ', $text);

  // Convert blanks to spaces
  $text = strtr($text, "\r\n\t", '   ');

  // Remove duplicate spaces
  $text = preg_replace('/\s+/', ' ', $text);

  // Trim
  $text = trim($text);
  
  // Make strings lower case to remove case sensitivity
  // TODO: Find a beter way for removing case sensitivity
  // Becase this will show all lower case words to user
  $text = mb_strtolower($text);

  // extract words
  $words = explode(' ', $text);
  
  return $words;
}

/**
 * Sort sub-elements of a graph using their scores
 */
function _sort_by_scores(&$tree) {
  uasort($tree, function($a, $b) {
    if ($a['#score'] == $b['#score']) {
      return 0;
    }
    return ($a['#score'] > $b['#score']) ? -1 : 1;
  });
}

/**
 * Get three best scores from a subtree of a graph
 */
function get_best_three_scores($tree) {
  $best_scores = array();
  
  // Remove #score of main tree
  unset($tree['#score']);
    
  // Sort sub-elements by their score
  _sort_by_scores($tree);
  
  // Reset array pointer so that we can read from first element
  reset($tree);

  // Read best three scores.
  for ($i = 1; $i <= 3; $i++) {
    $best_scores[] = key($tree);
    
    // Move array cursor
    next($tree);
  }
  
  return $best_scores;
}
