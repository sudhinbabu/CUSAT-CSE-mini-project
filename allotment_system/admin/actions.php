<?php 
require_once '../lib/functions.php';
require_once '../lib/db_class.php';
$db = new Database();

//list news
if(isset($_GET['list_news'])){
	$results =  $db->query('SELECT * FROM news_table');
	if(mysqli_num_rows($results)==0){
		// $jsonArray[]  = array('news_id' => '','news'=>'','date_added'=>'' );
		$jsonArray  = array('news_count' => 0 );
		response($jsonArray,200);
	}
	while($row=mysqli_fetch_array($results,MYSQLI_ASSOC))
		$jsonArray[] = $row;

	response($jsonArray,200);
}





//delete news
if(isset($_POST['deleteNews'])){
	if($_POST['deleteNews']=='yes'){
	$news = $_POST['news'];
	$db->query('DELETE FROM news_table WHERE news_id="'.$_POST["news_id"].'"');
	$result['msg'] = 'success';
	response($result,200);
	
}
else{
	$result['msg'] = 'invalid news data';
	response($result,400);
}
}

//update news
if(isset($_POST['updateNews'])){
	if($_POST['updateNews'] =='yes' &&!empty($_POST['news']) && !empty($_POST['news_id'])){
	$news = $_POST['news'];
	$db->query('UPDATE news_table SET news = "'.$news.'"
	 WHERE news_id = "'.$_POST["news_id"].'"');
	$result['msg'] = 'success';
	response($result,200);
	
}
else{
	$result['msg'] = 'invalid news data';
	response($result,400);
}
}


//add news
if(isset($_POST['addNews'])){
	if($_POST['news']!='' && $_POST['news'] !='undefined'){
	$news = $_POST['news'];
	$db->query('INSERT INTO news_table (
			news
			)
		VALUES (
			"'.$_POST["news"].'"
			)');
	$result['msg'] = 'success';
	response($result,200);
	
}
else{
	$result['msg'] = 'invalid news data';
	response($result,400);
}
}

$result['msg'] = 'Forbidden';
response($result,403);




 ?>