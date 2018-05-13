<?php

/*============== Register Insert/Update Courses API ===================*/
add_action( 'rest_api_init', 'itvndocorg_api_register_upload_course_route' );

function itvndocorg_api_register_upload_course_route(){
	register_rest_route( 'itvndocorg/v1', '/courses/upload', array(
		'methods' => WP_REST_Server::READABLE,
		'callback'	=>	'itvndocorg_api_response_upload_courses',
	) );
}

function itvndocorg_api_response_upload_courses($request){
	global $wpdb;
	$course_status = (!empty($request['course_status'])) ? (string) $request['course_status'] : 'draft';
	$source = (!empty($request['source'])) ? (string) $request['source'] : null;
	$connection_host = (!empty($request['connection_host'])) ? (string) $request['connection_host'] : null;
	$id = (!empty($request['id'])) ? (string) $request['id'] : null;

	if( is_null($id) ){
		return return_ajax_error('id course is invalid !!!');
	}

	$database_infos = [];

	$tmp_arr = explode('@', $connection_host);
	$tmp_arr_type_user_pwd = explode(':', $tmp_arr[0]);
	$tmp_arr_user = explode('//', $tmp_arr_type_user_pwd[1]);
	$tmp_arr_host_db_name = explode('/', $tmp_arr[1]);
	$database_infos['type'] = $tmp_arr_type_user_pwd[0];
	$database_infos['user'] = $tmp_arr_user[1];
	$database_infos['password'] = $tmp_arr_type_user_pwd[2];
	$database_infos['host'] = $tmp_arr_host_db_name[0];
	$database_infos['name'] = $tmp_arr_host_db_name[1];

	$community_db = new wpdb($database_infos['user'], $database_infos['password'], $database_infos['name'], $database_infos['host']);
	
	$messages = [];
	$data = [];
	$data['status'] = 'error';

	$tmp_course = $community_db->get_results("select * from ".$source."_staging where `id` = ".$id);
	$data['tmp_course'] = $tmp_course[0];

	$start_date = $community_db->get_results("select * from ".$source."_startdate where `course_id` = ".$tmp_course[0]->id." order by `effectived_at` desc limit 1");
	$data['start_date'] = $start_date[0];

	$tag = $community_db->get_results("select * from ".$source."_tags where `intro_course_url` = \"".$tmp_course[0]->intro_course_url."\" limit 1");
	$data['tag'] = $tag[0];

	if($source == 'edx'){
		$course = new Course_edX();
	}else{
		$course = new Course_Coursera();
	}
	$course->set_course_status($course_status);
	$course->set_community_db($community_db);
	$course->set_tmp_data($tmp_course[0]);
	$course->set_tmp_start_date($start_date[0]);
	$course->set_tmp_tags_data($tag[0]);
	$course->prepare_data_for_insert_update();
	$course->prepare_specs();
	$is_existed = $course->get_course_by_url($tmp_course[0]->intro_course_url);
	if(empty($is_existed)){
		$messages[] = "Starting insert course '".$tmp_course[0]->course_title."' ...";
		// echo "Starting insert course '".$tmp_course[0]->course_title."' ...</br>";
		$course_id = $course->insert_course();
		if ($course_id){

			$messages[] = "Insert Course successful";
			// echo "Insert Course successful</br>";

			$course->add_relations_and_metadata($course_id);

			$messages[] = "Insert Course '".$tmp_course[0]->course_title."' successful !!!";
			// echo  "Insert Course '".$tmp_course[0]->course_title."' successful !!!</br></br>";
			
			$data['status'] = 'inserted';
		}else{
			$data['status'] = 'failed';
		}
	}else{
		$course_id = $is_existed[0]->post_id;
		$cur_course = get_post($course_id);
		$messages[] = "Course '".$tmp_course[0]->course_title."' existed !!!";
		// echo "Course '".$tmp_course[0]->course_title."' existed !!!</br>";
		$messages[] = "Check on course startdate has new update ?";
		// echo "Check on course startdate has new update ?</br>";
		$course->update_start_date($course_id);
		$messages[] = "Check on course staging has new update ?";
		// echo "Check on course staging has new update ?</br>";
		$date = new DateTime($cur_course->post_modified);
		$date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
		$is_up_to_date = $course->check_new_data_on_staging($date->format('Y-m-d H:i:sP'));
		if(!$is_up_to_date){
			$messages[] = "Have new update !!! Update now ... ";
			// echo "Have new update !!! Update now ... </br>";
			$course->update_course($course_id);
			$data['status'] = 'updated';
		}else{
			$messages[] = "This course up to date ";
			// echo "This course up to date </br></br>";
			$data['status'] = 'existed';
		}

	}	
	$data['messages'] = array_merge ( $course->result , $messages  );
	
	return rest_ensure_response( $data );
}

/*============== Register Get List Uploading Courses API ===================*/
add_action( 'rest_api_init', 'itvndocorg_api_register_get_list_uploading_course_route' );

function itvndocorg_api_register_get_list_uploading_course_route(){
	register_rest_route( 'itvndocorg/v1', '/courses/get-upload-list', array(
		'methods' => WP_REST_Server::READABLE,
		'callback'	=>	'itvndocorg_api_response_get_list_upload_courses',
	) );
}

function itvndocorg_api_response_get_list_upload_courses($request){
	global $wpdb;
	$course_status = (!empty($request['course_status'])) ? (string) $request['course_status'] : 'draft';
	$source = (!empty($request['source'])) ? (string) $request['source'] : null;
	$connection_host = (!empty($request['connection_host'])) ? (string) $request['connection_host'] : null;

	if( is_null($connection_host) ){
		return return_ajax_error('You have to input connection host field for connect to staging database');
	}

	// Explode string connection host to get info for connect staging database
	// mysql://user:password@host.com:port/database_name
	$database_infos = [];

	$tmp_arr = explode('@', $connection_host);
	$tmp_arr_type_user_pwd = explode(':', $tmp_arr[0]);
	$tmp_arr_user = explode('//', $tmp_arr_type_user_pwd[1]);
	$tmp_arr_host_db_name = explode('/', $tmp_arr[1]);
	$database_infos['type'] = $tmp_arr_type_user_pwd[0];
	$database_infos['user'] = $tmp_arr_user[1];
	$database_infos['password'] = $tmp_arr_type_user_pwd[2];
	$database_infos['host'] = $tmp_arr_host_db_name[0];
	$database_infos['name'] = $tmp_arr_host_db_name[1];


	foreach ($database_infos as $key => $value) {
		if($value == ''){
			return return_ajax_error('Your connection host is not right structure. Please input right connection host structure like example.');
		}
	}
	
	if($database_infos['type'] != 'mysql'){
		return return_ajax_error('At this time connection host is only work with mysql database. Please input mysql database for connection host');
	}

	if( is_null($source) ){
		return return_ajax_error('Please Select Source to Import');
	}

	$community_db = new wpdb($database_infos['user'], $database_infos['password'], $database_infos['name'], $database_infos['host']);

	$rows = $community_db->get_results("select id, course_title from ".$source."_staging where `expired_at` = '9999-12-31 00:00:00' and `intro_course_url` != \"\" and `course_title` != \"\"");
	$totalCourses = count($rows);

	$data = [
		'status' 		=> 'success',
		'totalCourses'		=>	$totalCourses,
		'update_courses'	=> $rows,
	];	
	
	return rest_ensure_response( $data );
}

/*============== Register Get List Uploading Tags API ===================*/
add_action( 'rest_api_init', 'itvndocorg_api_register_get_list_uploading_tag_route' );

function itvndocorg_api_register_get_list_uploading_tag_route(){
	register_rest_route( 'itvndocorg/v1', '/tags/get-upload-list', array(
		'methods' => WP_REST_Server::READABLE,
		'callback'	=>	'itvndocorg_api_response_get_list_upload_tags',
	) );
}

function itvndocorg_api_response_get_list_upload_tags($request){
	global $wpdb;
	$course_status = (!empty($request['course_status'])) ? (string) $request['course_status'] : 'draft';
	$source = (!empty($request['source'])) ? (string) $request['source'] : null;
	$connection_host = (!empty($request['connection_host'])) ? (string) $request['connection_host'] : null;

	if( is_null($connection_host) ){
		return return_ajax_error('You have to input connection host field for connect to staging database');
	}

	// Explode string connection host to get info for connect staging database
	// mysql://user:password@host.com:port/database_name
	$database_infos = [];

	$tmp_arr = explode('@', $connection_host);
	$tmp_arr_type_user_pwd = explode(':', $tmp_arr[0]);
	$tmp_arr_user = explode('//', $tmp_arr_type_user_pwd[1]);
	$tmp_arr_host_db_name = explode('/', $tmp_arr[1]);
	$database_infos['type'] = $tmp_arr_type_user_pwd[0];
	$database_infos['user'] = $tmp_arr_user[1];
	$database_infos['password'] = $tmp_arr_type_user_pwd[2];
	$database_infos['host'] = $tmp_arr_host_db_name[0];
	$database_infos['name'] = $tmp_arr_host_db_name[1];


	foreach ($database_infos as $key => $value) {
		if($value == ''){
			return return_ajax_error('Your connection host is not right structure. Please input right connection host structure like example.');
		}
	}
	
	if($database_infos['type'] != 'mysql'){
		return return_ajax_error('At this time connection host is only work with mysql database. Please input mysql database for connection host');
	}

	if( is_null($source) ){
		return return_ajax_error('Please Select Source to Import');
	}

	$community_db = new wpdb($database_infos['user'], $database_infos['password'], $database_infos['name'], $database_infos['host']);

	$rows = $community_db->get_results("select id, intro_course_url from ".$source."_tags where `intro_course_url` != \"\" order by id asc");
	$totalCourses = count($rows);

	$data = [
		'status' 		=> 'success',
		'totalCourses'		=>	$totalCourses,
		'update_courses'	=> $rows,
	];	
	
	return rest_ensure_response( $data );
}

/*============== Register Insert/Update Courses API ===================*/
add_action( 'rest_api_init', 'itvndocorg_api_register_upload_tag_route' );

function itvndocorg_api_register_upload_tag_route(){
	register_rest_route( 'itvndocorg/v1', '/tags/upload', array(
		'methods' => WP_REST_Server::READABLE,
		'callback'	=>	'itvndocorg_api_response_upload_tags',
	) );
}

function itvndocorg_api_response_upload_tags($request){
	global $wpdb;
	$course_status = (!empty($request['course_status'])) ? (string) $request['course_status'] : 'draft';
	$source = (!empty($request['source'])) ? (string) $request['source'] : null;
	$connection_host = (!empty($request['connection_host'])) ? (string) $request['connection_host'] : null;
	$id = (!empty($request['id'])) ? (string) $request['id'] : null;

	if( is_null($id) ){
		return return_ajax_error('id course is invalid !!!');
	}

	$database_infos = [];

	$tmp_arr = explode('@', $connection_host);
	$tmp_arr_type_user_pwd = explode(':', $tmp_arr[0]);
	$tmp_arr_user = explode('//', $tmp_arr_type_user_pwd[1]);
	$tmp_arr_host_db_name = explode('/', $tmp_arr[1]);
	$database_infos['type'] = $tmp_arr_type_user_pwd[0];
	$database_infos['user'] = $tmp_arr_user[1];
	$database_infos['password'] = $tmp_arr_type_user_pwd[2];
	$database_infos['host'] = $tmp_arr_host_db_name[0];
	$database_infos['name'] = $tmp_arr_host_db_name[1];

	$community_db = new wpdb($database_infos['user'], $database_infos['password'], $database_infos['name'], $database_infos['host']);
	
	$messages = [];
	$data = [];
	$data['status'] = 'error';

	$tag = $community_db->get_results("select * from ".$source."_tags where `id` = \"".$id."\"");
	$data['tag'] = $tag[0];

	if($source == 'edx'){
		$course = new Course_edX();
	}else{
		$course = new Course_Coursera();
	}
	$course->set_community_db($community_db);
	$course->set_tmp_tags_data($tag[0]);
	$course->prepare_tags($course->tmp_tags_data->tags);
	$is_existed = $course->get_course_by_url($tag[0]->intro_course_url);
	$data['status'] = 'failed';
	if(!empty($is_existed)){
		$course_id = $is_existed[0]->post_id;
		$messages[] = "Course '".$tag[0]->intro_course_url."' existed !!!";
		$course->add_course_to_tag($course_id);
		$data['status'] = 'updated';
	}	
	$data['messages'] = array_merge ( $course->result , $messages  );
	
	return rest_ensure_response( $data );
}