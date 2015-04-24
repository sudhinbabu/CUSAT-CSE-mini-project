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



include '../templates/admin/header.php';
?>
<!-- contents -->
<div class="container main-content">


<div class="content" >

</div><!-- <div class="content" > -->
<div class="side-bar">

</div><!-- <div class="side-bar"> -->

</div><!-- <div class="container main-content"> -->



<script type="text/javascript" src="<?php echo BASE_URL.'/js/jquery.js'; ?>"></script>
<?php include '../templates/admin/footer.php';?>