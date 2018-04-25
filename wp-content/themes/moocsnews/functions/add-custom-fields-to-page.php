<?php 
/*==============	ECEP Add vCampus Course Fields Meta Box	======================*/
function itvndocorg_add_page_fields_meta_box() {
	add_meta_box(
		'page_fields_meta_box', // $id
		'Page Fields', // $title
		'itvndocorg_show_page_fields_meta_box', // $callback
		'page', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'itvndocorg_add_page_fields_meta_box' );

function itvndocorg_show_page_fields_meta_box() {
	global $post;  
	$meta = get_post_meta( $post->ID, 'page_fields', true ); ?>

	<input type="hidden" name="page_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">


	<p>
		<label for="page_fields[description]">Description</label>
		<br>
		<textarea name="page_fields[description]" id="page-fields-description" rows="5" cols="30" style="width:500px;"><?php echo $meta['description']; ?></textarea>
	</p>

    <!-- All fields will go here -->

<?php }

/*==============	ECEP Save vCampus Course Fields Meta Box	======================*/
function itvndocorg_save_page_fields_meta( $post_id ) {   
	// verify nonce
	if ( !wp_verify_nonce( $_POST['page_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['page'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}  
	}
	
	$old = get_post_meta( $post_id, 'page_fields', true );
	$new = $_POST['page_fields'];

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'page_fields', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'page_fields', $old );
	}
}
add_action( 'save_post', 'itvndocorg_save_page_fields_meta' );