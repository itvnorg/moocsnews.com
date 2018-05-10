<?php

/*==============	itvn.org Include JS to WP	======================*/
add_action('wp_enqueue_scripts', 'itvndocorg_moocsnews_include_js');

function itvndocorg_moocsnews_include_js(){
	$assetsUrl = MOOCSNEWS_THEME_URL . '/assets';
	
	wp_register_script('moocsnews_theme_bootstrap', $assetsUrl . '/plugins/bootstrap/bootstrap.min.js', array(), '4.0.0');
	wp_register_script('moocsnews_theme_datatable', $assetsUrl . '/plugins/datatable/datatables.min.js', array(), '1.10.16');
	
	wp_enqueue_script([
		'moocsnews_theme_bootstrap',
	]);

	if(is_front_page()){
		// wp_enqueue_script(['moocsnews_theme_home',]);
	}

	if(is_single()){
		// wp_enqueue_script(['moocsnews_theme_course_detail',]);
	}
	
	if( is_page_template('page-catalog.php') || is_category() || is_tag() ){
		// wp_enqueue_script(['moocsnews_theme_courses',]);
	}

	if( is_taxonomy('subject') ){
		wp_enqueue_script(['moocsnews_theme_datatable',]);
	}
}

/*==============	itvn.org Include CSS to WP	======================*/
add_action('wp_enqueue_scripts', 'itvndocorg_moocsnews_include_css');

function itvndocorg_moocsnews_include_css(){
	$assetsUrl = MOOCSNEWS_THEME_URL . '/assets';

	wp_register_style('moocsnews_theme_bootstrap', $assetsUrl . '/plugins/bootstrap/bootstrap.min.css', array(), '4.0.0');
	wp_register_style('moocsnews_theme_fontawesome', $assetsUrl . '/plugins/fontawesome/css/fontawesome-all.css', array(), '5.0.11');
	wp_register_style('moocsnews_theme_datatable', $assetsUrl . '/plugins/datatable/datatables.min.css', array(), '1.10.16');

	wp_enqueue_style([
		'moocsnews_theme_bootstrap',
		'moocsnews_theme_fontawesome',
	]);

	if(is_front_page()){
		// wp_enqueue_style(['moocsnews_theme_home',]);
	}

	if(is_single()){
		// wp_enqueue_style(['moocsnews_theme_course_detail',]);
	}
	
	if( is_page_template('page-catalog.php') || is_category() || is_tag() ){
		// wp_enqueue_style(['moocsnews_theme_courses',]);
	}

	if( is_taxonomy('subject') ){
		wp_enqueue_style(['moocsnews_theme_datatable',]);
	}
}
