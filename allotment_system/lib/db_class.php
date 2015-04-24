<?php
/* db_class.php
* A  simple class  for php MySQL database operations  
* Author :sudhin babu
* sudhinthedeveloper@gmail.com
*/

class Database 
{
	static $con;
	public $num_rows;
	function __construct($host='localhost',$username='root',$password='',$db_name='allotment_system')
	{
		self::$con = mysqli_connect($host,$username,$password,$db_name);

	// Check connection
		if (!self::$con) {
			die("Connection failed: " . mysqli_connect_error());
		}
	}


	// public function query($sql , $return_associative_array = FALSE,$return_single_value=FALSE){

	// 	if ($results = mysqli_query(self::$con,$sql)) {
	// 		if($return_associative_array){

	// 			$results_array = array();


	// 			while($row = mysqli_fetch_array($results,MYSQLI_ASSOC)){
	// 					$results_array[] = $row;				
	// 				}


	// 			if(($this->num_rows=mysqli_num_rows($results))==1){
	// 				//for single value when one column and one row
	// 						if($return_single_value){
	// 							$value_array = mysqli_fetch_array($results);
	// 							return $value_array[0];
	// 						}

	// 					}
	// 		return $results_array;		
	// 		}
	// 		else{
	// 		return $results;
	// 			}
	// 	}
	// 	else {
	// 		$trace =   debug_backtrace();
	//      	echo ' <hr> <h3>sql query error </h3> error =><br>  '.mysqli_error(self::$con).'<br>
	//      	  <br> file =>'.$trace[0]['file'].' ::  line:'.$trace[0]['line'].' <br><br>';
	// 		echo 'query tried to execute: => <br> '. nl2br(str_replace(' ','&nbsp;&nbsp;',print_r($trace[0]['args'][0],TRUE))).'<hr>';
	// 		exit();
	// 	}
		
	// }	



	public function query($sql , $return_associative_array = FALSE,$return_single_value=FALSE){

		if ($results = mysqli_query(self::$con,$sql)) {
			if($return_associative_array){
					if(($this->num_rows=mysqli_num_rows($results))==1){
							if($return_single_value){
								$value_array = mysqli_fetch_array($results);
								return $value_array[0];
							}
						return mysqli_fetch_array($results,MYSQLI_ASSOC);
						}
						else{
							$results_array = array();
						while($row = mysqli_fetch_array($results,MYSQLI_ASSOC)){
							$results_array[] = $row;				
						}
						return $results_array;
						}
					
			}
			else{
			return $results;
				}
		}
		else {
			$trace =   debug_backtrace();
	     	echo ' <hr> <h3>sql query error </h3> error =><br>  '.mysqli_error(self::$con).'<br>
	     	  <br> file =>'.$trace[0]['file'].' ::  line:'.$trace[0]['line'].' <br><br>';
			echo 'query tried to execute: => <br> '. nl2br(str_replace(' ','&nbsp;&nbsp;',print_r($trace[0]['args'][0],TRUE))).'<hr>';
			exit();
		}
		
	}


		public function mquery($sql , $return_associative_array = FALSE,$return_single_value=FALSE){

		if ($results = mysqli_multi_query(self::$con,$sql)) {
			if($return_associative_array){
					if(mysqli_num_rows($results)==1){
							if($return_single_value){
								$value_array = mysqli_fetch_array($results);
								return $value_array[0];
							}
						return mysqli_fetch_array($results,MYSQLI_ASSOC);
						}
						else{
							$results_array = array();
						while($row = mysqli_fetch_array($results,MYSQLI_ASSOC)){
							$results_array[] = $row;				
						}
						return $results_array;
						}
					
			}
			else{
			return $results;
				}
		}
		else {
			$trace =   debug_backtrace();
	     	echo ' <hr> <h3>sql query error </h3> error =><br>  '.mysqli_error(self::$con).'<br>
	     	  <br> file =>'.$trace[0]['file'].' ::  line:'.$trace[0]['line'].' <br><br>';
			echo 'query tried to execute: => <br> '. nl2br(str_replace(' ','&nbsp;&nbsp;',print_r($trace[0]['args'][0],TRUE))).'<hr>';
			exit();
		}
		
	}

	private function close(){
		mysqli_close(self::$con);
	}

	public function last_inserted_id(){
		return mysqli_insert_id(self::$con);
	}




	
}



