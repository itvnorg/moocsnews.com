<?php

require_once MOOCSNEWS_THEME_LIB_DIR . '/course.php';

class Course_edX extends Course{
	function __construct(){
		parent::__construct('edX');
	}

	public function prepare_specs(){
		$this->prepare_specializations($this->tmp_meta_data->x_series);
	}
}