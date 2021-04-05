<?php

class Sql {

	//construtor

	public $conn;
	private const SETTINGS='.env.config.ini';
	private $settings_db;
	public function __construct(){

		$this->settings_db = parse_ini_file(Sql::SETTINGS);
		
		return $this->conn =  mysqli_connect(
			$this->settings_db['db_server'],
			$this->settings_db['db_user'],
			$this->settings_db['db_password'],
			$this->settings_db['db_schema']
		);
	}

	public function __destruct(){
		mysqli_close($this->conn);
	}

	public function query($string_query){
		return mysqli_query($this->conn, $string_query);
	}

	public function select($string_query){

	
	    $result = $this->query($string_query);


	    $data = array();
	    
		    while ( $row = mysqli_fetch_array($result) ) {

	    	foreach ($row as $key => $value) {	
	    		$row[$key] = utf8_encode($value);
	    		# code...
	    	}
	    	
	        array_push($data, $row);
	    }

	    unset($result);

	    return $data;
	}
}


?>