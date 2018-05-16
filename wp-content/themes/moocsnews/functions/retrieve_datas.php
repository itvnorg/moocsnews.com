<?php

// Query to get post by taxonomy
function get_post_by_taxonomy( $post_type = 'post', $tax_type = 'category', $tax_terms = '' , $post_number = 5)  {
	$args = array(
		'post_type' 			=> 	$post_type,
		'posts_per_page'		=> 	$post_number,
		'paged'					=>	1,						
		'orderby' 				=> 'ID',
		'order'   				=> 'DESC',
	); 

	// Filter with taxonomy
	if($tax_terms != null){
		$args['tax_query'] = array(
			array(
				'taxonomy' => $tax_type,
				'field'    => 'slug',
				'terms'    => $tax_terms,
			)
		);									
	}
	
	return $courses = new WP_Query( $args ); 
}