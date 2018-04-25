<?php 

require_once MOOCSNEWS_THEME_LIB_DIR . '/course_edx.php';
require_once MOOCSNEWS_THEME_LIB_DIR . '/course_coursera.php';
require_once MOOCSNEWS_THEME_LIB_DIR . '/community_db.php';

/*============== Add ECEP Clean Course Hook To WP Admin ===================*/
add_action('admin_post_itvndocorg_clean_courses_hook', 'itvndocorg_clean_courses_hook_callback');

// Function controll post action clean course
function itvndocorg_clean_courses_hook_callback(){
	global $wpdb;
	$course_number = ( isset ($_POST['course_number']) ) ? $_POST['course_number'] : null;
	$source = ( isset ($_POST['source']) ) ? $_POST['source'] : null;
	$deleted_courses = 0;
	$sql_string = "select $wpdb->posts.ID, $wpdb->posts.post_title from $wpdb->posts";

	// Join with postmeta table
	if($source != null){
		$sql_string .= " LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID";
	}

	// Where condition
	$sql_string .= " where post_type = 'course'";
	
	if($source != null){
		$sql_string .= " AND meta_value like '%\"".$source."\"%'";
	}
	
	if($course_number != null){
		$sql_string .= " limit ".$course_number;
	}
	
	$rows = $wpdb->get_results($sql_string);

	echo '<ul>List deleted courses:';
	foreach ($rows as $obj){
		echo '<li>'.$obj->post_title.'</li>';
		wp_delete_post( $obj->ID, true);
		$deleted_courses++;
	}
	echo '</ul></br>';
	echo '<h5>Deleted '.$deleted_courses.'</h5>';
	echo '<a href="'.get_site_url().'/wp-admin/edit.php?post_type=course">Deleted Courses Successful, Click here to redirect to courses admin page !!!</a>';
}

/*============== Add ECEP Insert Course Hook To WP Admin ===================*/
add_action('admin_post_itvndocorg_insert_courses_hook', 'itvndocorg_insert_courses_hook_callback');

// Function controll post action insert course
function itvndocorg_insert_courses_hook_callback(){
	global $wpdb;

	$course_status = ( isset ($_POST['course_status']) ) ? $_POST['course_status'] : 'draft';
	$source = ( isset ($_POST['source']) ) ? $_POST['source'] : '';

	if( isset ($_POST['connection_host']) && $_POST['connection_host'] != '' ){
		$connection_host = $_POST['connection_host'];
	}else{
		show_alert_and_redirect('You have to input connection host field for connect to staging database');
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
			show_alert_and_redirect('Your connection host is not right structure. Please input right connection host structure like example.');
		}
	}
	
	if($database_infos['type'] == 'mysql'){
		$community_db = new wpdb($database_infos['user'], $database_infos['password'], $database_infos['name'], $database_infos['host']);
	}else{
		show_alert_and_redirect('At this time connection host is only work with mysql database. Please input mysql database for connection host');
	}

	if($source == ''){
		show_alert_and_redirect('Please Select Source to Import');
	}
	
	$rows = $community_db->get_results("select * from ".$source."_staging where `expired_at` = '9999-12-31 00:00:00'");
	$table_prefix = $wpdb->prefix;
	
	
	$insertSuccess = [];
	$insertFail = [];
	$insertExisted = [];
	$updated = [];
	$insertSuccess = [];
	$totalCourses = count($rows);
	$totalInserted= 0;
	
	foreach ($rows as $obj) {
		// Get Start date from $source_startdate table
		$start_date = $community_db->get_results("select * from ".$source."_startdate where `course_id` = ".$obj->id." order by `effectived_at` desc limit 1");

		if($source == 'edx'){
			$course = new Course_edX();
		}else{
			$course = new Course_Coursera();
		}
		$course->set_course_status($course_status);
		$course->set_community_db($community_db);
		$course->set_tmp_data($obj);
		$course->set_tmp_start_data($start_date[0]);
		$course->prepare_data_for_insert_update();
		$course->prepare_specs();
		$is_existed = $course->get_course_by_url($obj->intro_course_url);
		if(empty($is_existed)){
			echo "Starting insert course '".$obj->course_title."' ...</br>";
			$course_id = $course->insert_course();
			if ($course_id){

				echo "Insert Course successful</br>";

				$course->add_relations_and_metadata($course_id);

				echo  "Insert Course '".$obj->course_title."' successful !!!</br></br>";
				
				$totalInserted +=1;
				$insertSuccess[] = $obj->course_title;
			}else{
				$insertFail[] = $obj->course_title;
			}
		}else{
			$course_id = $is_existed[0]->post_id;
			$cur_course = get_post($course_id);
			echo "Course '".$obj->course_title."' existed !!!</br>";
			echo "Check on course startdate has new update ?</br>";
			$course->update_start_date($course_id);
			echo "Check on course staging has new update ?</br>";
			$date = new DateTime($cur_course->post_modified);
			$date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
			$is_up_to_date = $course->check_new_data_on_staging($date->format('Y-m-d H:i:sP'));
			if(!$is_up_to_date){
				echo "Have new update !!! Update now ... </br>";
				$course->update_course($course_id);
				$updated[] = $obj->course_title;
			}else{
				echo "This course up to date </br></br>";
				$insertExisted[] = $obj->course_title;
			}

		}
	}
	echo 'Insert Success '.$totalInserted.'/'.$totalCourses.'</br>';
	echo '<ul>List of inserted courses';
	foreach($insertSuccess as $key => $value){
		echo '<li>'.$value.'</li>';
	}
	echo '</ul></br>';
	echo '<ul>List of courses insert failed';
	foreach($insertFail as $key => $value){
		echo '<li>'.$value.'</li>';
	}
	echo '</ul></br>';
	echo '<ul>List of courses existed';
	foreach($insertExisted as $key => $value){
		echo '<li>'.$value.'</li>';
	}
	echo '</ul></br>';
	echo '<ul>List of courses updated';
	foreach($updated as $key => $value){
		echo '<li>'.$value.'</li>';
	}
	echo '</ul></br>';
	echo '<a href="'.get_site_url().'/wp-admin/edit.php?post_type=course">All Courses Inserted, Click here to redirect to courses admin page !!!</a>';
	
}