<?php
include 'configuration.php';
class Sql {

	//construtor

	public $conn;

	public function __construct(){
		return $this->conn =  mysqli_connect(SERVER,USER,PASSWORD,DB);
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