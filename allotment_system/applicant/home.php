<?php 
session_start();
require_once '../lib/functions.php';
require_once '../lib/validation_class.php';
require_once '../lib/db_class.php';
require_once '../lib/upload_class.php';

$db = new Database();
$upload = new Upload();	
//if wrong user came to this page then redirect to the home page by destroying session.
if(!is_logged_in() || get_user_type()!='applicant' ){
	logout();
}


if(isset($_GET['logout'])){
	logout();
}


//get application 
$result = $db->query('SELECT * FROM applications WHERE user_id = "'.get_user_id().'"');
$application = mysqli_fetch_array($result,MYSQLI_ASSOC);





// cancel application
if(isset($_POST['cancelApplication']) || isset($_POST['cancelAllottedApplication']) ){
	$db->query('DELETE FROM applications WHERE user_id = '.get_user_id());
	$db->query('DELETE FROM users WHERE user_id = '.get_user_id());
	$db->query('DELETE FROM marks WHERE user_id = '.get_user_id());
	logout();
}



//confirm alloted course

if(isset($_POST['confirmAllotment'])){
	$db->query('UPDATE applications SET status = "confirmed" WHERE user_id = '.get_user_id());
}



//Reset password
if(isset($_POST['reset_password'])){
	//getting username(for salt in encrypt()) and password
	$result = $db->query('SELECT username , password  FROM users WHERE 
		user_id = '.get_user_id(),TRUE);


	if($result['password'] == encrypt($_POST['old_password'],$result['username']) AND $_POST['new_password'] == $_POST['c_new_password'] ){
		$db->query('UPDATE users SET password = "'.encrypt($_POST['new_password'],$result['username']).'" WHERE user_id = '.get_user_id());
		echo "<script>alert('updated password successfully');</script>";
	}else{
		echo "<script>alert('invalid old password or passwords doesnt matches.');</script>";
	}

	
}

//password changer for developer .
// exit(encrypt('password','username'));

//get user profile image extension
$ext = '';
$allowed_types = $upload->get_allowed_types();
foreach ($allowed_types as $extension) 
	if(file_exists("../uploads/profile_photos/_".get_user_id().".".$extension))
	 $ext = $extension;
	
include '../templates/applicant/header.php';

//get user details.
$result = $db->query('SELECT * FROM users WHERE user_id = "'.get_user_id().'"');
$user = mysqli_fetch_array($result,MYSQLI_ASSOC);

?>

	<div class="container main-content">
			<div class="content" >


		<div class="challan-section">
			<!-- if challan not payed -->
			<?php if($application['chellan_payment'] == 0){ ?>
	<h5><div class="alert alert-warning" role="alert"><h4>Payment Pending.</h4>
	you have to complete challan payment to continue.</div></h5>
<a href="challan.php" target="_blank">click here to download chellan</a>
			<?php	} else{ ?>

			<!-- else -->
			<div class="alert alert-success" role="alert"><h4>Challan Payment Completed.</h4></div>

		<?php
		$result = $db->query('SELECT max(allotment_number) AS allotment_no FROM applications');
		$allotment = mysqli_fetch_array($result,MYSQLI_ASSOC);
	if($allotment['allotment_no'] != 0)
		{
			//show allotment details
	$alloted_course = $db->query('SELECT 
    courses.name,courses.course_id,applications.user_id, applications.status as status
FROM
    applications
        JOIN
    courses ON courses.course_id = applications.alloted_course_id
WHERE
    applications.user_id ='.get_user_id().' AND applications.status IN ("confirmed","alloted")' ,TRUE);		

		}
if(!empty($alloted_course)){
	if($alloted_course['status']=="alloted"){
?>

<div class="alloted-course">
	
	<div class="alert alert-warning" role="alert"><h3>You are alloted to <strong><?=$alloted_course['name'];?></strong>
	<h5>* If you confirm this course. you will be allocated to this
	 course and other preferences will not be considered.<br><br>
	 * You can wait for further allotments or you can cancel 
	 application. <br><br>*If you canceled application   you account will be deleted and 
	 you cannot login back to your accout.
	  </h5>

	<form action="home.php" method="post">
		<input type="submit" class="btn btn-success" name="confirmAllotment" value="confirm">
		<input type="submit" class="btn btn-danger" name="cancelAllottedApplication" value="cancel application">
	</form>


</h3>


</div>

	
</div>
<?php
}
elseif($alloted_course['status']=="confirmed"){
?>
	<div class="alert alert-success" role="alert"><h3>You are alloted to <strong><?=$alloted_course['name'];?></strong>
	<h5>*Alloted course have been confirmed <br>
	<form action="print_allotment.php" target="__blank" >
		<input type="hidden" name="applicant_name" value="<?=$user['name']?>">
		<input type="hidden" name="course_name" value="<?=$alloted_course['name']?>">
		<input type="hidden" name="user_id" value="<?=$user['user_id']?>">
		<br>
<button class="btn btn-primary" type="submit">click here to download allotment details</button>
	</form>

	 </h5>


</h3>


</div>





<?php
}



}else{

?>
<div class="alert alert-warning" role="alert">
	<h3>You are not alloted to any course. 
	<h5>* You can wait for further allotments or you can cancel application<br><br>
	 *If you canceled application   you account will be deleted and 
	 you cannot login back to your accout.
	  </h5>
	<form action="home.php" method="post">
		<input type="submit" name="cancelApplication" class="btn btn-danger" value="cancel application">
	</form>

</h3>
</div>

<?php

}


		 } ?>

	</div><!-- <div class="challan-section"> -->

<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Reset password</h3>
  </div>
  <div class="panel-body">
    <form class="form-horizontal" role="form" action="home.php" method="post">

  <div class="form-group">
    <label class="control-label col-sm-3" for="oldp">Old password:</label>
    <div class="col-sm-5">
      <input type="password" name="old_password" class="form-control" id="oldp" placeholder="">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3" for="pwd">New password:</label>
    <div class="col-sm-5"> 
      <input type="password" name="new_password" class="form-control" id="pwd" >
    </div>
  </div>

 <div class="form-group">
    <label class="control-label col-sm-3" for="cpwd">Confirm New Password:</label>
    <div class="col-sm-5"> 
      <input type="password" name="c_new_password" class="form-control" id="cpwd" >
    </div>
  </div>



  <div class="form-group"> 
    <div class="col-sm-offset-3 col-sm-2">
      <button type="submit" name="reset_password" class="btn btn-primary">Change</button>
    </div>
  </div>
</form>




  </div>
</div>




			</div>

<div class="side-bar">
<div class="personnel-details">
<h2>Personnel details</h2>
<img class="upload_pic"  id="uploadingPic" height="130" width="100" src="../uploads/profile_photos/_<?=get_user_id().'.'.$ext?>" alt="profile picture">
<table class="user-details-table">
	<tr>
		<td width="100px"><label for="">Name</label></td>
		<td><label for="">: <?=$user['name'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">DOB</label></td>
		<td><label for="">: <?=$user['dob'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">Address</label></td>
		<td><label for="">: <?=$user['address'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">Parent name</label></td>
		<td><label for="">: <?=$user['parent_name'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">Gender</label></td>
		<td><label for="">: <?=$user['gender'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">Parent occupation</label></td>
		<td><label for="">: <?=$user['occupation'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">Contact no</label></td>
		<td><label for="">: <?=$user['phone'] ?></label></td>
	</tr>
	<tr>
		<td><label for="">email</label></td>
		<td><label for="">: <?=$user['email'] ?></label></td>
	</tr>
</table>


</div>
</div>





		</div>
<script src="../js/jquery.js"></script>
<script src="../js/showUploadPic.js"></script>
<?php
include '../templates/applicant/footer.php';
 ?>


			