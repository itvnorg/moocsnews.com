<?php 

/*============== Add itvn.org Insert Course Page To Admin ===================*/
add_action( 'admin_menu', 'itvndocorg_admin_menu' );

// Function to add page to admin menu
function itvndocorg_admin_menu(){
	add_menu_page( 
		'itvn.org Manager', 
		'itvn.org', 
		'manage_options', 
		'itvndocorg', 
		'itvndocorg_index_page', 
		'dashicons-paperclip', 
		50  
	);
	
	add_submenu_page(
		'itvndocorg', 
		'itvn.org Ajax Upload Courses', 
		'Ajax Upload Courses', 
		'manage_options', 
		'itvndocorg/ajax-upload-course-admin-page', 
		'ajax_upload_course_admin_page'
	);
	
	add_submenu_page(
		'itvndocorg', 
		'itvn.org Clean Tags', 
		'Clean Tags', 
		'manage_options', 
		'itvndocorg/clean-tag-admin-page', 
		'clean_tag_admin_page'
	);
	
	add_submenu_page(
		'itvndocorg', 
		'itvn.org Clean Courses', 
		'Clean Courses', 
		'manage_options', 
		'itvndocorg/clean-course-admin-page', 
		'clean_course_admin_page'
	);
}

// Function to manage content of page itvn.org index
function itvndocorg_index_page(){
	?>
	<div class="wrap">
		<h2>Welcome To itvn.org tools</h2>
	</div>
	<?php 
}

// Function to manage content of page clean course
function clean_course_admin_page(){
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Clean Courses</h1>
		<form action="<?php get_site_url(); ?>/wp-admin/admin-post.php" method="POST">
			<input type="hidden" name="action" value="itvndocorg_clean_courses_hook">
			<input type="hidden" name="custom_nonce" value="<?php echo $custom_form_nonce; ?>">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content" style="position: relative;">
						<p>
							<label for="course_number">Number Courses 
								<span style="font-size: 12px; color: red;"> (Blank value is all courses)</span>
							</label>
							<br>
							<input type="text" name="course_number" id="course-number" />
						</p>

						<p>
							<label for="source">Source</label>
							<br>
							<select name="source" id="source">
									<option value="" selected="selected">All Source of Courses</option>
									<option value="coursera">Coursera</option>
									<option value="edx">edX</option>
							</select>
						</p>
					
						<input type="submit" name="submit" class="button button-primary button-large">
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
}

// Function to manage content of page ajax upload course
function ajax_upload_course_admin_page(){
	?>
	<style type="text/css">
		body{
			 background-color: #f1f1f1;
		}

		.progress-status{
			margin-right: 30px;
		}

		#info_upload, #current_item{
			margin-bottom: 10px;
		}
	</style>

	<div class="wrap">
		<h1 class="wp-heading-inline">Ajax Upload Courses</h1>
		<form action="javascript:;" onsubmit="return get_upload_courses();" method="POST">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content" style="position: relative;">
						<p>
							<label for="course_status">Course Status</label>
							<br>
							<select name="course_status" id="course_status">
									<option value="draft" selected="selected">Draft</option>
									<option value="publish">Publish</option>
							</select>
						</p>

						<p>
							<label for="connection_host">Connection Host</label>
							<br>
							<input type="text" name="connection_host" id="connection_host" placeholder="mysql://user:password@host.com:port/database_name" style="width:400px;">
						</p>

						<p>
							<label for="source">Source</label>
							<br>
							<select name="source" id="source">
									<option value="" selected="selected">Select Source of Courses</option>
									<option value="coursera">Coursera</option>
									<option value="edx">edX</option>
							</select>
						</p>

						<p>
							<div class="radio">
								<label><input type="radio" name="is_upload_tags" value="1" checked>Upload Tags</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="is_upload_tags" value="0">Don't Upload Tags</label>
							</div>
						</p>
					
						<button type="submit" name="submit" class="button button-primary button-large" id="btn_prepare_upload_courses">Prepare courses to upload</button>
						<a href="javascript:;" id="btn_upload_courses" class="button button-primary button-large" onclick="upload_courses();" style="display: none;">Upload course</a>
						<a href="javascript:;" id="btn_prepare_upload_tags" class="button button-primary button-large" onclick="get_upload_tags();">Prepare tags to upload</a>
						<a href="javascript:;" id="btn_upload_tags" class="button button-primary button-large" onclick="upload_tags();" style="display: none;">Upload tag</a>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</form>
		<div id="info_upload"></div>
		<div id="current_item"></div>
		<div id="progress"></div>
		<div class="current-item" id="current-item"></div>
	</div>
	
	<?php

	add_action('admin_footer', 'itvndocorg_moocsnews_admin_ajax_upload_courses_include_js_code');

}

function itvndocorg_moocsnews_admin_ajax_upload_courses_include_js_code(){
	?>
		<script type="text/javascript">
			var $ = jQuery;
			var urlSite = window.location.origin;
			var urlUploadCourses = urlSite + "/wp-json/itvndocorg/v1/courses/upload";
			var urlGetUploadCourses = urlSite + "/wp-json/itvndocorg/v1/courses/get-upload-list";
			var urlUploadTags = urlSite + "/wp-json/itvndocorg/v1/tags/upload";
			var urlGetUploadTags = urlSite + "/wp-json/itvndocorg/v1/tags/get-upload-list";
			var listUploadCourses = [];
			var listUploadTags = [];
			var source;
			var course_status;
			var connection_host;
			var is_upload_tags;
			var current_item = 0;
			var total_item;
			var updated_item = 0;
			var inserted_item = 0;
			var existed_item = 0;
			var failed_item = 0;

			function get_upload_courses(){
				$('#btn_prepare_upload_courses').attr('disabled','disabled');
				update_params();

				var params = {
					source: source,
					course_status: course_status,
					connection_host: connection_host,
				};

				$.get(urlGetUploadCourses, params).done(function(data){
					if(data.status == 'success'){
						total_item = data.totalCourses;
						listUploadCourses = data.update_courses;
						$('#btn_upload_courses').show();
						display_progess();
					}else{
						alert(data.message);
					}
				});
			}

			function upload_courses(){
				$('#btn_upload_courses').attr('disabled','disabled');
				load_ajax(current_item);
			}

			function load_ajax(index){
			  	if (index < total_item) {
			  		display_current_item(listUploadCourses[index]['course_title']);
				  	var params_upload = {
						source: source,
						course_status: course_status,
						connection_host: connection_host,
				  		id: listUploadCourses[index]['id'],
				  	};
				  	$.ajax({
				  		url: urlUploadCourses,
				  		type: "GET",
				  		data: params_upload,
				  		success: function(data){
							if(data.status == 'error'){
								$.each(data.messages, function( indexMsg, valueMsg ){
					  				remove_progress_animation();
								});
							}else{
								switch(data.status) {
								    case 'inserted':
								        inserted_item++;
								        break;
								    case 'updated':
								        updated_item++;
								        break;
								    case 'existed':
								        existed_item++;
								        break;
								    case 'failed':
								        failed_item++;
								    default:
								        break;
								}
								current_item = index + 1;
								display_progess();
								load_ajax(index + 1);
							}
					  	},
					  	error: function(data){
					  		remove_progress_animation();
					  		alert('Have error when upload courses');
					  	}
				  	});
			  	}else{
			  		display_current_item('All Courses Uploaded');
			  		display_progess();
			  		remove_progress_animation();
			  	}
			}

			function get_upload_tags(){
				$('#btn_prepare_upload_tags').attr('disabled','disabled');
				update_params();

				var params = {
					source: source,
					course_status: course_status,
					connection_host: connection_host,
				};

				$.get(urlGetUploadTags, params).done(function(data){
					if(data.status == 'success'){
						total_item = data.totalCourses;
						listUploadTags = data.update_courses;
						$('#btn_upload_tags').show();
						display_progess();
					}else{
						alert(data.message);
					}
				});
			}

			function upload_tags(){
				$('#btn_upload_tags').attr('disabled','disabled');
				load_ajax_upload_tag(current_item);
			}

			function load_ajax_upload_tag(index){
				if (index < total_item) {
					display_current_item(listUploadTags[index]['intro_course_url']);
					var params_upload = {
						source: source,
						course_status: course_status,
						connection_host: connection_host,
						id: listUploadTags[index]['id'],
					};
					$.ajax({
						url: urlUploadTags,
						type: "GET",
						data: params_upload,
						success: function(data){
							if(data.status == 'error'){
								$.each(data.messages, function( indexMsg, valueMsg ){
									remove_progress_animation();
								});
							}else{
								switch(data.status) {
									case 'inserted':
									inserted_item++;
									break;
									case 'updated':
									updated_item++;
									break;
									case 'existed':
									existed_item++;
									break;
									case 'failed':
									failed_item++;
									default:
									break;
								}
								current_item = index + 1;
								display_progess();
								load_ajax_upload_tag(index + 1);
							}
						},
						error: function(data){
							remove_progress_animation();
							alert('Have error when upload tags');
						}
					});
				}else{
					display_current_item('All Tags Uploaded');
					display_progess();
					remove_progress_animation();
				}
			}

			function remove_progress_animation(){
				$('.progress-bar-striped').removeClass('progress-bar-animated');
			}

			function update_params(){
				source = $('#source').val();
				course_status = $('#course_status').val();
				connection_host = $('#connection_host').val();
				is_upload_tags = $('input[name="is_upload_tags"]:checked').val();
			}

			function display_current_item(name){
				$('#current_item').html('<span class="current_item">Running Course: '+name+'</span>');
			}

			function display_progess(){
				$('#info_upload').html('<span class="progress-status">Total: '+current_item+'/'+total_item+'</span>'+
					'<span class="progress-status">Inserted: '+inserted_item+'/'+current_item+'</span>'+
					'<span class="progress-status">Updated: '+updated_item+'/'+current_item+'</span>'+
					'<span class="progress-status">Existed: '+existed_item+'/'+current_item+'</span>'+
					'<span class="progress-status">Failed: '+failed_item+'/'+current_item+'</span>'
					);
				$('#progress').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="'+current_item+'" aria-valuemin="0" aria-valuemax="'+total_item+'" style="width: '+(current_item/total_item)*100+'%;"></div></div>');
			}
		</script>
	<?php
}

add_action('admin_enqueue_scripts', 'itvndocorg_add_admin_bootstrap');

function itvndocorg_add_admin_bootstrap(){
	$assetsUrl = MOOCSNEWS_THEME_URL . '/assets';
	wp_register_style('moocsnews_theme_bootstrap', $assetsUrl . '/plugins/bootstrap/bootstrap.min.css', array(), '4.0.0');
	wp_register_script('moocsnews_theme_bootstrap', $assetsUrl . '/plugins/bootstrap/bootstrap.min.js', array(), '4.0.0');

	$current_screen = get_current_screen();

    if ( strpos($current_screen->base, 'itvndocorg') === false) {
        return;
    } else {
		wp_enqueue_script(['moocsnews_theme_bootstrap']);
		wp_enqueue_style(['moocsnews_theme_bootstrap']);
    }
	
}

// Function to manage content of page clean course
function clean_tag_admin_page(){
	?>
	<div class="wrap">
		<div class="clearfix">
			<h1 class="wp-heading-inline">Clean Tags</h1>
			<form action="<?php get_site_url; ?>/wp-admin/admin-post.php" method="POST">
				<input type="hidden" name="action" value="itvndocorg_clean_tags_hook">
				<input type="hidden" name="custom_nonce" value="<?php echo $custom_form_nonce; ?>">
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content" style="position: relative;">
							<input type="submit" name="submit" class="button button-primary button-large">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="clearfix">
			<h1 class="wp-heading-inline">Clean Instructors</h1>
			<form action="<?php get_site_url; ?>/wp-admin/admin-post.php" method="POST">
				<input type="hidden" name="action" value="itvndocorg_clean_instructors_hook">
				<input type="hidden" name="custom_nonce" value="<?php echo $custom_form_nonce; ?>">
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content" style="position: relative;">
							<input type="submit" name="submit" class="button button-primary button-large">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
}