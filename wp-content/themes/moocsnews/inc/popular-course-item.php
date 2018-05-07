<div class="row">
	<?php
	$course = array("1","2","3");
	?>
	<?php foreach($course as $key=>$value): ?>
		<div class="col-md-4">
			<a href="javascript:;" class="course-link-item">
				<div class="course-item">
					<div class="course-card-container">
						<h3 class="institution">University of California, San Diego</h3>
						<h4 class="course-source">Coursera</h4>
						<h2 class="course-title">Learning How to Learn: Powerful mental tools to help you master tough subjects</h2>
					</div>
					<div class="course-detail-info">
						<div class="student-interested">
							<div class="text-left">
								<span class="number-student">40.7k</span>
								<span class="student-label">student</br>interested</span>
							</div>
						</div>
						<div class="course-rating">
							<span class="rating-icons">
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							</span>
							<span class="reviews-number">4181 Reviews</span>
						</div>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach; ?>
</div>