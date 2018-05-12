<?php

class Course{
	// Define vars
	public $data;
	public $data_meta;
	public $id;
	public $source_name;
	public $cats = [];
	public $subjects = [];
	public $instructors = [];
	public $institutions = [];
	public $specializations = [];
	public $tmp_data = [];
	public $tmp_start_date = [];
	public $tmp_meta_data;
	public $community_db;
	public $course_content;
	public $course_title;
	public $course_status;
	public $post_type = 'course';
	public $wpdb;
	public $result = [];

	// Construct Function
	function __construct($source_name){
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->source_name = $source_name;
	}

	// Function check have new update on start date
	public function update_start_date($course_id){
		$datameta = get_post_meta( $course_id, 'course_fields', true );
		if($datameta['start_date'] != $this->tmp_start_date->start_date){
			$datameta['start_date'] = $this->tmp_start_date->start_date;
			update_post_meta( $course_id, 'course_fields', $datameta );
			$this->result[] = "Start date updated new data";
			// echo "Start date updated new data</br>";
		}else{
			$this->result[] = "Course start date up to date";
			// echo "Course start date up to date</br>";
		}
	}

	// Function check have new update on staging
	public function check_new_data_on_staging($modified_at){
		if($modified_at < $this->tmp_data->effectived_at){
			return false;
		}
		if($modified_at < $this->tmp_tags_data->updated_at){
			return false;
		}
		return true;
	}

	// Function repare data
	public function prepare_data_for_insert_update(){
		$this->format_course_content();
		// $this->prepare_cats($this->tmp_meta_data->categories);
		$this->prepare_subjects($this->tmp_meta_data->categories);
		$this->prepare_instructors($this->tmp_meta_data->instructors);
		$this->prepare_institutions($this->tmp_meta_data->institutions);
		$this->prepare_tags($this->tmp_tags_data->tags);
		$this->prepare_course_metadata();
	}

	// Function set temp data
	public function set_tmp_data($tmp_data){
		$this->tmp_data = $tmp_data;
		$this->tmp_meta_data = json_decode($this->tmp_data->metadata);
		$this->course_title = $this->tmp_data->course_title;
	}

	// Function set temp data
	public function set_tmp_start_date($tmp_start_date){
		$this->tmp_start_date = $tmp_start_date;
	}

	// Function set temp data
	public function set_tmp_tags_data($tmp_tags_data){
		$this->tmp_tags_data = $tmp_tags_data;
	}

	// Function set temp data
	public function set_community_db($community_db){
		$this->community_db = $community_db;
	}

	// Function set course_status
	public function set_course_status($course_status){
		$this->course_status = $course_status;
	}

	// Function check course existed
	public function get_course_by_url($url){
		return $this->wpdb->get_results("SELECT * FROM `".$this->wpdb->prefix."postmeta` where meta_value like '%\"".$url."\"%'");
	}

	// Function check course up to date
	// Function to insert course
	public function insert_course(){
		// Prepare main data for course
		$data = [
			'post_type' => $this->post_type,
		   	'post_title' => $this->course_title,
		   	'post_content' => $this->course_content,
		   	'post_status' => $this->course_status,
		];
		$this->result[] = "Prepare main data for course successful";
		// echo "Prepare main data for course successful</br>";
		$this->id = wp_insert_post($data);
		return $this->id;
	}

	// Function to update course
	public function update_course($course_id){
		// Prepare main data for course
		$data = [
			'ID'	=> $course_id,
			'post_type' => $this->post_type,
		   	'post_title' => $this->course_title,
		   	'post_content' => $this->course_content,
		   	'post_status' => $this->course_status,
		];
		$this->result[] = "Prepare main data for update course successful";
		// echo "Prepare main data for update course successful</br>";
		$post_id = wp_update_post($data, true);
		if (is_wp_error($post_id)) {
			$errors = $post_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error;
			}
		}else{
			$this->add_relations_and_metadata($course_id);
		}
		$this->result[] = "Update course successful";
		// echo "Update course successful</br></br>";
	}
	// Function to delete course

	// Function prepare course metadata
	public function prepare_course_metadata(){
		$contents = json_decode($this->tmp_data->course_content);
		$expression_youtube = '/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n%]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/';
		$video_src = '';
		$video_type = '';
		preg_match($expression_youtube, $this->tmp_meta_data->video_src, $result_youtube);

		if (!empty($result_youtube)) {	// Youtube link
			$video_src = $result_youtube[0];
			$video_type = 'youtube';
		}elseif (strpos($this->tmp_meta_data->video_src, '.mp4') !== false) { // Mp4 Link
			$video_src = $this->tmp_meta_data->video_src;
			$video_type = 'mp4';
		}elseif (strpos($this->tmp_meta_data->video_src, '.webm') !== false) { // Webm Link
			$video_src = $this->tmp_meta_data->video_src;
			$video_type = 'webm';
		}elseif ($this->tmp_meta_data->video_src == '') { // No video
			# code...
		}else { // Others Link
			$video_src = $this->tmp_meta_data->video_src;
			$video_type = 'mp4';
		}
		
		// Prepare meta data for course
		$data_meta = [
			'link_intro_course' => $this->tmp_data->intro_course_url,
			'video_introduction' => $video_src,
			'video_type' => $video_type,
			'video_poster' => $this->return_value_or_null($this->tmp_meta_data,'video_poster'),
			'description' => $this->return_value_or_null($this->tmp_meta_data,'short_description'),
			'cost' => $this->return_value_or_null($this->tmp_meta_data,'course_price'),
			'session' => $this->return_value_or_null($this->tmp_start_date,'status'),
			// 'certificate' => $this->tmp_meta_data->short_description,
			'effort' => $this->get_course_effort_field_by_source($this->tmp_meta_data),
			// 'duration' => $this->tmp_meta_data->commitment,
			'about_this_course' => $this->return_value_or_null($contents,'description'),
			'syllabus' => $this->return_value_or_null($contents,'syllabus'),
			// 'source' => $this->source_name,
			'start_date' => (isset($this->tmp_start_date)) ? $this->tmp_start_date->start_date : '0000-00-00',
			'language' => $this->return_value_or_null($this->tmp_meta_data,'language'),
		];
		$this->data_meta = $data_meta;
		$this->result[] = "Prepare meta data for course successful";
		// echo "Prepare meta data for course successful</br>";
	}

	// Function to insert/update course metadata
	public function insert_update_course_meta($course_id){
		$old = get_post_meta( $course_id, 'course_fields', true );
		$new = $this->data_meta;

		if ( $new && $new !== $old ) {
			update_post_meta( $course_id, 'course_fields', $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $course_id, 'course_fields', $old );
		}
		$this->result[] = "Insert/Update course meta successful";
		// echo "Insert/Update course meta successful</br>";
	}

	// Function prepare cats of course
	public function prepare_cats($tmp_cats){
		foreach ($tmp_cats as $key => $value){
			$rows_cats = $this->community_db->get_results("select * from `categories` where `categories`.`meta` like '%".$value."%'");
			foreach ($rows_cats as $item) {
				$this->cats[] = get_cat_ID($item->name);
			}
		}
		$this->result[] = "Prepare Category ID By Name successful";
		// echo "Prepare Category ID By Name successful</br>";
	}

	// Function prepare cats of course
	public function prepare_subjects($tmp_cats){
		foreach ($tmp_cats as $key => $value){
			$rows_cats = $this->community_db->get_results("select * from `categories` where `categories`.`meta` like '%".$value."%'");
			foreach ($rows_cats as $item) {
				$this->subjects[] = $item->name;
			}
		}
		$this->result[] = "Prepare Subject ID By Name successful";
		// echo "Prepare Category ID By Name successful</br>";
	}

	// Function prepare instructors of course
	public function prepare_instructors($tmp_instructors){
		if(isset($tmp_instructors) && !empty($tmp_instructors)){
			foreach ($tmp_instructors as $key => $value) {
				$value = json_decode($value);
				$arr_url = explode('/', $value->instructor_url);
				$name = $this->source_name.'-'.$arr_url[count($arr_url)-1];
				$instructor = get_term_by('name', $name, 'instructor');
				if(empty($instructor)){
					$new_instructor = wp_insert_term(
						$name, 
						'instructor'
					);
				}
				$instructor = get_term_by('name', $name, 'instructor');
				$old = get_term_meta( $instructor->term_id, 'instructor_meta', true );
		        $new = [
		        	'instructor_name' => $value->instructor_name,
		        	'instructor_job' => $value->instructor_job,
		        	'instructor_image' => $value->instructor_image,
		        	'instructor_url' => $value->instructor_url,
		        ];

		        // Save meta data array.
		        if ( $new && $new !== $old ) {
		            update_term_meta( $instructor->term_id, 'instructor_meta', $new );
		        } elseif ( '' === $new && $old ) {
		            delete_term_meta( $instructor->term_id, 'instructor_meta', $old );
		        }
				$this->instructors[] = $name;
			}
		}
		$this->result[] = "Prepare instructors successful";
		// echo "Prepare instructors successful</br>";
	}

	// Function prepare institution of course
	public function prepare_institutions($tmp_institutions){
		if(isset($tmp_institutions) && !empty($tmp_institutions)){
			foreach ($tmp_institutions as $key => $value) {
				$value = json_decode($value);
				$institution = get_term_by('name', $value->institution_name, 'institution');
				if(empty($institution)){
					$new_institution = wp_insert_term(
						$value->institution_name, 
						'institution', 
						array(
					    	'description'=> $value->institution_description,
						)
					);
				}
				$institution = get_term_by('name', $value->institution_name, 'institution');
				$old = get_term_meta( $institution->term_id, 'institution_meta', true );
		        $new = [
		        	'institution_image' => $value->institution_image,
		        ];

		        // Save meta data array.
		        if ( $new && $new !== $old ) {
		            update_term_meta( $institution->term_id, 'institution_meta', $new );
		        } elseif ( '' === $new && $old ) {
		            delete_term_meta( $institution->term_id, 'institution_meta', $old );
		        }
				$this->institutions[] = $institution->name;
			}
		}
		$this->result[] = "Prepare institutions successful";
		// echo "Prepare institutions successful</br>";
	}

	// Function prepare specialization of course
	public function prepare_specializations($tmp_specializations){
		if (isset($tmp_specializations) && count($tmp_specializations) > 0) {
			foreach ($tmp_specializations as $key => $value) {
				$specialization = get_term_by('name', $value, 'specialization');
				if(empty($specialization)){
					$new_spec = wp_insert_term($value, 'specialization');
				}
				$specialization = get_term_by('name', $value, 'specialization');
				$this->specializations[] = $specialization->name;
			}
		}
		$this->result[] = "Prepare specializations successful";
		// echo "Prepare specializations successful</br>";
	}

	// Function prepare tags of course
	public function prepare_tags($tags){
		if(isset($tags) && !empty($tags)){
			$tags = json_decode($tags);
			foreach ($tags as $key => $value) {
				if( doubleval($value) >= 4 && !strpos($key, '/')){

					$new_key = trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $key)," -");
					
					$this->tags[] = $new_key;
					
				}
			}
		}
		$this->result[] = "Prepare tags successful";
		// echo "Prepare tags successful</br>";
	}

	// Function format course content
	public function format_course_content(){
		$contents = json_decode($this->tmp_data->course_content);
		$course_content = '';
		if($contents->description){
			$course_content .= '<span class="course-des-title underline">About This Course</span></br>
								<span class="course-des-content">';
			$course_content .= $contents->description;
			$course_content .= '</span></br></br>';
		}

		if($contents->syllabus){
			$course_content .= '<span class="course-des-title underline">Syllabus</span></br>
								<span class="course-des-content">';
			$course_content .= $contents->syllabus;
			$course_content .= '</span></br></br>';
		}

		if($contents->reviews){
			$course_content .= '<span class="course-des-title underline">Reviews</span></br>
								<span class="course-des-content">';
			$course_content .= $contents->reviews;
			$course_content .= '</span></br></br>';
		}
		$this->result[] = "Format course content successful";
		// echo "Format course content successful</br>";
		$this->course_content = $course_content;
	}

	// Function add all relations and metadata
	public function add_relations_and_metadata($course_id){
		// $this->add_course_to_category($course_id);
		$this->add_course_to_subject($course_id);
		$this->add_course_to_specialization($course_id);
		$this->add_course_to_institution($course_id);
		$this->add_course_to_instructor($course_id);
		$this->add_course_to_provider($course_id);
		$this->add_course_to_tag($course_id);
		$this->insert_update_course_meta($course_id);
		$this->add_course_image($course_id);
	}

	// Function to add course image
	public function add_course_image($course_id){
   		$data_img = [
	   		'post_id' => $course_id,
	   		'meta_key' => 'fifu_image_url',
	   		'meta_value' => $this->tmp_meta_data->course_img_url,
   		];
   		
   		$format_img = [
   			'%d',
   			'%s',
   			'%s',
   		];
   		$this->wpdb->replace( $this->wpdb->prefix.'postmeta', $data_img, $format_img );
		$this->result[] = "Insert course image successfull";
		// echo "Insert course image successfull</br>";
	}

	// Function to add course to category
	public function add_course_to_category($course_id){
		wp_set_post_categories( $course_id, $this->cats, false );
		$this->result[] = "Add Course to Category successful";
		// echo "Add Course to Category successful</br>";
	}
	// Function to add course to specialization
	public function add_course_to_specialization($course_id){
		wp_set_post_terms( $course_id, $this->specializations, 'specialization', false );
		$this->result[] = "Add Course to Specialization successful";
		// echo "Add Course to Specialization successful</br>";
	}
	// Function to add course to institution
	public function add_course_to_institution($course_id){
		wp_set_post_terms( $course_id, $this->institutions, 'institution', false );
		$this->result[] = "Add Course to institution successful";
		// echo "Add Course to institution successful</br>";
	}
	// Function to add course to instructor
	public function add_course_to_instructor($course_id){
		wp_set_post_terms( $course_id, $this->instructors, 'instructor', false );
		$this->result[] = "Add Course to instructor successful";
		// echo "Add Course to instructor successful</br>";
	}
	// Function to add course to provider
	public function add_course_to_provider($course_id){
		wp_set_post_terms( $course_id, $this->source_name, 'provider', false );
		$this->result[] = "Add Course to provider successful";
	}
	// Function to add course to subject
	public function add_course_to_subject($course_id){
		wp_set_post_terms( $course_id, $this->subjects, 'subject', false );
		$this->result[] = "Add Course to subject successful";
		// echo "Add Course to Category successful</br>";
	}
	// Function to add course to tag
	public function add_course_to_tag($course_id){
		wp_set_post_terms( $course_id, $this->tags );
		$this->result[] = "Add Course to tag successful";
		// echo "Add Course to instructor successful</br>";
	}

	// Function set field course effort
	public function get_course_effort_field_by_source($data){
		$effort;
		switch ($this->source_name) {
			case 'edX':
				$effort = $this->return_value_or_null($data,'course_effort');
				break;

			case 'Coursera':
				$effort = $this->return_value_or_null($data,'commitment');
				break;
			
			default:
				$effort = '';
				break;
		}
		return $effort;
	}

	// Function return value or null
	public function return_value_or_null($data, $field){
		if (!isset($data->$field)) {
			$value = '';
		}
		return $data->$field;
	}

}