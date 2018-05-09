<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">All</a>
  </li>
  <?php 
  foreach ($subjects as $key => $value) {
    if($key > 3){break;}
    ?>
    <li class="nav-item">
      <a class="nav-link" id="profile-<?php echo $value->slug; ?>" data-toggle="tab" href="#<?php echo $value->slug; ?>" role="tab" aria-controls="<?php echo $value->slug; ?>" aria-selected="false"><?php echo $value->name; ?></a>
    </li>
    <?php
  }
  ?>
  <li class="linnk-all-subject">
    <a href="<?php echo $the_home.'/subjects'; ?>">
      View all subjects <i class="fas fa-chevron-right"></i>
    </a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
      <?php 
      $args = array(
        'post_type' => 'course',
        'posts_per_page' => '6',
          // 'meta_key' => 'itvndocorg_post_views_count', 
          // 'orderby' => 'meta_value_num', 
          // 'order' => 'DESC' ,
      );  
      $courses = new WP_Query( $args ); 

      if ( $courses->have_posts() ) : while ( $courses->have_posts() ) : $courses->the_post(); 

        get_template_part( 'inc/course-item-grid', get_post_format() );

      endwhile; endif; 
      wp_reset_postdata(); 
      ?>
    </div>
  </div>

  <?php 
  foreach ($subjects as $key => $value) {
    if($key > 3){break;}
    ?>
    <div class="tab-pane fade" id="<?php echo $value->slug; ?>" role="tabpanel" aria-labelledby="<?php echo $value->slug; ?>-tab">
      <div class="row">
        <?php
        $courses = get_post_by_taxonomy('course', 'subject', $value->slug, 6);

        if ( $courses->have_posts() ) : while ( $courses->have_posts() ) : $courses->the_post();

          get_template_part( 'inc/course-item-grid', get_post_format() ); 

        endwhile; endif; 
        wp_reset_postdata(); 
        ?>
      </div>
    </div>
    <?php
  }
  ?>

</div>
<div class="text-center button-show-more"><a href="<?php echo $the_home.'/subjects'; ?>" class="btn btn-outline-primary"><?php echo __('Show More'); ?></a></div>