<?php
/**
 * The template for displaying the options page
 */
?>
<div class="wrap">
  <h1><?php _e('Post Insert, Query & Delete Benchmark'); ?></h1>
  <p>
    <?php _e('This tool allows you to test the speed of inserting, querying and deleting a certain number of posts.'); ?>
    <?php _e('This can give insights in perceived back-end performance, the database performance and the speed of the server CPUs.'); ?>
  </p>
  <hr class="wp-header-end">
  <form method="post" class="miw-pb-form">
		<h2><?php _e('Run a New Test'); ?></h2>
		<table class="form-table">
			<tbody>
        <tr>
					<th scope="row">
						<label for="miw_pb_number"><?php _e('Number of Posts'); ?></label>
					</th>
					<td>
						<input type="number" class="small-text" id="miw_pb_number" name="miw_pb_number" min="0" required="" value="100">
            <p class="description">
              <?php _e('The number of custom posts to insert, query and delete.'); ?><br>
              <?php _e('For large numbers (1000+), you may need to increase your max execution time in PHP.'); ?>
            </p>
					</td>
				</tr>
			</tbody>
    </table>
    <div class="submit">
		  <?php submit_button( __('Run Benchmark'), 'primary button-hero', 'submit', false); ?>	
      <div class="update-nag notice notice-info inline" style="margin: 0 0 0 8px; display: none;">
        <span class="spinner is-active" style="float: left; margin: 0 10px 0 0;"></span>
        <?php _e('The benchmark is running, please do not close this page.'); ?>
      </div>       
      <div class="update-nag notice notice-success inline" style="margin: 0 0 0 8px; display: none;">
        <?php _e('Hurray! The Benchmark is complete!'); ?>
      </div>
      <div class="update-nag notice notice-error inline" style="margin: 0 0 0 8px; display: none;">
        <?php _e('Failed to benchmark, please try again. You may need to increase your PHP execution time and request timeout values.'); ?>
      </div>      
    </div>
    <span class="running"></span>
	</form>  
  <hr>
  <form method="post" class="miw-pb-table">
    <h2><?php _e('Previous Tests'); ?></h2>
    <?php 
      $table->display(); 
    ?>
		<?php submit_button( __('Clear All Tests'), 'button-hero', 'reset', false); ?>	    
  </form>
</div>