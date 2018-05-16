<?php 
/**
 * Template Name: Providers Page
 *
 * @package WordPress
 * @subpackage vCampus
 * @since vCampus 1.0
 */
$providers = get_terms( array(
    'taxonomy' => 'provider',
    'hide_empty' => false,
    'orderby'	=> 'count',
    'order'		=>	'DESC',
) );

?>

<!-- Main Content -->
<?php get_header(); ?>
<section class="main-content">
 	<div class="container">
    	<div id="content" class="page-content">
	 		<div class="row"><div class="col-md-12"><?php the_breadcrumb(); ?></div></div>
	 		<h1 class="page-title"><?php the_title(); ?><span><?php echo ' ('.count($providers).')'; ?></span></h1> <!-- Page Title -->
	 		<div class="row">
	 			<div class="col-md-12">
					<div style="overflow-x:auto;">
						<table class="table table-condensed tbl-courses datatable">
						<col width="30%">
						<col width="30%">
						<col width="40%">
						<tbody>
							<?php foreach ($providers as $key => $value) { ?>
								<tr>
									<td><span class="text-primary font-weight-bold"><?php echo $value->name; ?></span></td>
									<td><span><?php echo $value->count.' '.__('Courses','moocsnews'); ?></span></td>
									<td><a class="btn btn-primary" href="<?php echo get_category_link( $value ); ?>"><?php echo __('View Provider Page','moocsnews'); ?></a></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
    		</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>