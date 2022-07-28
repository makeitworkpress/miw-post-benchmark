<?php
/**
 * Plugin Name: Post Benchmark
 * Description: Simple benchmark plugin that captures how long inserting, querying and deleting a certain number of custom posts take.
 * 
 * Author: Make it WorkPress
 * Author URI: https://makeitwork.press
 * Version: 1.0.0
 */

require_once( dirname(__FILE__) . './src/ajax.php' );
require_once( dirname(__FILE__) . './src/assets.php' );
require_once( dirname(__FILE__) . './src/options.php' );

add_action('plugins_loaded', function() {
  miw_pb_setup_ajax();
  miw_pb_setup_assets();
  miw_pb_setup_options();
});