<?php 
// session_name("MiniProject");
// session_start();

/**
*encryption function
*@access public 
*@return string with length 127 characters
*@param string - This value is given value to be hashed
*@param salt - This is used to add ass salt while encryption
*/
function encrypt($string,$salt=NULL){
	if($salt==NULL){
		$salt ='kidilan';
	}
	//
	// $salt ='kidilan';
	// exit(substr(hash('sha256',$string).hash('ripemd256',$salt),2));
	//
	 return substr(hash('sha256',$string).hash('ripemd256',$salt),2); 
	}


	
function get_user_type(){
	return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : false;
}

function is_logged_in(){
	if(isset($_SESSION['is_logged_in']))
		if(!$_SESSION['is_logged_in']){
			$_SESSION['msg'] ="user not logged in.";
			return FALSE;
		}
		return TRUE;
	}

function get_user_id(){
	if(!$result = is_logged_in())
		return $result;
	else
		return $_SESSION['user_id'];
}

function logout(){
		session_destroy();
		header('location: '.BASE_URL);
		exit();
	}

function login_redirect(){
	if($_SESSION['is_logged_in']){
		//selecting home page based on user type.
		$user = get_user_type()=='admin' ? 'admin' : 'applicant';
		header('location: '.BASE_URL.'/'.$user.'/home.php');
		exit();
	}
	logout();
}


function base_url(){
	$url = $_SERVER['REQUEST_URI'];
	$url_as_array = explode('/', $url);
	$folder_name = $url_as_array[1];
	return 'http://'.$_SERVER['HTTP_HOST'].'/'.$folder_name;
}

function file_name(){
	$url = $_SERVER['REQUEST_URI'];
	$url_as_array = explode('/', $url);
	$length = count($url_as_array);
	$file_name = $url_as_array[$length-1];
	return $file_name;
}




function response($result,$status=200){
	http_response_code($status);
	echo json_encode($result);
	exit();
}

define( 'BASE_URL',base_url());
?>

