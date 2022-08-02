<?php
/**
 * A very simple algorithm to calculate server scores
 */
defined( 'ABSPATH' ) or die('Nope...');

/**
 * Returns the score from 0 to 10 based on the input.
 * Insertion and deletion weigh 3 times as heavy as querying.
 * 
 * @return float The score
 */
function miw_pb_score($number, $insert, $query, $delete): float {

  $insert_score = max(1, 10 - ($insert/$number)/0.0015); // 0.0025s per post is a very good score for inserting
  $query_score = max(1, 10 - ($query/$number)/0.000015); // 0.000025s per post is a very good score for querying
  $delete_score = max(1, 10 - ($delete/$number)/0.0020); // 0.00275s per post is a very good score for deleting

  return number_format( ($insert_score * 2 + $query_score + $delete_score * 2)/5, 2 );
}