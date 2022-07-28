<?php
/**
 * Use this function to test MySQL write and read performance in WordPress
 * An important factor or bottleneck in WordPress is the database. This is a simple test that tests database performance.
 */
function miw_pb_insert_posts(int $number = 1000): float {
  $start_time = round(microtime(true) * 1000);
  
  for( $i = 0; $i < $number; $i++) {
    wp_insert_post([
      'post_type'     => 'miw_pb_post',
      'post_title'    => 'Lorem Ipsum',
      'post_content'  => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?'
    ]);
  }
  $end_time = round(microtime(true) * 1000);

  return floatval( ($end_time - $start_time)/1000 );
}

function miw_pb_delete_posts(int $number = 1000): float {
  $start_time = round(microtime(true) * 1000);

  $posts      = get_posts(['post_type' => 'miw_pb_post', 'posts_per_page' => $number, 'fields' => 'ids', 'post_status' => 'draft']);
  foreach($posts as $post_id) {
    wp_delete_post($post_id, true);
  }

  $end_time   = round(microtime(true) * 1000);

  return floatval( ($end_time - $start_time)/1000 );
}

function miw_pb_query_posts(int $number = 1000): float {
  
  $start_time = round(microtime(true) * 1000);
  $posts      = get_posts(['post_type' => 'miw_pb_post', 'posts_per_page' => $number, 'post_status' => 'draft']);
  $end_time   = round(microtime(true) * 1000);

  return floatval( ($end_time - $start_time)/1000 );
}