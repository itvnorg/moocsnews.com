<?php

/*==============	itvn.org Add Moocsnews Category Fields Meta Box	======================*/
add_action('category_edit_form_fields','itvndocorg_add_custom_fields_to_edit_category');
add_action('category_add_form_fields','itvndocorg_add_custom_fields_to_add_category');
add_action( 'edited_category', 'itvndocorg_save_category_custom_fields_meta_data', 10, 2 );
add_action( 'create_category', 'itvndocorg_save_category_custom_fields_meta_data', 10, 2 );  

function itvndocorg_add_custom_fields_to_add_category($term){
	$meta = get_term_meta( $term->term_id, 'category_meta', true );
?>
    <div class="form-field">
        <label for="category_meta[image_url]"><?php echo _e('Image URL') ?></label>
    	<input type="text" name="category_meta[image_url]" id="category_meta[image_url]" value="<?php echo $meta['image_url']; ?>">              
    </div>
<?php
}

function itvndocorg_add_custom_fields_to_edit_category($term){
	$meta = get_term_meta( $term->term_id, 'category_meta', true );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="category_meta[image_url]"><?php echo _e('Image URL') ?></label>
            <td>
            	<input type="text" name="category_meta[image_url]" id="category_meta[image_url]" value="<?php echo $meta['image_url']; ?>">                
            </td>
        </th>
    </tr>
<?php
}

/*==============	itvn.org Save Moocsnews Category Fields Meta Data	======================*/
function itvndocorg_save_category_custom_fields_meta_data($term_id){ 

    if ( isset( $_POST['category_meta'] ) ) {
	
		$old = get_term_meta( $term_id, 'category_meta', true );
		$new = $_POST['category_meta'];

		// Save meta data array.
		if ( $new && $new !== $old ) {
			update_term_meta( $term_id, 'category_meta', $new );
		} elseif ( '' === $new && $old ) {
			delete_term_meta( $term_id, 'category_meta', $old );
		}
    } 
}

/*==============    itvn.org Add Moocsnews Institution Fields Meta Box ======================*/
add_action('institution_edit_form_fields','itvndocorg_add_custom_fields_to_edit_institution');
add_action('institution_add_form_fields','itvndocorg_add_custom_fields_to_add_institution');
add_action( 'edited_institution', 'itvndocorg_save_institution_custom_fields_meta_data', 10, 2 );
add_action( 'create_institution', 'itvndocorg_save_institution_custom_fields_meta_data', 10, 2 );  

function itvndocorg_add_custom_fields_to_add_institution($term){
    $meta = get_term_meta( $term->term_id, 'institution_meta', true );
?>
    <div class="form-field">
        <label for="institution_meta[image_url]"><?php echo _e('Image URL') ?></label>
        <input type="text" name="institution_meta[image_url]" id="institution_meta[image_url]" value="<?php echo $meta['image_url']; ?>">              
    </div>
<?php
}

function itvndocorg_add_custom_fields_to_edit_institution($term){
    $meta = get_term_meta( $term->term_id, 'institution_meta', true );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="institution_meta[image_url]"><?php echo _e('Image URL') ?></label>
            <td>
                <input type="text" name="institution_meta[image_url]" id="institution_meta[image_url]" value="<?php echo $meta['image_url']; ?>">                
            </td>
        </th>
    </tr>
<?php
}

/*==============    itvn.org Save Moocsnews Category Fields Meta Data   ======================*/
function itvndocorg_save_institution_custom_fields_meta_data($term_id){ 

    if ( isset( $_POST['institution_meta'] ) ) {
    
        $old = get_term_meta( $term_id, 'institution_meta', true );
        $new = $_POST['institution_meta'];

        // Save meta data array.
        if ( $new && $new !== $old ) {
            update_term_meta( $term_id, 'institution_meta', $new );
        } elseif ( '' === $new && $old ) {
            delete_term_meta( $term_id, 'institution_meta', $old );
        }
    } 
}

/*==============    itvn.org Add Moocsnews Instructor Fields Meta Box ======================*/
add_action('instructor_edit_form_fields','itvndocorg_add_custom_fields_to_edit_instructor');
add_action('instructor_add_form_fields','itvndocorg_add_custom_fields_to_add_instructor');
add_action( 'edited_instructor', 'itvndocorg_save_instructor_custom_fields_meta_data', 10, 2 );
add_action( 'create_instructor', 'itvndocorg_save_instructor_custom_fields_meta_data', 10, 2 );  

function itvndocorg_add_custom_fields_to_add_instructor($term){
    $meta = get_term_meta( $term->term_id, 'instructor_meta', true );
?>
    <div class="form-field">
        <label for="instructor_meta[image_url]"><?php echo _e('Image URL') ?></label>
        <input type="text" name="instructor_meta[image_url]" id="instructor_meta[image_url]" value="<?php echo $meta['image_url']; ?>">              
    </div>
<?php
}

function itvndocorg_add_custom_fields_to_edit_instructor($term){
    $meta = get_term_meta( $term->term_id, 'instructor_meta', true );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="instructor_meta[image_url]"><?php echo _e('Image URL') ?></label>
            <td>
                <input type="text" name="instructor_meta[image_url]" id="instructor_meta[image_url]" value="<?php echo $meta['image_url']; ?>">                
            </td>
        </th>
    </tr>
<?php
}

/*==============    itvn.org Save Moocsnews Category Fields Meta Data   ======================*/
function itvndocorg_save_instructor_custom_fields_meta_data($term_id){ 

    if ( isset( $_POST['instructor_meta'] ) ) {
    
        $old = get_term_meta( $term_id, 'instructor_meta', true );
        $new = $_POST['instructor_meta'];

        // Save meta data array.
        if ( $new && $new !== $old ) {
            update_term_meta( $term_id, 'instructor_meta', $new );
        } elseif ( '' === $new && $old ) {
            delete_term_meta( $term_id, 'instructor_meta', $old );
        }
    } 
}

/*==============    itvn.org Add Moocsnews Specialization Fields Meta Box ======================*/
add_action('specialization_edit_form_fields','itvndocorg_add_custom_fields_to_edit_specialization');
add_action('specialization_add_form_fields','itvndocorg_add_custom_fields_to_add_specialization');
add_action( 'edited_specialization', 'itvndocorg_save_specialization_custom_fields_meta_data', 10, 2 );
add_action( 'create_specialization', 'itvndocorg_save_specialization_custom_fields_meta_data', 10, 2 );  

function itvndocorg_add_custom_fields_to_add_specialization($term){
    $meta = get_term_meta( $term->term_id, 'specialization_meta', true );
?>
    <div class="form-field">
        <label for="specialization_meta[image_url]"><?php echo _e('Image URL') ?></label>
        <input type="text" name="specialization_meta[image_url]" id="specialization_meta[image_url]" value="<?php echo $meta['image_url']; ?>">              
    </div>
<?php
}

function itvndocorg_add_custom_fields_to_edit_specialization($term){
    $meta = get_term_meta( $term->term_id, 'specialization_meta', true );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="specialization_meta[image_url]"><?php echo _e('Image URL') ?></label>
            <td>
                <input type="text" name="specialization_meta[image_url]" id="specialization_meta[image_url]" value="<?php echo $meta['image_url']; ?>">                
            </td>
        </th>
    </tr>
<?php
}

/*==============    itvn.org Save Moocsnews Category Fields Meta Data   ======================*/
function itvndocorg_save_specialization_custom_fields_meta_data($term_id){ 

    if ( isset( $_POST['specialization_meta'] ) ) {
    
        $old = get_term_meta( $term_id, 'specialization_meta', true );
        $new = $_POST['specialization_meta'];

        // Save meta data array.
        if ( $new && $new !== $old ) {
            update_term_meta( $term_id, 'specialization_meta', $new );
        } elseif ( '' === $new && $old ) {
            delete_term_meta( $term_id, 'specialization_meta', $old );
        }
    } 
}

/*==============    itvn.org Add Moocsnews Subject Fields Meta Box ======================*/
add_action('subject_edit_form_fields','itvndocorg_add_custom_fields_to_edit_subject');
add_action('subject_add_form_fields','itvndocorg_add_custom_fields_to_add_subject');
add_action( 'edited_subject', 'itvndocorg_save_subject_custom_fields_meta_data', 10, 2 );
add_action( 'create_subject', 'itvndocorg_save_subject_custom_fields_meta_data', 10, 2 );  

function itvndocorg_add_custom_fields_to_add_subject($term){
    $meta = get_term_meta( $term->term_id, 'subject_meta', true );
?>
    <div class="form-field">
        <label for="subject_meta[image_url]"><?php echo _e('Image URL') ?></label>
        <input type="text" name="subject_meta[image_url]" id="subject_meta[image_url]" value="<?php echo $meta['image_url']; ?>">              
    </div>
<?php
}

function itvndocorg_add_custom_fields_to_edit_subject($term){
    $meta = get_term_meta( $term->term_id, 'subject_meta', true );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="subject_meta[image_url]"><?php echo _e('Image URL') ?></label>
            <td>
                <input type="text" name="subject_meta[image_url]" id="subject_meta[image_url]" value="<?php echo $meta['image_url']; ?>">                
            </td>
        </th>
    </tr>
<?php
}

/*==============    itvn.org Save Moocsnews Category Fields Meta Data   ======================*/
function itvndocorg_save_subject_custom_fields_meta_data($term_id){ 

    if ( isset( $_POST['subject_meta'] ) ) {
    
        $old = get_term_meta( $term_id, 'subject_meta', true );
        $new = $_POST['subject_meta'];

        // Save meta data array.
        if ( $new && $new !== $old ) {
            update_term_meta( $term_id, 'subject_meta', $new );
        } elseif ( '' === $new && $old ) {
            delete_term_meta( $term_id, 'subject_meta', $old );
        }
    } 
}