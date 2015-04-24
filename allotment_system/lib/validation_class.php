<?php
require_once('db_class.php');
 class Validation{
	public function validate_form_data($form){
		$validation["error"]= FALSE;
		foreach ($form as $index => $field) {
			switch ($index) {

				case 'email':
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!filter_var($field,FILTER_VALIDATE_EMAIL)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " invalid mail";
				}
				elseif(!$this->is_email_used($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " email already used";
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
					

				}

				break;



				case 'username':
				case 'uname':
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!$this->is_username_used($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " username already used";
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));

				}

				break;



				case 'max_marks':
				case 'preference':
				case 'awarded_mark':
				$validation[$index] = $index;
				break;

				case 'dob':
				case 'DOB':
				case 'data_of_birth':
				case 'birth_date':
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!preg_match("/^((31(?! (Feb|Apr|Jun|Sep|Nov)))|((30|29)(?! Feb))|(29(?= Feb (((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))|(0?[1-9])|1\d|2[0-8])-(Jan|Feb|Mar|May|Apr|Jul|Jun|Aug|Oct|Sep|Nov|Dec)-((1[6-9]|[2-9]\d)\d{2})$/i", $field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " invalid date"; 
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
					
				}

				break;


				case 'sex':
				case 'gender':
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!preg_match("/^(male|female)$/", $field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " gender error"; 
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
					
				}

				break;






				case 'url':
				case 'website':
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " invalid url"; 
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));


				}
				break;


				case 'c_password' :
				case 'confirm_password':
				case 'cpassword':
					if(empty($field)){	
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif($form['password'] != $field){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = " passwords doesnt matches";
				}	
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
					
				}
				break;

			

				case 'mobile':
				case 'phone':

				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				elseif(!preg_match("/^[789]\d{9}$/", $field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = "invalid mobile number";
				}
				else{
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
				}	
				break;
				
				default:
				if(empty($field)){
					$validation["error"] = TRUE;
					$validation[$index.'_error'] = $index." is empty";
				}
				else{
					//if no error then place it in the same index with value.
					$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));

					// $validation[$index]=$field;
				}
				
				break;
			}
		}
		// print_r($validation); exit(' :: breakpoint 23123'); 
		return $validation;			
	} 


	public 	function validate_credentials_with_db($form,$usertype='users'){
		$validation['error'] = FALSE;
		foreach ($form as $index => $field) {
			
			if(empty($field)){
				$validation["error"] = TRUE;
				$validation[$index] = $index." is empty";
			}

			else{
				$validation[$index]=trim(filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS));
			}
		}
		//check field is empty.
		if(!$validation['error']){
		$username = $validation['username'];
		$password = encrypt($validation['password'],$username);
		// exit($password);
		$sql="SELECT user_id , user_type FROM ".$usertype." WHERE password ='$password' AND username='$username'";
		$db = new Database();
		$result = $db->query($sql);
		if(mysqli_num_rows($result)!=1){
			$validation["error"] = TRUE;
		}
		else{
			$result = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$validation['user_id'] = $result['user_id'];
			$validation['user_type'] = $result['user_type'];
		}
	}
		return $validation;
	}


	private  function is_email_used($email){
		$db = new Database();
		$result = $db->query('SELECT * FROM users WHERE email="'.$email.'"');
		if(mysqli_num_rows($result) != 0)
			return false;
		return true; 	
	}

	private  function is_username_used($username){
		$db = new Database();
		$result = $db->query('SELECT * FROM users WHERE username="'.$username.'"');
		if(mysqli_num_rows($result) != 0)
			return false;
		return true; 	
	}



}

