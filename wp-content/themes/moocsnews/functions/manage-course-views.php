<?php 

/*============== Add matadata to manage post views ===================*/
function itvndocorg_set_post_views($postID) {
    $count_key = 'itvndocorg_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, $count);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

/*============== Track Post Views ===================*/
function itvndocorg_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    itvndocorg_set_post_views($post_id);
}
add_action( 'wp_head', 'itvndocorg_track_post_views');