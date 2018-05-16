<?php

/*==============	itvn.org Create Courses Post Type	======================*/
function itvndocorg_create_course_post_type() {
	/* Set labels for post type fields */
	$labels = array(
		'name' 				  => __( 'Courses', 'Post Type General Name' ),
		'singular_name' 	  => __( 'Course', 'Post Type Singular Name' ),
		'menu_name'           => __( 'Courses' ),
        'parent_item_colon'   => __( 'Parent Course' ),
        'all_items'           => __( 'All Course' ),
        'view_item'           => __( 'View Course' ),
        'add_new_item'        => __( 'Add New Course' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Course' ),
        'update_item'         => __( 'Update Course' ),
        'search_items'        => __( 'Search Course' ),
        'not_found'           => __( 'Course Not Found' ),
        'not_found_in_trash'  => __( 'Course Not found in Trash' ),
	);
	
	/* Set arguments for post type */
	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'rewrite' => ['slug' => 'course'],
		'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			  	//'custom-fields',
		), 
		'taxonomies'   => array(
			// 'category',
			//'instructor',
			//'institution',
			//'specialization',
		)
	);

	/* Register Post Type */
	register_post_type( 'course', $args);
	// register_taxonomy_for_object_type( 'category', 'course' );
}
add_action( 'init', 'itvndocorg_create_course_post_type' );

/*==============	itvn.org Add Moocsnews Course Fields Meta Box	======================*/
function itvndocorg_add_course_fields_meta_box() {
	add_meta_box(
		'course_fields_meta_box', // $id
		'Course Fields', // $title
		'itvndocorg_show_course_fields_meta_box', // $callback
		'course', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'itvndocorg_add_course_fields_meta_box' );

function itvndocorg_show_course_fields_meta_box() {
	global $post;  
	$meta = get_post_meta( $post->ID, 'course_fields', true ); ?>

	<input type="hidden" name="course_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

	<p>
		<label for="course_fields[link_intro_course]">Intro Course Page URL</label>
		<br>
		<input type="text" name="course_fields[link_intro_course]" id="course-fields-link-intro-course" class="regular-text" value="<?php echo $meta['link_intro_course']; ?>">
	</p>

	<p>
		<label for="course_fields[video_introduction]">Video Introduction URL</label>
		<br>
		<input type="text" name="course_fields[video_introduction]" id="course-fields-video-introduction" class="regular-text" value="<?php echo $meta['video_introduction']; ?>">
	</p>

	<p>
		<label for="course_fields[video_type]">Video Introduction Type</label>
		<br>
		<input type="text" name="course_fields[video_type]" id="course-fields-video-introduction" class="regular-text" value="<?php echo $meta['video_type']; ?>">
	</p>

	<p>
		<label for="course_fields[video_poster]">Video Poster URL</label>
		<br>
		<input type="text" name="course_fields[video_poster]" id="course-fields-video-introduction" class="regular-text" value="<?php echo $meta['video_poster']; ?>">
	</p>

	<p>
		<label for="course_fields[description]">Course Short Description</label>
		<br>
		<textarea name="course_fields[description]" id="course-fields-description" rows="5" cols="30" style="width:500px;"><?php echo $meta['description']; ?></textarea>
	</p>

	<p>
	<label for="course_fields[release_date]">Start Date</label>
		<br>
		<input type="text" name="course_fields[start_date]" id="course-fields-start-date" class="regular-text datepicker" value="<?php echo $meta['start_date']; ?>">
	</p>

	<p>
		<label for="course_fields[language]">Select Language</label>
		<br>
		<select name="course_fields[language]" id="course-fields-language">
				<option value="English" <?php selected( $meta['language'], 'English' ); ?>>English</option>
				<option value="Vietnamese" <?php selected( $meta['language'], 'Vietnamese' ); ?>>Vietnamese</option>
		</select>
	</p>

    <!-- All fields will go here -->

<?php }

/*==============	itvn.org Save Moocsnews Course Fields Meta Box	======================*/
function itvndocorg_save_course_fields_meta( $post_id ) {   
	// verify nonce
	if ( !wp_verify_nonce( $_POST['course_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['course'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}  
	}
	
	$old = get_post_meta( $post_id, 'course_fields', true );
	$new = $_POST['course_fields'];

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'course_fields', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'course_fields', $old );
	}
}
add_action( 'save_post', 'itvndocorg_save_course_fields_meta' );

/*Add Tags To Courses*/
add_action( 'init', 'itvndocorg_register_taxonomy_for_object_type' );
function itvndocorg_register_taxonomy_for_object_type() {
    register_taxonomy_for_object_type( 'post_tag', 'course' );
};