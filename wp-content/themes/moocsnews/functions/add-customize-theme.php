<?php



/*Customizer Code HERE*/
add_action('customize_register', 'theme_footer_customizer');
add_action('customize_register', 'theme_head_customizer');
add_action('customize_register', 'theme_body_head_customizer');

function theme_footer_customizer($wp_customize){
 	//adding section in wordpress customizer   
	$wp_customize->add_section('footer_settings_section', array(
  		'title'          => 'Footer About Us Section'
 	));
	//adding setting for footer text area
	$wp_customize->add_setting('footer_about_us', array(
 		'default'        => 'Default Text For Footer About Us Section',
 	));
	$wp_customize->add_control('footer_about_us', array(
	 	'label'   => 'Footer About Us Text Here',
	  	'section' => 'footer_settings_section',
	 	'type'    => 'textarea',
	));
}

function theme_head_customizer($wp_customize){
 	//adding section in wordpress customizer   
	$wp_customize->add_section('head_settings_section', array(
  		'title'          => 'Head Scripts Section'
 	));
	//adding setting for footer text area
	$wp_customize->add_setting('head_script', array(
 		'default'        => '',
 	));
	$wp_customize->add_control('head_script', array(
	 	'label'   => 'Head Scripts',
	  	'section' => 'head_settings_section',
	 	'type'    => 'textarea',
	));
}

function theme_body_head_customizer($wp_customize){
 	//adding section in wordpress customizer   
	$wp_customize->add_section('body_head_settings_section', array(
  		'title'          => 'Body Head Scripts Section'
 	));
	//adding setting for footer text area
	$wp_customize->add_setting('body_head_script', array(
 		'default'        => '',
 	));
	$wp_customize->add_control('body_head_script', array(
	 	'label'   => 'Body Head Scripts',
	  	'section' => 'body_head_settings_section',
	 	'type'    => 'textarea',
	));
}