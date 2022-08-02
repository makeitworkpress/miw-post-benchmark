<?php
/**
 * Adds the options page
 */
defined( 'ABSPATH' ) or die('Nope...');

// Loads dependencies
require_once(  dirname(__FILE__) . '/classes/MIW_PB_List_Table.php' );

/**
 * Setup the options page
 */
function miw_pb_setup_options() {

  add_action('admin_menu', function() {
    add_management_page( __('Make it WorkPress Post Benchmark'), __('Post Benchmark'), 'manage_options', 'miw-post-benchmark', 'miw_pb_render_options');
  });

  add_action('init', function() {
    register_post_type('miw_pb_post', [
      'labels'    => [
        'name'      => __('Benchmark Posts'),
        'singular'  => __('Benchmark Post'),
      ],
      'public'    => false,
      'supports'  => ['title']
    ]);
  });
}

/**
 * Renders the option page
 */
function miw_pb_render_options() {
  $table = new MIW_PB_List_Table();
  $table->prepare_items();
  require( dirname(__FILE__) . '/templates/options.php');
}