<?php 
/**
 * Template Name: Subjects Page
 *
 * @package WordPress
 * @subpackage vCampus
 * @since vCampus 1.0
 */
$subjects = get_terms( array(
    'taxonomy' => 'subject',
    'hide_empty' => false,
    'orderby'	=> 'count',
    'order'		=>	'DESC',
) );

// Display subject item
function display_subject_item($item){
	echo '<div class="col-md-4 col-sm-6"><div class="category-item link-decoration-none"><div class="block-head"><a href="'.get_category_link( $item ).'"><span class="block-title">'.$item->name.'</span><span class="block-count">'.$item->count.' '.__('Courses').'</span></a></div>';
	$courses = get_post_by_taxonomy('course', 'subject', $item->slug);
	echo '<ul class="list-group list-group-customize-1">';
	if ( $courses->have_posts() ) : while ( $courses->have_posts() ) : $courses->the_post(); 

		display_course_item();

	endwhile; endif; 
	wp_reset_postdata(); 
	echo '</ul></div></div>';
}

// Display course item in subject item
function display_course_item(){
	global $post;
	?>
	<li class="list-group-item item-inline link-gray link-underline">
		<a href="<?php echo get_post_permalink( $post->ID ); ?>" class="title" title='<?php echo $post->post_title; ?>'><?php echo $post->post_title; ?></a>
	</li>
	<?php
}
?>

<!-- Main Content -->
<?php get_header(); ?>
<section class="main-content">
 	<div class="container">
    	<div id="content" class="page-content">
	 		<div class="row"><div class="col-md-12"><?php the_breadcrumb(); ?></div></div>
	 		<h1 class="page-title"><?php the_title(); ?></h1> <!-- Page Title -->
	 		<div class="row">
    		<?php 
    			foreach ($subjects as $item) {
    				display_subject_item($item);
    			}
    		 ?>
    		</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>