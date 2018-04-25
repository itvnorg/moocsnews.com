<?php 

/*==============    itvn.org Create Institution Custom Taxonomy Type   ======================*/
add_action( 'init', 'itvndocorg_create_institution_nonhierarchical_taxonomy', 0 );
 
function itvndocorg_create_institution_nonhierarchical_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Institutions', 'taxonomy general name' ),
    'singular_name' => _x( 'Institution', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Institution' ),
    'popular_items' => __( 'Popular Institutions' ),
    'all_items' => __( 'All Institutions' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Institution' ), 
    'update_item' => __( 'Update Institution' ),
    'add_new_item' => __( 'Add New Institution' ),
    'new_item_name' => __( 'New Institution Name' ),
    'separate_items_with_commas' => __( 'Separate Institutions with commas' ),
    'add_or_remove_items' => __( 'Add or remove Institutions' ),
    'choose_from_most_used' => __( 'Choose from the most used Institutions' ),
    'menu_name' => __( 'Institutions' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('institution','course',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'institution' ),
  ));
}

/*==============    itvn.org Create Instructor Custom Taxonomy Type   ======================*/
add_action( 'init', 'itvndocorg_create_instructor_nonhierarchical_taxonomy', 0 );
 
function itvndocorg_create_instructor_nonhierarchical_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Instructors', 'taxonomy general name' ),
    'singular_name' => _x( 'Instructor', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Instructor' ),
    'popular_items' => __( 'Popular Instructors' ),
    'all_items' => __( 'All Instructors' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Instructor' ), 
    'update_item' => __( 'Update Instructor' ),
    'add_new_item' => __( 'Add New Instructor' ),
    'new_item_name' => __( 'New Instructor Name' ),
    'separate_items_with_commas' => __( 'Separate Instructors with commas' ),
    'add_or_remove_items' => __( 'Add or remove Instructors' ),
    'choose_from_most_used' => __( 'Choose from the most used Instructors' ),
    'menu_name' => __( 'Instructors' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('instructor','course',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'instructor' ),
  ));
}

/*==============    itvn.org Create Specialization Custom Taxonomy Type   ======================*/
add_action( 'init', 'itvndocorg_create_specialization_nonhierarchical_taxonomy', 0 );
 
function itvndocorg_create_specialization_nonhierarchical_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Specializations', 'taxonomy general name' ),
    'singular_name' => _x( 'Specialization', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Specialization' ),
    'popular_items' => __( 'Popular Specializations' ),
    'all_items' => __( 'All Specializations' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Specialization' ), 
    'update_item' => __( 'Update Specialization' ),
    'add_new_item' => __( 'Add New Specialization' ),
    'new_item_name' => __( 'New Specialization Name' ),
    'separate_items_with_commas' => __( 'Separate Specializations with commas' ),
    'add_or_remove_items' => __( 'Add or remove Specializations' ),
    'choose_from_most_used' => __( 'Choose from the most used Specializations' ),
    'menu_name' => __( 'Specializations' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('specialization','course',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'specialization' ),
  ));
}