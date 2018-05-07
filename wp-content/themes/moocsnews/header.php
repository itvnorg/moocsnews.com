<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo('charset'); ?>" />

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="apple-touch-icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>
    <?php wp_title('|',true,'right');
        bloginfo('name');
    ?>
</title>
<?php wp_head(); ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php echo get_theme_mod('head_script'); ?>
</head>
<body>
    <?php echo get_theme_mod('body_head_script'); ?>
	<header>
    	<?php require_once MOOCSNEWS_THEME_INC_DIR . '/header-menu.php'; ?>
	</header>
