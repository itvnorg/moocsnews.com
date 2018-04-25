<?php

require_once MOOCSNEWS_THEME_LIB_DIR . '/course.php';

class Course_Coursera extends Course{
	function __construct(){
		parent::__construct('Coursera');
		
	}

	public function prepare_specs(){
		$this->prepare_specializations($this->tmp_meta_data->specializations);
	}
}