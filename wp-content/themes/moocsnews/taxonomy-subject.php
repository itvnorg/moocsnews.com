<?php 
$term = get_queried_object();
$courses = get_post_by_taxonomy('course', 'subject', $term->slug, -1);
?>

<!-- Get Header -->
<?php get_header(); ?>

<section class="courses">
	<div class="container">
		<!-- BEGIN: Breadcrumb -->
		<div class="row">
			<div class="col-md-12"><?php the_breadcrumb(); ?></div>
		</div>
		<!-- END: Breadcrumb -->
		<div class="row content">
			<div class="col-md-3">
				<div class="left-filter">
					<h3>
						<span class="number-courses">Showing</span>
						<strong class="text-bold">
							<span class="text--bold" id="number-of-courses"><?php echo $term->count; ?></span> 
							courses
						</strong>
					</h3>
					<div class="category-filter">
						<ul class="filter-nav">
							<li class="bg-white padding-horz-small padding-vert-xsmall radius">
								<a href="javascript:;">
									<span>By start date</span>
									<span class="icon"><i class="fas fa-chevron-down"></i></span>
								</a>
								<ul class="main-filter-dropdown ">
									<li>
										<label class="check-item">
											Recently started or starting soon
											<input type="checkbox">
											<span class="checkmark"></span>
										</label>
									</li>
									<li>
										<label class="check-item">
											Recently started or starting soon
											<input type="checkbox">
											<span class="checkmark"></span>
										</label>
									</li>
									<li>
										<label class="check-item">
											Recently started or starting soon
											<input type="checkbox">
											<span class="checkmark"></span>
										</label>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="right-content">
					<div style="overflow-x:auto;">
						<table class="table table-condensed tbl-courses datatable">
						<col width="5%">
						<col width="55%">
						<col width="20%">
						<col width="20%">
						<thead>
							<tr>
								<th></th>
								<th>Course Name</th>
								<th>Start Date</th>
								<th>Rating</th>
							</tr>
						</thead>
						<tbody>

							<?php if ( $courses->have_posts() ) : while ( $courses->have_posts() ) : $courses->the_post(); 
							$meta = get_post_meta( $post->ID, 'course_fields', true ); 
							$institutions = wp_get_post_terms( $post->ID, 'institution' );
							$providers = wp_get_post_terms( $post->ID, 'provider' ); ?>

							<tr>
								<td><span><i class="fas fa-list"></i></span></td>
								<td>
									<ul class="institution">
										<?php if(count($institutions) > 0){ ?>
										<?php foreach ($institutions as $key => $value) { ?>
										<?php if( (count($institutions)-1) == $key ) {?>
										<?php echo '<li><a href="javascript:;">'.$value->name.'</a></li>'; ?>
										<?php } ?>
										<?php } ?>
										<?php } ?>
									</ul>
									<div class="tbl-course-title"><a  href="javascript:;">
										<?php echo $post->post_title; ?>
									</a></div>
									<span class="block">
										via
										<a href="">
											<?php if(count($providers) > 0){ ?>
											<?php foreach ($providers as $key => $value) { ?>
											<?php if( (count($providers)-1) == $key ) {?>
											<?php echo $value->name; ?>
											<?php }else{ ?>
											<?php echo $value->name.', '; ?>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</a>
										<span class="block ml5">
											<span class="icon">
												<i class="far fa-clock"></i>
											</span>
										6-8 hours a week , 5 weeks long</span>
									</span>
								</td>
								<td><span class="start-date">7th May, 2018</span></td>
								<td>
									<div class="rating">
										<span class="review-ratings">
											<span class="icon-rating"><i class="fas fa-star"></i></span>
											<span class="icon-rating"><i class="fas fa-star"></i></span>
											<span class="icon-rating"><i class="fas fa-star"></i></span>
											<span class="icon-rating"><i class="fas fa-star"></i></span>
											<span class="icon-rating"><i class="fas fa-star"></i></span>
										</span>
										<span class="review-number">14 Reviews</span>
									</div>
								</td>
							</tr>

						<?php endwhile; endif; 
						wp_reset_postdata(); ?>

						

						</tbody>
					</table>
					</div>
				<div class="input-group mb-3 col-md-4 pull-right" id="tb-search" >
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
					</div>
					<input type="text" class="form-control" aria-describedby="basic-addon1" id="myInputTextField">
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<script type="text/javascript">
	var $ = jQuery;
	$(document).ready(function() {
		dtTable = $('table.datatable').DataTable({
			"dom": '<"top"i>rt<"bottom clearfix"lp><"clear">'
		});
		$('#myInputTextField').keyup(function(){
			dtTable.search($(this).val()).draw() ;
		})
		$('.dataTables_wrapper .top').append($('#tb-search'));
	} );
</script>
<!-- Get Footer -->
<?php get_footer(); ?>