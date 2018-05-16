<?php

/*============== Add Theme support nav menus ===================*/
add_theme_support('nav-menus');

register_nav_menus(array(
    'header-menu' => __('Header Menu', 'moocsnews'),
    'footer-menu-first' => __('Footer Menu First', 'moocsnews'),
    'footer-menu-second' => __('Footer Menu Second', 'moocsnews'),
));

function atg_menu_classes($classes, $item, $args) {
  	if($args->theme_location == 'header-menu') {
    	$classes[] = 'nav-item';
  	}
    if($args->theme_location == 'footer-menu-first' || $args->theme_location == 'footer-menu-second') {
      $classes[] = 'list-horizontal-item';
    }
  	return $classes;
}
add_filter('nav_menu_css_class', 'atg_menu_classes', 1, 3);

function add_menuclass($ulclass) {
  if (strpos($ulclass, 'header-menu') !== false) {
    $ulclass = preg_replace('/<a /', '<a class="nav-link"', $ulclass);
  }

  if (strpos($ulclass, 'footer-menu') !== false) {
    $ulclass = preg_replace('/<a /', '<a class="text-blue text-link"', $ulclass);
  }
  return $ulclass;
}
add_filter('wp_nav_menu','add_menuclass');

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
 	if( in_array('current-menu-item', $classes) ){
     	$classes[] = 'active ';
 	}
 	return $classes;
}

/*==============	Support Featured Images	======================*/
/* Enable Feature Image */
add_theme_support( 'post-thumbnails' );

/*============== 	Custom Logo	======================*/
add_theme_support( 'custom-logo' );

function itvndocorg_custom_logo_setup() {
    $defaults = array(
        'height'      => 50,
        'width'       => 220,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'itvndocorg_custom_logo_setup' );