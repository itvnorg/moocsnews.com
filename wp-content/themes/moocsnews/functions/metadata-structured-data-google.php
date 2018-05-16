<?php

add_action( 'wp_head', 'add_metadata_structured_data' , -1000);

function add_metadata_structured_data(){
	if(is_front_page()){
		home_front_page_add_metadata_structured_data();
	}

	if(is_tag()){
		tag_page_add_metadata_structured_data();
	}
	if(is_page()){
		if( is_page_template('page-subjects.php') || is_page_template('page-providers.php') || is_page_template('page-institutions.php') ){
			taxonomies_add_metadata_structured_data();
		}else{
			others_page_add_metadata_structured_data();
		}
	}

	if(is_single()){
		add_course_detail_metadata_structured_data();
	}

	if(is_tax()){
		taxonomy_detail_add_metadata_structured_data();
	}
}

/*==============	itvn.org Add meta description (SEO) to Home Page 	======================*/
function home_front_page_add_metadata_structured_data(){
	global $the_home;
	?>
	<!-- BEGIN: Meta description for SEO. -->
	<link rel="canonical" href="<?php echo $the_home; ?>" />
	<!-- Facebook & Twitter -->
	<meta property="og:title" content="<?php echo bloginfo('name'); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo $the_home; ?>" />
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
	  "url": "<?php echo $the_home; ?>",
	  "potentialAction": {
	    "@type": "SearchAction",
	    "target": "<?php echo $the_home.'?s={s}'; ?>",
	    "query-input": "required name=s"
	  }
	}
	</script>
	<!-- End: Structured Data Google -->

	<?php
}

/*==============	itvn.org Add meta description (SEO) to Others Page 	======================*/
function others_page_add_metadata_structured_data(){
	global $the_home;
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
	      "@id": "<?php echo $the_home; ?>",
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
	global $the_home;
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$course_id = get_the_ID();

		$args = array( 
			'post_type' => 'course',
			'post__in' => array($course_id) 
		);

		//--> Get course
		$course = get_posts($args);

		$meta = get_post_meta( $course_id, 'course_fields', true );
		$providers = wp_get_post_terms( $course_id, 'provider' ); 
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
		$post_image = $the_home.'/timthumb.php?src='.$post_image.'&w=600&h=315';
	}


	//---> Parse to meta tag.
	if($course_id){//---> BEGIN: If
	?>
		<!-- BEGIN: Meta description for SEO. -->
		<link rel="canonical" href="<?php echo $the_home; ?>/course/<?php echo $course_detail->post_name; ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $course_detail->post_title; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php echo $post_image; ?>" />
		<meta property="og:url" content="<?php echo $the_home; ?>/course/<?php echo $course_detail->post_name; ?>" />
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
		    "name": "<?php echo $providers[0]->name; ?>"
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
		      "@id": "<?php echo $the_home; ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo get_term_link($providers[0]); ?>",
		      "name": "<?php echo $providers[0]->name; ?>"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 3,
		    "item": {
		      "@id": "<?php echo the_permalink(); ?>",
		      "name": "<?php echo $course_detail->post_title; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}

/*==============	itvn.org Add meta description (SEO) to Categories 	======================*/
function taxonomies_add_metadata_structured_data(){
	global $the_home;
	global $post;  
	$taxonomy_name = '';
	if(is_page_template('page-subjects.php')){
		$taxonomy_name = 'subject';
	}
	if(is_page_template('page-institutions.php')){
		$taxonomy_name = 'institution';
	}
	if(is_page_template('page-providers.php')){
		$taxonomy_name = 'provider';
	}
	$meta_page = get_post_meta( $post->ID, 'page_fields', true ); 
	$terms = get_terms( array(
	    'taxonomy' => $taxonomy_name,
    	'orderby'    => 'count',
        'order' => 'ASC',
        'number' => 10,
	    'hide_empty' => false,
	) );
	$json_ld = '';
	$i = 1;
	if ( count($terms) > 0 ){
		foreach ($terms as $key => $value) {
			$json_ld .= '{
		      	"@type": "ListItem",
		      	"position": "'.$i.'",
  				"url":"'.get_category_link( $value ).'"
			}';
			if($i < count($terms)){
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
	      "@id": "<?php echo $the_home; ?>",
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
	global $the_home;
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
		
		// Filter with Tag
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $tag_slug,
			)
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
		<link rel="canonical" href="<?php echo $the_home; ?>/tag/<?php echo $tag->slug; ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $tag->name; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo $the_home; ?>/tag/<?php echo $tag->slug; ?>" />
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
		      "@id": "<?php echo $the_home; ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo $the_home; ?>/tag/<?php echo $tag->slug; ?>",
		      "name": "<?php echo $tag->name; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}

/*==============	itvn.org Add meta description (SEO) to Categories 	======================*/
function taxonomy_detail_add_metadata_structured_data(){
	global $the_home;
	global $post;  
	$link_taxs = '';
	$tax_name = '';
	$tax_display_name = '';
	if(is_tax('subject')){
		$link_taxs = $the_home.'/subjects';
		$tax_name = 'subject';
		$tax_display_name = 'Subjects';
	}
	if(is_tax('provider')){
		$link_taxs = $the_home.'/providers';
		$tax_name = 'provider';
		$tax_display_name = 'Providers';
	}
	if(is_tax('institution')){
		$link_taxs = $the_home.'/institutions';
		$tax_name = 'institution';
		$tax_display_name = 'Institutions';
	}

	$term = get_queried_object();

	//---> Parse to meta tag.
	if($term){//---> BEGIN: If
		$args = array(
			'post_type' 			=> 	'course',
			'posts_per_page'		=> 	5,
			'paged'					=>	1,						
			'orderby' 				=> 'ID',
			'order'   				=> 'DESC',
		); 
		
		// Filter with Term
		$args['tax_query'] = array(
			array(
				'taxonomy' => $tax_name,
				'field'    => 'slug',
				'terms'    => $term->slug,
			)
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
		<link rel="canonical" href="<?php echo get_term_link($term); ?>" />
		<!-- Facebook & Twitter -->
		<meta property="og:title" content="<?php echo $term->description; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo get_term_link($term); ?>" />
		<meta property="og:site_name" content="<?php echo bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php echo $term->description; ?>" />
		<meta property="og:updated_time" content="<?php echo date('Y-m-d'); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:locale:alternate" content="vi_VN" />
		<!-- Google+ -->
		<meta name="description" content="<?php echo $term->description; ?>" />
		<meta itemprop="name" content="<?php echo $term->name; ?>">
		<meta itemprop="description" content="<?php echo $term->description; ?>">
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
		      "@id": "<?php echo $the_home; ?>",
		      "name": "Home"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 2,
		    "item": {
		      "@id": "<?php echo $link_taxs; ?>",
		      "name": "<?php echo $tax_display_name; ?>"
		    }
		  },{
		    "@type": "ListItem",
		    "position": 3,
		    "item": {
		      "@id": "<?php echo get_term_link($term); ?>",
		      "name": "<?php echo $term->name; ?>"
		    }
		  }]
		}
		</script>
		<!-- End: Structured Data Google -->
  	<?php
  	}//---> END: If
}