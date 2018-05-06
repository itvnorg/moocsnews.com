<?php

/*============== Add Theme support nav menus ===================*/
add_theme_support('nav-menus');

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