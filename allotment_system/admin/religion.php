<!--admin religion.php  -->
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


//add new religion
if(isset($_POST['addReligion'])){
	$db->query('INSERT INTO religions (
			name,
			caste_name,
			category,
			reservation_percentage
			)
		VALUES (
			"'.$_POST["name"].'",
			"'.$_POST["caste_name"].'",
			"'.$_POST["category"].'",
			"'.$_POST["reservation_percentage"].'"
			)');
	unset($_POST);
	header('location: religion.php');
}

//delete religion.
	if(isset($_POST['deleteReligion'])){
	$db->query('DELETE FROM religions WHERE religion_id="'.$_POST['religion_id'].'"');
	unset($_POST);
}


//update religion
if(isset($_POST['updateReligion'])){
	$db->query('UPDATE religions SET name = "'.$_POST['name_update'].'",
		caste_name = "'.$_POST['caste_name_update'].'",
		category = "'.$_POST['category_update'].'",
		reservation_percentage = "'.$_POST['reservation_percentage_update'].'"
	 WHERE religion_id = "'.$_POST["religion_id"].'"');
		echo "<script>alert(' updated religion : ".$_POST['name_update']." , ".$_POST['caste_name_update']."')</script>";


}

//list religions
	$results =  $db->query('SELECT * FROM religions');
	$religions_count = mysqli_num_rows($results);
	if($religions_count!=0){
		while($row=mysqli_fetch_array($results,MYSQLI_ASSOC))
		$religions[] = $row;
	}





include '../templates/admin/header.php';
?>


	<div class="container main-content">


		<div class="content-full" >

<div class="add-table">
<h3>Add new religion</h3>
<form action="religion.php" method="post">


<table cellspacing="5px" class="religion-add-table">
 <tbody>
	<th>Religion name</th>
	<th>Caste name</th>
	<th>Category name</th>
	<th>reservation percentage</th>
	<th></th>
	<tr>
		<td><input type="text" name="name"></td>
		<td><input type="text" name="caste_name"></td>
		<td><input type="text" name="category"></td>
		<td><input type="text" name="reservation_percentage"></td>
		<td><input class="btn btn-success add-religion-btn"  type="submit" name="addReligion" value="add"></td>
	</tr>
	</tbody>
</table>
</form>

			</div>



			<h2 class="heading">Religions( <?php echo (isset($religions_count) ? $religions_count : 0 ); ?>)</h2>
	<table  class="religion-edit-table">

	<th>Religion name</th>
	<th>Caste name</th>
	<th>Category name</th>
	<th>reservation percentage</th>
	<th></th>
<?php 

if(isset($religions))

foreach($religions as $religion){

?>
	<tr>
		<form action="religion.php" method="post">
		<td><input value="<?=$religion['name'] ?>" type="text" name="name_update"></td>
		<td><input value="<?=$religion['caste_name'] ?>" type="text" name="caste_name_update"></td>
		<td><input value="<?=$religion['category'] ?>" type="text" name="category_update"></td>
		<td><input value="<?=$religion['reservation_percentage'] ?>" type="text" name="reservation_percentage_update">
		<input type="hidden" name="religion_id" value="<?=$religion['religion_id']?>">
		</td>
		<td>
	
		<div class="btn-group" role="group" aria-label="...">
  <button type="submit" name="deleteReligion" value="yes" class="religion-edit-btn btn btn-danger">delete</button>
  <button type="submit" name="updateReligion" value="yes" class="religion-edit-btn btn btn-primary">Update</button>
		</div>
		</form>
		</td>
	</tr>
	</form>
<?php } ?>
</table>

</div>





		</div>

	<?php include '../templates/admin/footer.php';