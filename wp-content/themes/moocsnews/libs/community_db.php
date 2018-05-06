<?php

class Community_DB{
	public $community_db;
	public $database_infos = [];

	function __Construct($connection_string){
		parse_connection_string_to_host($connection_string);
		$this->community_db = new wpdb($this->database_infos['user'], $this->database_infos['password'], $this->database_infos['name'], $this->database_infos['host']);
	}

	public function parse_connection_string_to_host($connection_string){
		// Explode string connection host to get info for connect staging database
		// mysql://user:password@host.com:port/database_name

		$tmp_arr = explode('@', $connection_host);
		$tmp_arr_type_user_pwd = explode(':', $tmp_arr[0]);
		$tmp_arr_user = explode('//', $tmp_arr_type_user_pwd[1]);
		$tmp_arr_host_db_name = explode('/', $tmp_arr[1]);
		$this->database_infos['type'] = $tmp_arr_type_user_pwd[0];
		$this->database_infos['user'] = $tmp_arr_user[1];
		$this->database_infos['password'] = $tmp_arr_type_user_pwd[2];
		$this->database_infos['host'] = $tmp_arr_host_db_name[0];
		$this->database_infos['name'] = $tmp_arr_host_db_name[1];


		foreach ($this->database_infos as $key => $value) {
			if($value == ''){
				show_alert_and_redirect('Your connection host is not right structure. Please input right connection host structure like example.');
			}
		}
	}
}