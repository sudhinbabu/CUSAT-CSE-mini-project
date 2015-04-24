<!-- admin home_dev.php -->
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


//add news
if(isset($_POST['addNews'])){
	$db->query('INSERT INTO news_table (
			news
			)
		VALUES (
			"'.$_POST["newsText"].'"
			)');
	$result['msg'] = 'success';
	unset($_POST);
}

//delete news.
	if(isset($_POST['deleteNews'])){
	$db->query('DELETE FROM news_table WHERE news_id="'.$_POST["news_id"].'"');
	unset($_POST);
}


//update news
if(isset($_POST['updateNews'])){
	if($_POST['updateNews'] =='yes' &&!empty($_POST['newsUpdate']) && !empty($_POST['news_id'])){
	$db->query('UPDATE news_table SET news = "'.$_POST['newsUpdate'].'"
	 WHERE news_id = "'.$_POST["news_id"].'"');
}

}

//add Constants
if(isset($_POST['addConstants'])){
	$db->query('INSERT INTO constants (
			label,
			value
			)
		VALUES (
			"'.$_POST["label"].'",
			"'.$_POST["value"].'"
			)');
	$result['msg'] = 'success';
	unset($_POST);
}

//delete constants.
	if(isset($_POST['deleteConstants'])){
	$db->query('DELETE FROM constants WHERE id="'.$_POST["id"].'"');
	unset($_POST);
}


//update constants
if(isset($_POST['updateConstants'])){
	if($_POST['updateConstants'] =='yes' &&!empty($_POST['label']) && !empty($_POST['id'])){
	$db->query('UPDATE constants SET
	 label = "'.$_POST['label'].'",
	 value = "'.$_POST['value'].'"
	 WHERE id = "'.$_POST["id"].'"');
}
unset($_POST);
}




//list news
	$results =  $db->query('SELECT * FROM news_table');
	$news_count = mysqli_num_rows($results);
	if($news_count!=0){
		while($row=mysqli_fetch_array($results,MYSQLI_ASSOC))
		$news_array[] = $row;
	}
//list constants
	$results =  $db->query('SELECT * FROM constants');
	$contants_count = mysqli_num_rows($results);
	if($contants_count!=0){
		while($row=mysqli_fetch_array($results,MYSQLI_ASSOC))
		$constants[] = $row;
	}










include '../templates/admin/header.php';
?>


	<div class="container main-content">
	

		<div class="content" >


			<h2 class="heading">news( <?php echo (isset($news_count) ? $news_count : 0 ); ?>)</h2>

<?php 

if(isset($news_array))
foreach($news_array as $news){

?>



			<table class="news-edit-table"   cellspacing="10">

					<tr>
						<td class="date-added">
							<span class="news-date-data" >
						<?php echo (isset($news['date_added']) ? $news['date_added'] : ''); ?>
							</span>


</td>
<td class="news">
<form action="home_dev.php" method="post" >
<input type="text" name="newsUpdate"   value="<?php echo (isset($news['news']) ? $news['news'] : ''); ?>">
<input  name="news_id" type="hidden" value="<?php echo (isset($news['news_id']) ? $news['news_id'] : ''); ?>">
	<button  name="deleteNews" value="yes" class="btn btn-danger " type="submit">delete</button>		
	<button name="updateNews" value="yes" class="btn btn-primary " type="submit">update</button>			
</form>
</td>
	</td>
		</tr>
	</table>
<?php } ?>


<div class="constants-edit">
<h2 class="heading">Constants</h2>
<!-- constants add  -->
<form action="home_dev.php" method="post">
	<table class="contants-edit-table">

	
	<tr>
	
			<td><input type="text" placeholder="constant label" name="label" value="">
			</td>
			<td><input placeholder="contstant value" type="text" name="value" value=""></td>
			<td>
	<button  name="addConstants" value="yes" class="btn btn-success " type="submit">add</button>		
			</td>
		</tr>
	</table>
	</form>
<!-- #constant add -->


	<table class="contants-edit-table">
<!-- 	
	<th>constant</th>
	<th>value</th>
	<th>actions</th> -->
<?php if(isset($constants)) foreach ($constants as $contstant) { ?>
	
	<tr>
	<form action="home_dev.php" method="post">
			<td><input type="text" style="width:200px;" name="label" value="<?=$contstant['label']?>">
			<input  type="hidden" name="id" value="<?=$contstant['id']?>" >
			</td>
			<td><input style="width:50px;" type="text" name="value" value="<?=$contstant['value']?>"></td>
			<td>
	<button  name="deleteConstants" value="yes" class="btn btn-danger " type="submit">delete</button>		
	<button name="updateConstants" value="yes" class="btn btn-primary " type="submit">update</button>
			</td>
				</form>
		</tr>

<?php } ?>
	</table>

</div>

			</div>
			<div class="side-bar">
				<h3>Add new news</h3>
				<form action="home_dev.php" method="post">
				<ul class="news">
					<li class="news-date" ></li>
					<li class="news-item">
						<input type="text" name="newsText" style="width:300px;">
					</li>
					<li class="actions">
						<button name="addNews" type="submit" class="btn btn-success">Add</button>
					</li>

				</ul>
</form>

			</div>





		</div>

	<?php include '../templates/admin/footer.php';