<?php 
/**
 * Template Name: Course Detail Page
 *
 * @package WordPress
 * @subpackage moocnews
 * @since moocnews 1.0
 */
$meta = get_post_meta( $post->ID, 'course_fields', true ); 
$institutions = wp_get_post_terms( $post->ID, 'institution' );
$providers = wp_get_post_terms( $post->ID, 'provider' ); 
$instructors = wp_get_post_terms( $post->ID, 'instructor' );
$tags = wp_get_post_tags($post->ID);
$term = get_the_terms($post, 'subject');
$related = get_post_by_taxonomy('course', 'subject', $term[0]->slug);
?>

<!-- Get Header -->
<?php get_header(); ?>

<section class="course-detail">
	<div class="container">
		<!-- BEGIN: Breadcrumb -->
		<div class="row">
			<div class="col-md-12"><?php the_breadcrumb(); ?></div>
		</div>
		<!-- END: Breadcrumb -->
		<div class="row content-detail">
			<div class="col-md-8">
				<div class="left-content">
					<h1 class="course-tile"><?php the_title(); ?></h1>
					<p class="institution-source">
										<?php if(count($institutions) > 0){ ?>
										<?php foreach ($institutions as $key => $value) { ?>
										<?php if( (count($institutions)-1) == $key ) {?>
										<?php echo '<a class="insitution" href="'./*get_term_link($value)*/'javascript:;'.'">'.$value->name.'</a>'; ?>
										<?php }else{ ?> 
										<?php echo '<a class="insitution" href="'./*get_term_link($value)*/'javascript:;'.'">'.$value->name.'</a>,'; ?>
										<?php } } } ?>
										 via 
										<?php if(count($providers) > 0){ ?>
										<?php foreach ($providers as $key => $value) { ?>
										<?php if( (count($providers)-1) == $key ) {?>
										<?php echo '<a class="source" href="'./*get_term_link($value)*/'javascript:;'.'">'.$value->name.'</a>'; ?>
										<?php }else{ ?>
										<?php echo '<a class="source" href="'./*get_term_link($value)*/'javascript:;'.'">'.$value->name.'</a>, '; ?>
										<?php } } } ?>
										</p>
					<div class="description-course">
						<ul class="nav nav-tabs nav-desc" id="nav-desc" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="aboutcourse-tab" data-toggle="tab" href="#aboutcourse" role="tab" aria-controls="home" aria-selected="true">About this course</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="syllabus-tab" data-toggle="tab" href="#syllabus" role="tab" aria-controls="profile" aria-selected="false">Syllabus</a>
							</li>
						</ul>
						<div class="tab-content" id="desc-tab">
							<div class="tab-pane fade show active" id="aboutcourse" role="tabpanel" aria-labelledby="aboutcourse-tab">
								<?php echo $meta['about_this_course']; ?>
							</div>
							<div class="tab-pane fade" id="syllabus" role="tabpanel" aria-labelledby="syllabus-tab">
								<?php echo $meta['syllabus']; ?>
							</div>
						</div>
					</div>
					<div class="instructor border-bottom padding-vert-large">
						<h4 class="taught-by font-18">Taught by</h4>
						<div class="instructor-name">
							<?php if(count($instructors) > 0){ ?>
							<?php foreach ($instructors as $key => $value) { $meta_instructor = get_term_meta( $value->term_id, 'instructor_meta', true ); ?>
							<?php if( (count($instructors)-1) == $key ) {?>
							<?php echo $meta_instructor['instructor_name']; ?>
							<?php }else{ ?>
							<?php echo $meta_instructor['instructor_name'].', '; ?>
							<?php } ?>
							<?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="moocnews-chart">
						
					</div>
					<div class="tags border-bottom padding-vert-large">
						<h4 class="font-18">Tags</h4>
						<?php if(count($tags) > 0){ ?>
						<?php foreach ($tags as $key => $value) { ?>
						<?php echo '<a href="'./*get_tag_link($value)*/'javascript:;'.'">'.$value->name.'</a>'; ?>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
				<!-- BEGIN:  Relate course -->
				<div class="relate-course">
					<h3 class="section-title">realated courses</h3>
					<ul>
					<?php if ( $related->have_posts() ) : while ( $related->have_posts() ) : $related->the_post(); 
					$r_institutions = wp_get_post_terms( $post->ID, 'institution' );
					$r_providers = wp_get_post_terms( $post->ID, 'provider' ); ?>
						<li class="border-bottom pd-t10 pd-b10">
							<div class="item-relate-course">
								<ul class="list-institutions">
									<?php if(count($r_institutions) > 0){ ?>
									<?php foreach ($r_institutions as $key => $value) { ?>
									<?php echo '<li class="inline"><a href="'./*get_term_link($value)*/'javascript:;'.'"><span>'.$value->name.'</span></a></li>'; ?>
									<?php } } ?>
								</ul>
								<a class="relate-course-title" href=""><?php echo $post->post_title; ?></a>
								<div class="provider">
									<span>via 
										<?php if(count($r_providers) > 0){ ?>
										<?php foreach ($r_providers as $key => $value) { ?>
										<?php if( (count($r_providers)-1) == $key ) {?>
										<?php echo '<a href="'./*get_term_link($value)*/'javascript:;'.'" class="text-grey-bold text-italic">'.$value->name.'</a>'; ?>
										<?php }else{ ?>
										<?php echo '<a href="'./*get_term_link($value)*/'javascript:;'.'" class="text-grey-bold text-italic">'.$value->name.'</a>, '; ?>
										<?php } } } ?>
									</span>
								</div>
							</div>
						</li>
					<?php endwhile; endif; 
					wp_reset_postdata(); ?>
					</ul>
				</div>
				<!-- END:  Relate course -->
				<!-- BEGIN: Browse more -->
				<div class="browse-more">
					<a href="<?php echo get_term_link($term[0]); ?>">
						<span class="bold">browse more</span>
						<span class="light"><?php echo $term[0]->name; ?></span>
					</a>
				</div>
				<!-- END: Browse more -->
			</div>
			<div class="col-md-4">
				<div class="right-bar">
					<div class="course-info">
						<div class="course-image border-bottom">
							<img class="img-reponsive" src="<?php echo get_site_url(); ?>/timthumb.php?src=<?php	the_post_thumbnail_url(); ?>&w=346&h=194" alt="course title" />
						</div>
						<div class="go-to-class">
							<a href="<?php echo $meta['link_intro_course']; ?>">Go to class <span><i class="fas fa-arrow-right"></i></span></a>
						</div>
						<ul class="list-item">
							<?php if(count($providers) > 0){ ?>
							<li>
								<strong class="item-label">provider</strong>
							<?php foreach ($providers as $key => $value) { ?>
							<?php if( (count($providers)-1) == $key ) {?>
							<?php echo '<a class="item-link" href="'./*get_term_link($value)*/'javascript:;'.'"><span class="item-text">'.$value->name.'</span></a>'; ?>
							<?php }else{ ?>
							<?php echo '<a class="item-link" href="'./*get_term_link($value)*/'javascript:;'.'"><span class="item-text">'.$value->name.'</span></a>, '; ?>
							<?php } } ?>
							</li>
							<?php } ?>

							<?php if(count($term) > 0){ ?>
							<li>
								<strong class="item-label">subject</strong>
							<?php foreach ($term as $key => $value) { ?>
							<?php if( (count($term)-1) == $key ) {?>
							<?php echo '<a class="item-link" href="'.get_term_link($value).'"><span class="item-text">'.$value->name.'</span></a>'; ?>
							<?php }else{ ?>
							<?php echo '<a class="item-link" href="'.get_term_link($value).'"><span class="item-text">'.$value->name.'</span></a>, '; ?>
							<?php } } ?>
							</li>
							<?php } ?>

							<?php if($meta['cost']){ ?>
							<li>
								<strong class="item-label">$ cost</strong>
								<span class="item-text"><?php echo $meta['cost']; ?></span>
							</li>
							<?php } ?>

							<?php if($meta['session']){ ?>
							<li>
								<strong class="item-label">session</strong>
								<span class="item-text"><?php echo $meta['session']; ?></span>
							</li>
							<?php } ?>

							<?php if($meta['language']){ ?>
							<li>
								<strong class="item-label">language</strong>
								<span class="item-text"><?php echo $meta['language']; ?></span>
							</li>
							<?php } ?>

							<?php if($meta['certificate']){ ?>
							<li>
								<strong class="item-label">certificate</strong>
								<span class="item-text"><?php echo $meta['certificate']; ?></span>
							</li>
							<?php } ?>
							
							<?php if($meta['effort']){ ?>
							<li>
								<strong class="item-label">effort</strong>
								<span class="item-text"><?php echo $meta['effort']; ?></span>
							</li>
							<?php } ?>
							
							<?php if($meta['start_date']){ ?>
							<li>
								<strong class="item-label">start date</strong>
								<span class="item-text"><?php echo $meta['start_date']; ?></span>
							</li>
							<?php } ?>
							
							<?php if($meta['duration']){ ?>
							<li>
								<strong class="item-label">duration</strong>
								<span class="item-text"><?php echo $meta['duration']; ?></span>
							</li>
							<?php } ?>
							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Get Footer -->
<?php get_footer(); ?>