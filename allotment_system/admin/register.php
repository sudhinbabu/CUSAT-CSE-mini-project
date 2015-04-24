<?php 
//If we need to use a session any where in the page it should be started first in the page.
session_start();
//adding reqired files.
require_once '../lib/functions.php';
require_once '../lib/validation_class.php';
require_once '../lib/db_class.php';
require_once '../lib/upload_class.php';
$db = new Database();

define('SUBMIT_APPLICATION',$db->query('SELECT value FROM settings WHERE label="SUBMIT_APPLICATION"',TRUE,TRUE));

if(SUBMIT_APPLICATION=='TRUE'){
if(isset($_POST['register'])){




	//validate register data
	$security = new Validation();
	$result = $security->validate_form_data($_POST);
		// validate captcha
	if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){  
		$result['captcha_error']='The Validation code does not match!';// Captcha verification is incorrect.		
		$result['error']=TRUE;
	}
		//upload photo after validation
	if(!empty($_FILES['fileToUpload']['name'])) {
	//check other errors 
		if(!$result['error']){
			$db_result = $db->query('SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1 ');
			$user_id = mysqli_fetch_row($db_result);
			$user_id = $user_id[0] + 1;
			$upload = new Upload();	
			$upload_errors = $upload->upload_file($user_id);
			if($upload_errors['error'])
			{
				$result = array_merge($result,$upload_errors);
			}
		}
	}
	else{
		$result['error'] = true;
		$result['upload_error'] = array('invalid file');
	}
	
	//add to database if there is no error.
	//create user
	if(!$result['error']){
		$result['password'] = encrypt($result['password'],$result['username']);
		$result['dob']=date('Y-m-d',strtotime($result['dob']));
		$db->query('INSERT INTO users (
			username, 
			password,
			email,
			name,
			dob,
			gender,
			parent_name,
			occupation,
			phone,
			address
			)
		VALUES (
			"'.$result["username"].'",
			"'.$result["password"].'",
			"'.$result["email"].'",
			"'.$result["name"].'",
			"'.$result["dob"].'",
			"'.$result["gender"].'",
			"'.$result["name_of_parent"].'",
			"'.$result["occupation"].'",
			"'.$result["phone"].'",
			"'.$result["address"].'"
			)');

		$_SESSION = $result;
//get last inserted user id.
		$user_id = $db->last_inserted_id();
//create application

		$db->query('INSERT INTO applications (
			user_id, 
			year_of_passing,
			religion_id,
			first_preference,
			second_preference,
			third_preference
			)
		VALUES (
			"'.$user_id .'",
			"'.$result["year_of_pass"].'",
			"'.$result["religon_id"].'",
			"'.$_POST['preference'][0].'",
			"'.$_POST['preference'][1].'",
			"'.$_POST['preference'][2].'"
			)');

//create application



//Add total marks scored
		print_r($result["_max_mark"]);
		$db->query('INSERT INTO marks (
			user_id, 
			subject_name,
			max_mark,
			awarded_mark
			)
		VALUES (
			"'.$user_id .'",
			"total_marks",
			"'.$result["_max_mark"].'",
			"'.$result["_awarded_mark"].'"
			)');
		

	$i=0;
		
		foreach ($_POST['subject_name'] as $subject_name) {		
			$db->query('INSERT INTO marks (
				user_id, 
				subject_name,
				max_mark,
				awarded_mark
				)
			VALUES (
				"'.$user_id .'",
				"'. $subject_name.'",
				"'.$_POST['max_mark'][$i].'",
				"'.$_POST['awarded_mark'][$i].'"
				)');
			$i++;
		}

		unset($_POST);

		$_SESSION["msg_register"]= 'successfully registered please login';
		header('location: '.BASE_URL);
		exit();
	}else
	{
	//To print errors  stored in session.
		$_SESSION = $result;
	}
}
//list all religions
$results = $db->query('SELECT * FROM religions ORDER BY name ASC');
while ($row = mysqli_fetch_array($results,MYSQLI_ASSOC)) {
	$religions[] = $row; 
}



//list courses
$results =  $db->query('SELECT * FROM courses');
$course_count = mysqli_num_rows($results);
if($course_count!=0){
	while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
		$courses[] = $row;

	}
}

//get course dependencies 
//called via ajax

if(isset($_POST['getCourseDependencies'])){

	$sql ='SELECT DISTINCT dependency FROM course_dependencies
	WHERE course_id IN (
		"'.$_POST['preference'][0].'",
		"'.$_POST['preference'][1].'",
		"'.$_POST['preference'][2].'" )';
$results = $db->query($sql);
while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
	$subjects[]= $row['dependency'];
}
echo json_encode($subjects);
exit();
}




// }






include '../templates/header.php';
?>

<div class="container">
	<h2 class="heading">Submit your application</h2>
	<div class="main-content">
		<form action="register.php" method="post" class="registation-form"  enctype="multipart/form-data">
			<table class="register-table">

				<tr>
					<td>
						<label class="reg-input-label">upload photo</label>
					</td>
					<td><input class="form-control" name="fileToUpload" onchange="readURL(this);" type="file" required>
						<?php if(isset($_SESSION['upload_error'])) foreach ($_SESSION['upload_error'] as $upload_error) echo '<span class="input-error-msg"> *'.$upload_error.'</span><br>'?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="reg-input-label">name</label>
					</td>
					<td><input class="form-control" name="name" type="text"  required>
						<?php  echo isset($_SESSION['name_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['name_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">name of father</label>
					</td>
					<td><input class="form-control"  name="name_of_parent" type="text" required>
						<?php  echo isset($_SESSION['name_of_parent_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['name_of_parent_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">DOB</label>
					</td>
					<td>
						<input placeholder="12-Nov-1980"class="form-control"  name="dob" type="text" required>

						<?php  echo isset($_SESSION['dob_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['dob_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">address</label>
					</td>
					<td><textarea class="form-control" name="address" cols="21" required></textarea>
						<?php  echo isset($_SESSION['address_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['address_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">phone</label>
					</td>
					<td><input class="form-control" placeholder="9020424243" name="phone" type="text" required>
						<?php  echo isset($_SESSION['phone_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['phone_error'].'</span>' : '' ;  ?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="reg-input-label">parent occupation</label>
					</td>
					<td><input class="form-control" name="occupation" type="text" required>
						<?php  echo isset($_SESSION['occupation_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['occupation_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">email</label>
					</td>
					<td><input class="form-control" name="email" type="email" required>
						<?php  echo isset($_SESSION['email_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['email_error'].'</span>' : '' ;  ?>
					</td>
				</tr>


				<tr>
					<td>
						<label class="reg-input-label">gender</label>
					</td>
					<td><input class="form-control" name="gender" type="text" required>
						<?php  echo isset($_SESSION['gender_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['gender_error'].'</span>' : '' ;  ?>
					</td>
				</tr>


				<!-- Academic details -->

				<tr>
					<td>
						<label class="reg-input-label">year of passing</label>
					</td>
					<td><input class="form-control" name="year_of_pass" type="text" required>
						<?php  echo isset($_SESSION['']) ?  '<span class="input-error-msg"> *'.$_SESSION['year_of_pass_error'].'</span>' : '' ;  ?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="reg-input-label">Religion</label>
					</td>
					<td>

	<select class="form-control" name="religon_id" id="religion_id">
							<?php  foreach ($religions as $religion) {
echo "<option value =".$religion['religion_id'].">".$religion['name']." , ".$religion['caste_name'] ."</option>";
							} ?>

						</select>
						<?php  echo isset($_SESSION['religion_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['religion_error'].'</span>' : '' ;  ?>
					</td>
				</tr>

			<tr>
					<td>
						<label class="reg-input-label">Total marks scored</label>
					</td>
					<td><input placeholder="maximum mark"  class="form-control" name="_max_mark" type="text" required>
						<?php  echo isset($_SESSION['_max_mark_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['max_mark_error'].'</span>' : '' ;  ?>
				

<input placeholder="awarded mark" class="form-control"  name="_awarded_mark" type="text" required>
						<?php  echo isset($_SESSION['_awarded_mark_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['awarded_mark_error'].'</span>' : '' ;  ?>
				
					</td>
				</tr>




				<tr>
					<td>
						<label class="reg-input-label">first preference</label>
					</td>
					<td>

						<select  class="course-preference form-control" name="preference[]" id="religion_id">
							<option value=""></option>
							<?php  foreach ($courses as $course) {
								echo "<option value =".$course['course_id'].">".$course['name']."</option>";
							} ?>

						</select>
						<?php  echo isset($_SESSION['course_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['course_error'].'</span>' : '' ;  ?>
					</td>
				</tr>


				<tr>
					<td>
						<label class="reg-input-label">second preference</label>
					</td>
					<td>

						<select  class="course-preference form-control" name="preference[]" id="religion_id">
							<option value="" selected=""></option>
							<?php  foreach ($courses as $course) {
								echo "<option value =".$course['course_id'].">".$course['name']."</option>";
							} ?>

						</select>
						<?php  echo isset($_SESSION['course_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['course_error'].'</span>' : '' ;  ?>
					</td>
				</tr>



				<tr>
					<td>
						<label class="reg-input-label">third preference</label>
					</td>
					<td>

						<select  class="course-preference form-control" name="preference[]" id="religion_id">
							<option value="" ></option>
							<?php  foreach ($courses as $course) {
								echo "<option value =".$course['course_id'].">".$course['name']."</option>";
							} ?>

						</select>
						<?php  echo isset($_SESSION['course_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['course_error'].'</span>' : '' ;  ?>
					</td>
				</tr>








				<!-- course dependencies marks -->
				<tr>
					<td>
						<label class="reg-input-label">Your Marks in</label>
					</td>

					<td id="ApplicantMarks">
						<div>
							
						</div>
					</td>
					


				</tr>



				<!--# course dependencies marks-->


















				<!--# Academic details -->

				<tr>
					<td>
						<label class="reg-input-label">username</label>
					</td>
					<td><input class="form-control" name="username" type="text">
						<?php  echo isset($_SESSION['username_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['username_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">password</label>
					</td>
					<td><input class="form-control" name="password" type="text">
						<?php  echo isset($_SESSION['password_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['password_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">confirm password</label>
					</td>
					<td><input class="form-control" name="confirm_password" type="text">
						<?php  echo isset($_SESSION['confirm_password_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['confirm_password_error'].'</span>' : '' ;  ?>
					</td>
				</tr>
				<tr>
					<td>
						<label class="reg-input-label">Enter the code above here </label>
					</td>
					<td>
						<img src="../lib/captcha/captcha.php?rand=<?php echo rand();?>" id='captchaimg'>
						<br>
						<input class="form-control" id="captcha_code" name="captcha_code" required type="text">
						<br>
						<?php  echo isset($_SESSION['captcha_error']) ?  '<span class="input-error-msg"> *'.$_SESSION['captcha_error'].'</span>' : '' ;  ?>
						<br>
						Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a>

					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td><input class="btn btn-success" name="register" type="submit" value="submit"></td>
				</tr>
				
			</table>
			<img src="" id="uploadingPic" alt="">
		</form>

	</div>
</div>


<script src="../js/jquery.js"></script>
<script src="../js/captcha.js"></script>
<script src="../js/showUploadPic.js"></script>
<script src="../js/register.js"></script>
<?php

}

else{
	include '../templates/header.php';
?>
<div class="container">
	<h3 class="heading">Application Registration closed.</h3>
	<!-- <div class="main-content"></div> -->
</div>


<?php
}
include '../templates/footer.php';
//destroying session for removing errors on refresh.
session_destroy();




?>