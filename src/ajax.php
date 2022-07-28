<?php
/**
 * Setup certain ajax actions
 */
defined( 'ABSPATH' ) or die('Nope...');

// Load our actions
require_once(  dirname(__FILE__) . './actions.php' );
require_once(  dirname(__FILE__) . './score.php' );

/**
 * Add the ajax actions
 */
function miw_pb_setup_ajax() {

  // Insert Posts
  add_action('wp_ajax_miw_pb_insert', function() {
    check_ajax_referer('strawberry-juice', 'nonce');
    $time = miw_pb_insert_posts( intval( $_POST['number'] ) );
    wp_send_json( $time );
  });

  // Query Posts
  add_action('wp_ajax_miw_pb_query', function() {
    check_ajax_referer('strawberry-juice', 'nonce');
    $time = miw_pb_query_posts( intval( $_POST['number'] ) );
    wp_send_json( $time );
  });

  // Delete posts
  add_action('wp_ajax_miw_pb_delete', function() {
    check_ajax_referer('strawberry-juice', 'nonce');
    $time = miw_pb_delete_posts( intval( $_POST['number'] ) );
    wp_send_json( $time );
  });

  // Save benchmarks
  add_action('wp_ajax_miw_pb_update_results', function() {
    check_ajax_referer('strawberry-juice', 'nonce');

    $results = [];

    foreach( ['number', 'insert_time', 'query_time', 'delete_time'] as $key ) {
      if( ! isset($_POST[$key]) || ! is_numeric($_POST[$key]) ) {
        wp_send_json_error($_POST[$key]);
      }

      $results[$key] = $_POST[$key]; 
    }

    $benchmarks       = get_option('miw_pb_benchmarks') ? get_option('miw_pb_benchmarks') : [];
    $results['id']    = count($benchmarks) + 1;
    $results['score'] = miw_pb_score($results['number'], $results['insert_time'], $results['query_time'], $results['delete_time']);

    if( $benchmarks ) {
      array_unshift($benchmarks, $results);
    } else {
      $benchmarks = [$results];  
    }

    update_option('miw_pb_benchmarks', $benchmarks);

    wp_send_json_success(['id' => $results['id'], 'score' => $results['score']]);
  }); 
  
  // Delete benchmarks
  add_action('wp_ajax_miw_pb_delete_results', function() {
    check_ajax_referer('strawberry-juice', 'nonce');

    delete_option('miw_pb_benchmarks');
  });

}