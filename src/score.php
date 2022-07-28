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

  $insert_score = max(1, 10 - ($insert/$number)/0.0025); // 0.0025s per post is a very good score for inserting
  $query_score = max(1, 10 - ($query/$number)/0.000025); // 0.000025s per post is a very good score for querying
  $delete_score = max(1, 10 - ($delete/$number)/0.00275); // 0.00275s per post is a very good score for deleting

  return number_format( ($insert_score * 3 + $query_score + $delete_score * 3)/7, 2 );
}