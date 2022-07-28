<?php
/**
 * Enqueues our assets
 */
defined( 'ABSPATH' ) or die('Nope...');


/**
 * Add the assets
 */
function miw_pb_setup_assets() {

  add_action('admin_enqueue_scripts', function() {

    $screen = get_current_screen();

    if( isset($screen->id) && $screen->id === 'tools_page_miw-post-benchmark') {
      
      wp_enqueue_script('miw-post-benchmark', plugin_dir_url( __FILE__ ) . '/assets/scripts.js', [], false, true);

      wp_localize_script( 'miw-post-benchmark', 'MIWPB', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'debug'   => defined('WP_DEBUG') && WP_DEBUG ? true : false,
        'nonce'   => wp_create_nonce( 'strawberry-juice' ),
      ]); 
    }    
  });

}