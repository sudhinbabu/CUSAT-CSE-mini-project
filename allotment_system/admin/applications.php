<!--applictions.php  -->
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
//To search
if(isset($_GET['search_key'])){
	$applications= $db->query('SELECT 
		name,
		email,
		phone,
		first_preference,
		second_preference,
		third_preference,
		chellan_payment,
		alloted_course_id
	FROM  users JOIN applications ON users.user_id = applications.user_id 
	WHERE '.$_GET["search_key"].' LIKE "%'.$_GET['search_value'].'%";
		', TRUE);



}else{
	$applications= $db->query('SELECT 
		name,
		email,
		phone,
		first_preference,
		second_preference,
		third_preference,
		chellan_payment,
		alloted_course_id
	FROM  users JOIN applications ON users.user_id = applications.user_id ' , TRUE);
}
//get courses for create map
$courses = $db->query('SELECT course_id ,name  FROM courses',TRUE);
foreach ($courses as $course) {
	$course_map[$course['course_id']]=$course['name'];
}



//for single value records
if($db->num_rows==1){
	$temp = array();
	$temp[0] = $applications;
	$applications = $temp;
}

// echo nl2br(str_replace(' ','&nbsp;&nbsp;&nbsp;',print_r($applications,TRUE))).'<hr>';exit('breakpoint 1');


include '../templates/admin/header.php';
?>
<!-- contents -->
<div class="container main-content">


<h2>Applications(<?=$db->num_rows?>)</h2>

<div class="search-box">
	<form action="applications.php" method="GET"  class="form-inline" role="form">
<!-- 	<label for="searchIn">search in</label>
	<select id="searchIn" class="form-control" name="search_in">
		<option selected="true" value="all">all</option>
		<option value="physics">physics</option>
		<option value="chemistry">chemistry</option>
		<option value="maths">maths</option>
	</select> -->
	<input  placeholder="search.."  class="form-control" type="text" name="search_value">

	<button class="btn btn-primary" type="submit">search <i class="glyphicon glyphicon-search"></i></button>
	<br>
	<span class="options-radio"><input type="radio" name="search_key" checked="" value="name" id="">name</span>
	<span class="options-radio"><input type="radio" name="search_key" value="phone" id="">phone</span>
	<span class="options-radio"><input type="radio" name="search_key" value="email" id="">email</span>
	</form>
</div>







<table class="table table-hover">
<thead>
<tr>
<th>name</th>
<th>email</th>
<th>phone</th>
<th>1st preference</th>
<th>2nd preference</th>
<th>3rd preference</th>
<th>challan payment</th>
<th>allotment status</th>
</thead>
</tr>
<?php foreach($applications as $application){ ?>


	<tr>
		<td><?=$application['name']?></td>
		<td><?=$application['email']?></td>
		<td><?=$application['phone']?></td>
		<td><?=$course_map[ $application['first_preference']]?></td>
		<td><?=$course_map[$application['second_preference']]?></td>
		<td><?=$course_map[$application['third_preference']]?></td>
		<td><?=($application['chellan_payment']==1) ? 'success' : 'pending' ?></td>
		<td><?=($application['alloted_course_id']) ? $course_map[$application['alloted_course_id']] : 'waiting list'; ?> </td>
	</tr>

	<?php } ?>
</table>


</div><!-- <div class="content" > -->
<div class="side-bar">
</div><!-- <div class="side-bar"> -->

</div><!-- <div class="container main-content"> -->



<script type="text/javascript" src="<?php echo BASE_URL.'/js/jquery.js'; ?>"></script>
<script type="text/javascript" src="<?php echo BASE_URL.'/js/applications.js'; ?>"></script>
<?php include '../templates/admin/footer.php';?>