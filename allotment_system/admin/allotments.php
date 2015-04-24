<!--allotments.php  -->
<?php 
session_start();
require_once '../lib/functions.php';
require_once '../lib/db_class.php';
$db = new Database();
//if wrong user try to access this page then redirect to the home page by destroying session.
if(!is_logged_in() || get_user_type()!='admin' ){
	logout();
}

//If logout is set then do logout
if(isset($_GET['logout'])){
	logout();
}

$SYSTEM_STATUS = $db->query('SELECT  value FROM settings WHERE label="SYSTEM_STATUS"',TRUE,TRUE);

// set  ALLOTMENT_NO.
$result = $db->query('SELECT max(allotment_number) AS allotment_no FROM applications');
$allotment = mysqli_fetch_array($result,MYSQLI_ASSOC);
if($allotment['allotment_no'] == '')
	$allotment['allotment_no'] = 0;
define('ALLOTMENT_NO', $allotment['allotment_no']);


// Make allotment
if(isset($_POST['makeAllotment'])){
	//Check allotment number
	require_once 'allotment_class.php';
	$allotment = new Allotments();
	if(ALLOTMENT_NO==0)
	$allotment->make_first_allotment();
	else
	$allotment->make_next_allotment();

	// $allotment->hello();
		unset($_POST);
	header('location: allotments.php');
}
if(isset($_POST['stopAllotements'])){
	//Check allotment number
	require_once 'allotment_class.php';
	$allotment = new Allotments();
	$allotment->stop_allotments();
		unset($_POST);
	header('location: allotments.php');

}

if(isset($_POST['resetSystem'])){
	//Check allotment number
	$db->mquery('TRUNCATE applications;TRUNCATE marks;
	 UPDATE settings SET value = "TRUE" WHERE label = "SUBMIT_APPLICATION";
	 UPDATE settings SET value = 0 WHERE label = "SYSTEM_STATUS";
	 DELETE users WHERE user_type!="applicant";
	  ');
		unset($_POST);
		header('location: allotments.php');
}








include '../templates/admin/header.php';
?>
<!-- contents -->
<div class="container main-content">


<div class="content" >
<h2>Allotments</h2>

<form action="allotments.php" method="post">


 <!-- onclick="changeToProcessing(this);" -->

<?php
if($SYSTEM_STATUS==2){
?>
<button type="submit" name="resetSystem" onclick="changeToProcessing(this);"    class=" btn btn-success" >Reset allotment system</button>
<?php
}
 elseif(ALLOTMENT_NO == 0){  ?>
	<input type="submit" name="makeAllotment"onclick="changeToProcessing(this);"    value="make first allotment" class="  btn btn-success">
<?php }else{ ?>
<input type="submit" name="makeAllotment" onclick="changeToProcessing(this);"    value="make <?php
switch (ALLOTMENT_NO) {
	case '1':
		echo 'second allotment';
		break;

	case '2':
		echo 'third allotment';
		break;
	
	default:
		echo  (ALLOTMENT_NO+1).'th allotment';
		break;
} ?> " class="  btn btn-warning">
<input type="submit" name="stopAllotements" onclick="changeToProcessing(this);"    value="stop allotments" class="  btn btn-danger">
<?php } ?>


</form>

</div><!-- <div class="content" > -->
<div class="side-bar">

</div><!-- <div class="side-bar"> -->

</div><!-- <div class="container main-content"> -->



<script type="text/javascript" src="<?php echo BASE_URL.'/js/jquery.js'; ?>"></script>
<script type="text/javascript" src="<?php echo BASE_URL.'/js/allotments.js'; ?>"></script>
<?php include '../templates/admin/footer.php';?>

