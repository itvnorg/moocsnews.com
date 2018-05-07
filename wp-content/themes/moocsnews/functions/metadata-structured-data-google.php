<?php

add_action( 'wp_head', 'add_metadata_structured_data' , -1000);

function add_metadata_structured_data(){
	if(is_front_page()){
		home_front_page_add_metadata_structured_data();
	}

	if(is_page()){
		if(!is_page_template('page-catalog.php') && !is_page_template('page-categories.php')){
			others_page_add_metadata_structured_data();
		}
	}

	if(is_tag()){
		tag_page_add_metadata_structured_data();
	}

	if(is_page_template('page-catalog.php')){
		catalog_add_metadata_structured_data();
	}

	if(is_page_template('page-categories.php')){
		categories_add_metadata_structured_data();
	}

	if(is_category()){
		category_add_metadata_structured_data();
	}

	if(is_single()){
		add_course_detail_metadata_structured_data();
	}
}

/*==============	itvn.org Add meta description (SEO) to Home Page 	======================*/
function home_front_page_add_metadata_structured_data(){
	?>
	<!-- BEGIN: Meta description for SEO. -->
	<link rel="canonical" href="<?php echo get_home_url(); ?>" />
	<!-- Facebook & Twitter -->
	<meta property="og:title" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_home_url(); ?>" />
	<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php echo get_theme_mod('footer_about_us'); ?>" />
	<meta property="og:updated_time" content="<?php echo date('Y-m-d'); ?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:locale:alternate" content="vi_VN" />
	<!-- Google+ -->
	<meta name="description" content="<?php echo get_theme_mod('footer_about_us'); ?>" />
	<meta itemprop="name" content="<?php echo bloginfo('name'); ?>">
	<meta itemprop="description" content="<?php echo get_theme_mod('footer_about_us'); ?>">
	<!-- END: Meta description for SEO. -->

	<!-- Structured Data Google -->
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "WebSite",
	  "url": "<?php echo get_home_url(); ?>",
	  "potentialAction": {
	    "@type": "SearchAction",
	    "target": "<?php echo get_home_url().'/courses?search_term={search_term_string}'; ?>",
	    "query-input": "required name=search_term_string"
	  }
	}
	</script>
	<!-- End: Structured Data Google -->

	<?php
}

/*==============	itvn.org Add meta description (SEO) to Others Page 	======================*/
function others_page_add_metadata_structured_data(){
	global $post;  
	$meta_page = get_post_meta( $post->ID, 'page_fields', true ); 
	?>
	<!-- BEGIN: Meta description for SEO. -->
	<link rel="canonical" href="<?php echo get_permalink( $post->ID ); ?>" />
	<!-- Facebook & Twitter -->
	<meta property="og:title" content="<?php echo $post->post_title; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_permalink( $post->ID ); ?>" />
	<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php echo $meta_page['description']; ?>" />
	<meta property="og:updated_time" content="<?php echo $post->post_modified; ?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:locale:alternate" content="vi_VN" />
	<!-- Google+ -->
	<meta name="description" content="<?php echo $meta_page['description']; ?>" />
	<meta itemprop="name" content="<?php echo $post->post_title; ?>">
	<meta itemprop="description" content="<?php echo $meta_page['description']; ?>">
	<!-- END: Meta description for SEO. -->

	<!-- Structured Data Google -->
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "BreadcrumbList",
	  "itemListElement": [{
	    "@type": "ListItem",
	    "position": 1,
	    "item": {
	      "@id": "<?php echo get_home_url(); ?>",
	      "name": "Home"
	    }
	  },{
	    "@type": "ListItem",
	    "position": 2,
	    "item": {
	      "@id": "<?php echo get_permalink( $post->ID ); ?>",
	      "name": "<?php echo $post->post_title; ?>"
	    }
	  }]
	}
	</script>
	<!-- End: Structured Data Google -->

	<?php
}

/*==============	itvn.org Add meta description (SEO) to Course Detail 	======================*/
function add_course_detail_metadata_structured_data(){
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$course_id = get_the_ID();

		$args = array( 
			'post_type' => 'course',
			'post__in' => array($course_id) 
		);

		//--> Get course
		$course = get_posts($args);

		$meta = get_post_meta( $course_id, 'course_fields', true );
		$course_content = strip_tags($meta['description']);

		//---> Get category by course
		$category_detail=get_the_category($course->ID);
		foreach($category_detail as $cd){
			$category_name = $cd->cat_name;
		}
	endwhile; endif; 

	$course_detail = $course[0];

	//---> Get course image
	if (has_post_thumbnail( $course->ID ) ){
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $course->ID ), 'single-post-thumbnail' );
		$post_image = $image[0];
		$post_image = get_home_url().'/timthumb.php?src='.$post_image.'&w=600&h=315';
	}


	//---> Parse to meta tag.
	if($course_id){//---> BEGIN: If
	?>
		<!-- BEGIN: Meta description for SEO. -->
		<link rel="canonical" href="<?php echo get_home_url(); ?>/course/<?php echo $course_detail->post_name; ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $course_detail->post_title; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php echo $post_image; ?>" />
		<meta property="og:url" content="<?php echo get_home_url(); ?>/course/<?php echo $course_detail->post_name; ?>" />
		<?php if( !empty($meta['video_introduction']) ){ ?>
			<?php if($meta['video_type'] != 'youtube') { ?>
				<meta property="og:video" content="<?php echo $meta['video_introduction']; ?>" />
			<?php }else{ ?>
				<meta property="og:video" content="<?php echo 'https://www.youtube.com/embed/'.$meta['video_introduction']; ?>" />
			<?php } ?>
			<meta property="og:video:width" content="400" />
			<meta property="og:video:height" content="300" />
		<?php } ?>
		<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php echo $meta['description']; ?>" />
		<meta property="og:updated_time" content="<?php echo $course_detail->post_modified; ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:locale:alternate" content="vi_VN" />
		<meta property="article:section" content="<?php echo $category_name; ?>" />
		<meta property="article:published_time" content="<?php echo $course_detail->post_date; ?>" />
		<meta property="article:modified_time" content="<?php echo $course_detail->post_modified; ?>" />
		<meta property="og:image:width" content="600" />
		<meta property="og:image:height" content="315" />
		<meta property="og:image:alt" content="<?php echo $course_detail->post_title; ?>" />
		<!-- Google+ -->
		<meta name="description" content="<?php echo $meta['description']; ?>" />
		<meta itemprop="name" content="<?php echo $course_detail->post_title; ?>">
		<meta itemprop="description" content="<?php echo $meta['description']; ?>">
		<meta itemprop="image" content="<?php echo $post_image; ?>">
		<!-- END: Meta description for SEO. -->

		<!-- Structured Data Google -->
		<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "Course",
		  "name": "<?php echo $course_detail->post_title; ?>",
		  "description": "<?php echo $meta['description']; ?>",
		  "provider": {
		    "@type": "Organization",
		    "name": "<?php echo $meta['institution']; ?>"
		  }
		}
		</script>
		<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "BreadcrumbList",
		  "itemListElement": [{
		    "@type": "ListItem",
		    "position": 1,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo get_home_url().'/courses'; ?>",
		      "name": "Courses"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 3,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>/course/<?php echo $course_detail->post_name; ?>",
		      "name": "<?php echo $course_detail->post_title; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}

/*==============	itvn.org Add meta description (SEO) to Category 	======================*/
function category_add_metadata_structured_data(){
	$uri_requests = explode('category/', $_SERVER['REQUEST_URI']);
	$categories = explode('?', $uri_requests[1]);
	$category = get_category_by_slug($categories[0]);
	//---> Parse to meta tag.
	if($category){//---> BEGIN: If
		$args = array(
			'post_type' 			=> 	'course',
			'posts_per_page'		=> 	5,
			'paged'					=>	1,						
			'orderby' 				=> 'ID',
			'order'   				=> 'DESC',
		); 
		
		// Filter with Category
		if($filter_cats != null){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $category->slug,
				)
			);									
		}
		
		$courses = new WP_Query( $args );
		$json_ld = '';
		$i = 1;
		if ( $courses->have_posts() ){
			foreach ($courses->posts as $key => $value) {
				$meta = get_post_meta( $value->ID, 'course_fields', true );
				$json_ld .= '{
			      	"@type": "ListItem",
			      	"position": "'.$i.'",
			      	"description": "'.$meta['description'].'",
      				"url":"'.get_post_permalink( $value->ID ).'"
    			}';
				if($i != count($courses->posts)){
					$json_ld .= ',';
				}
				$i++;
			}
			
		}
			
		wp_reset_postdata(); 
	?>
		<!-- BEGIN: Meta description for SEO. -->
		<link rel="canonical" href="<?php echo get_home_url(); ?>/category/<?php echo $category->slug; ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $category->description; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo get_home_url(); ?>/category/<?php echo $category->slug; ?>" />
		<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php echo $category->description; ?>" />
		<meta property="og:updated_time" content="<?php echo date('Y-m-d'); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:locale:alternate" content="vi_VN" />
		<!-- Google+ -->
		<meta name="description" content="<?php echo $category->description; ?>" />
		<meta itemprop="name" content="<?php echo $category->name; ?>">
		<meta itemprop="description" content="<?php echo $category->description; ?>">
		<!-- END: Meta description for SEO. -->

		<!-- Structured Data Google -->
		<script type="application/ld+json">
		{
			"@context":"http://schema.org",
  			"@type": "ItemList",
  			"itemListElement": [
    			<?php echo $json_ld; ?>
		  	]
		}
		</script>
		<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "BreadcrumbList",
		  "itemListElement": [{
		    "@type": "ListItem",
		    "position": 1,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo get_home_url().'/categories'; ?>",
		      "name": "Categories"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 3,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>/category/<?php echo $category->slug; ?>",
		      "name": "<?php echo $category->name; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}

/*==============	itvn.org Add meta description (SEO) to Categories 	======================*/
function categories_add_metadata_structured_data(){
	global $post;  
	$meta_page = get_post_meta( $post->ID, 'page_fields', true ); 
	$categories = get_categories(); 
	$json_ld = '';
	$i = 1;
	if ( count($categories) > 0 ){
		foreach ($categories as $key => $value) {
			$json_ld .= '{
		      	"@type": "ListItem",
		      	"position": "'.$i.'",
  				"url":"'.get_category_link( $value ).'"
			}';
			if($i < count($categories)){
				$json_ld .= ',';
			}
			$i++;
		}
		
	}
	?>
	<!-- BEGIN: Meta description for SEO. -->
	<link rel="canonical" href="<?php echo get_permalink( $post->ID ); ?>" />
	<!-- Facebook & Twitter -->
	<meta property="og:title" content="<?php echo $post->post_title; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_permalink( $post->ID ); ?>" />
	<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php echo $meta_page['description']; ?>" />
	<meta property="og:updated_time" content="<?php echo $post->post_modified; ?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:locale:alternate" content="vi_VN" />
	<!-- Google+ -->
	<meta name="description" content="<?php echo $meta_page['description']; ?>" />
	<meta itemprop="name" content="<?php echo $post->post_title; ?>">
	<meta itemprop="description" content="<?php echo $meta_page['description']; ?>">
	<!-- END: Meta description for SEO. -->

	<!-- Structured Data Google -->
	<script type="application/ld+json">
	{
		"@context":"http://schema.org",
			"@type": "ItemList",
			"itemListElement": [
			<?php echo $json_ld; ?>
	  	]
	}
	</script>
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "BreadcrumbList",
	  "itemListElement": [{
	    "@type": "ListItem",
	    "position": 1,
	    "item": {
	      "@id": "<?php echo get_home_url(); ?>",
	      "name": "Home"
	    }
	  },{
	    "@type": "ListItem",
	    "position": 2,
	    "item": {
	      "@id": "<?php echo get_permalink( $post->ID ); ?>",
	      "name": "<?php echo $post->post_title; ?>"
	    }
	  }]
	}
	</script>
	<!-- End: Structured Data Google -->

	<?php
}

/*==============	itvn.org Add meta description (SEO) to Catalog 	======================*/
function catalog_add_metadata_structured_data(){
	global $post;  
	$meta_page = get_post_meta( $post->ID, 'page_fields', true ); 
	$args = array(
		'post_type' 			=> 	'course',
		'posts_per_page'		=> 	5,
		'paged'					=>	1,						
		'orderby' 				=> 'post_date',
		'order'   				=> 'DESC',
	); 
	
	$courses = new WP_Query( $args );
	$json_ld = '';
	$i = 1;
	if ( $courses->have_posts() ){
		foreach ($courses->posts as $key => $value) {
			$meta = get_post_meta( $value->ID, 'course_fields', true );
			$json_ld .= '{
		      	"@type": "ListItem",
		      	"position": "'.$i.'",
  				"url":"'.get_post_permalink( $value->ID ).'"
			}';
			if($i != count($courses->posts)){
				$json_ld .= ',';
			}
			$i++;
			if($key == 0){
				//---> Get course image
				if (has_post_thumbnail( $value->ID ) ){
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $value->ID ), 'single-post-thumbnail' );
					$post_image = get_home_url().'/timthumb.php?src='.$image[0].'&w=600&h=315';
				}
			}
		}
		
	}
		
	wp_reset_postdata(); 
	?>
	<!-- BEGIN: Meta description for SEO. -->
	<link rel="canonical" href="<?php echo get_permalink( $post->ID ); ?>" />
	<!-- Facebook & Twitter -->
	<meta property="og:title" content="<?php echo $post->post_title; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_permalink( $post->ID ); ?>" />
	<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php echo $meta_page['description']; ?>" />
	<meta property="og:updated_time" content="<?php echo $post->post_modified; ?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:locale:alternate" content="vi_VN" />
	<!-- Google+ -->
	<meta name="description" content="<?php echo $meta_page['description']; ?>" />
	<meta itemprop="name" content="<?php echo $post->post_title; ?>">
	<meta itemprop="description" content="<?php echo $meta_page['description']; ?>">
	<!-- END: Meta description for SEO. -->

	<!-- Structured Data Google -->
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "WebSite",
	  "url": "<?php echo get_home_url(); ?>",
	  "potentialAction": {
	    "@type": "SearchAction",
	    "target": "<?php echo get_home_url().'/courses?search_term={search_term_string}'; ?>",
	    "query-input": "required name=search_term_string"
	  }
	}
	</script>
	<script type="application/ld+json">
	{
		"@context":"http://schema.org",
			"@type": "ItemList",
			"itemListElement": [
			<?php echo $json_ld; ?>
	  	]
	}
	</script>
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "BreadcrumbList",
	  "itemListElement": [{
	    "@type": "ListItem",
	    "position": 1,
	    "item": {
	      "@id": "<?php echo get_home_url(); ?>",
	      "name": "Home"
	    }
	  },{
	    "@type": "ListItem",
	    "position": 2,
	    "item": {
	      "@id": "<?php echo get_permalink( $post->ID ); ?>",
	      "name": "<?php echo $post->post_title; ?>"
	    }
	  }]
	}
	</script>
	<!-- End: Structured Data Google -->

	<?php
}

/*==============	itvn.org Add meta description (SEO) to Category 	======================*/
function tag_page_add_metadata_structured_data(){
	$uri_requests = explode('tag/', $_SERVER['REQUEST_URI']); 
	$tags_arr = explode('?', $uri_requests[1]);
	$tag_slug = $tags_arr[0];
	$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
	//---> Parse to meta tag.
	if($tag){//---> BEGIN: If
		$args = array(
			'post_type' 			=> 	'course',
			'posts_per_page'		=> 	5,
			'paged'					=>	1,						
			'orderby' 				=> 'ID',
			'order'   				=> 'DESC',
		); 
		
		// Filter with Category
		if($filter_cats != null){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $tag_slug,
				)
			);									
		}
		
		$courses = new WP_Query( $args );
		$json_ld = '';
		$i = 1;
		if ( $courses->have_posts() ){
			foreach ($courses->posts as $key => $value) {
				$meta = get_post_meta( $value->ID, 'course_fields', true );
				$json_ld .= '{
			      	"@type": "ListItem",
			      	"position": "'.$i.'",
			      	"description": "'.$meta['description'].'",
      				"url":"'.get_post_permalink( $value->ID ).'"
    			}';
				if($i != count($courses->posts)){
					$json_ld .= ',';
				}
				$i++;
			}
			
		}
			
		wp_reset_postdata(); 
	?>

		<!-- BEGIN: Meta description for SEO. -->
		<link rel="canonical" href="<?php echo get_home_url(); ?>/tag/<?php echo $tag->slug; ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $tag->name; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo get_home_url(); ?>/tag/<?php echo $tag->slug; ?>" />
		<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php echo $tag->description; ?>" />
		<meta property="og:updated_time" content="<?php echo date('Y-m-d'); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:locale:alternate" content="vi_VN" />
		<!-- Google+ -->
		<meta name="description" content="<?php echo $tag->description; ?>" />
		<meta itemprop="name" content="<?php echo $tag->name; ?>">
		<meta itemprop="description" content="<?php echo $tag->description; ?>">
		<!-- END: Meta description for SEO. -->

		<!-- Structured Data Google -->
		<script type="application/ld+json">
		{
			"@context":"http://schema.org",
  			"@type": "ItemList",
  			"itemListElement": [
    			<?php echo $json_ld; ?>
		  	]
		}
		</script>
		<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "BreadcrumbList",
		  "itemListElement": [{
		    "@type": "ListItem",
		    "position": 1,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo get_home_url(); ?>/tag/<?php echo $tag->slug; ?>",
		      "name": "<?php echo $tag->name; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}