<?php 
	$meta = get_post_meta( $post->ID, 'course_fields', true ); 
	$institutions = wp_get_post_terms( $post->ID, 'institution' );
	$providers = wp_get_post_terms( $post->ID, 'provider' );
?>
<div class="col-md-4">
	<a href="<?php the_permalink(); ?>" class="course-link-item">
		<div class="course-item">
			<div class="course-card-container">
				<h3 class="institution">
					<?php if(count($institutions) > 0){ ?>
                        <?php foreach ($institutions as $key => $value) { ?>
                            <?php if( (count($institutions)-1) == $key ) {?>
                                <?php echo $value->name; ?>
                            <?php }else{ ?>
                                <?php echo $value->name.', '; ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
				</h3>
				<h4 class="course-source">
					<?php if(count($providers) > 0){ ?>
                        <?php foreach ($providers as $key => $value) { ?>
                            <?php if( (count($providers)-1) == $key ) {?>
                                <?php echo $value->name; ?>
                            <?php }else{ ?>
                                <?php echo $value->name.', '; ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </h4>
				<h2 class="course-title"><?php the_title(); ?></h2>
			</div>
			<div class="course-detail-info">
				<div class="student-interested">
					<div class="text-left">
						<?php $addition_info = '';
						if($meta['effort']){$addition_info =$meta['effort'];}
						elseif($meta['duration']){$addition_info =$meta['duration'];}
						else{$addition_info =$meta['session'];}
						?>
						<span class="student-label"><?php echo $addition_info; ?></span>
					</div>
				</div>
				<!-- <div class="student-interested">
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
				</div> -->
			</div>
		</div>
	</a>
</div>