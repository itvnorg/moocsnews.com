<?php 

/*==============	Define Const Vars	======================*/
define('MOOCSNEWS_THEME_URL', get_template_directory_uri());

define('MOOCSNEWS_THEME_DIR', get_template_directory());
define('MOOCSNEWS_THEME_INC_DIR', MOOCSNEWS_THEME_DIR . '/inc');
// define('MOOCSNEWS_THEME_WIDGETS_DIR', MOOCSNEWS_THEME_INC_DIR . '/widgets');
define('MOOCSNEWS_THEME_FUNC_DIR', MOOCSNEWS_THEME_DIR . '/functions');
define('MOOCSNEWS_THEME_LIB_DIR', MOOCSNEWS_THEME_DIR . '/libs');

require_once MOOCSNEWS_THEME_FUNC_DIR . '/helpers.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-theme-supports.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-object-to-sidebar-admin.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-course-custom-post-type.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/manage-course-views.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-custom-fields-to-page.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-custom-fields-to-taxonomy.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-custom-taxonomies.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/insert-update-delete-data.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/api.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/load-css-js-front-end.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/metadata-structured-data-google.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/add-customize-theme.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/create-site-maps.php';
require_once MOOCSNEWS_THEME_FUNC_DIR . '/retrieve_datas.php';

global $the_home;
if(empty($the_home)){
	$the_home = get_home_url();
}