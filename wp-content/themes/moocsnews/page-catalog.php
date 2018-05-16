<?php 
/**
 * Template Name: Browse Courses Page
 *
 * @package WordPress
 * @subpackage moocnews
 * @since moocnews 1.0
 */
$breadcrumbs = [];
$breadcrumbs[] = array('title' => 'Home', 'url' => get_site_url()); 
$breadcrumbs[] = array('title' => get_the_title(), 'url' => ''); 
?>

<!-- Get Header -->
<?php get_header(); ?>

<section class="courses">
	<div class="container">
		<!-- BEGIN: Breadcrumb -->
		<div class="row">
			<div class="col-md-12">
				<div class="breadcrumbs">
					<ul class="list-breadcrumb">
						<li class="inline">
							<a class="text-grey" href="javascript:;">
								<span>Home</span>
							</a>
						</li>
						<li class="inline">
							<a class="text-grey" href="javascript:;">
								<i class="fas fa-chevron-right"></i><span>Subjects </span>
							</a>
						</li>
						<li class="inline">
							<a class="text-grey" href="javascript:;">
								<i class="fas fa-chevron-right"></i><span>Art & Design </span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- END: Breadcrumb -->
		<div class="row content">
			<div class="col-md-3">
				<div class="left-filter">
					<h3>
						<span class="number-courses">Showing</span>
						<strong class="text-bold">
							<span class="text--bold" id="number-of-courses">532</span> 
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
					<table class="table table-condensed tbl-courses">
						<thead>
							<tr>
								<th></th>
								<th>Course Name</th>
								<th>Start Date</th>
								<th>Rating</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span><i class="fas fa-list"></i></span></td>
								<td>
									<ul class="institution"><li><a href="javascript:;">University of Pennsylvania</a></li></ul>
									<div class="tbl-course-title"><a  href="javascript:;">
										Design: Creation of Artifacts in Society
									</a></div>
									<span class="block">
										via
										<a href="">Coursera</a>
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
							<tr>
								<td><span><i class="fas fa-list"></i></span></td>
								<td>
									<ul class="institution"><li><a href="javascript:;">University of Pennsylvania</a></li></ul>
									<div class="tbl-course-title"><a  href="javascript:;">
										Design: Creation of Artifacts in Society
									</a></div>
									<span class="block">
										via
										<a href="">Coursera</a>
										<span>
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
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Get Footer -->
<?php get_footer(); ?>