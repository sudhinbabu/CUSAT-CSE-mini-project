<?php 
session_start();
require_once 'lib/functions.php';
require_once 'lib/db_class.php';

//check if login form is submitted
if(isset($_POST['login'])){
	require 'lib/validation_class.php';
	$security =  new validation();
	//check user type.

		$result = $security->validate_credentials_with_db($_POST);
		if(!$result['error']){
			$_SESSION['user_type'] = $result['user_type'];
			$_SESSION['user_id'] = $result['user_id'];
			$_SESSION['logged_in'] = TRUE;
			$location = ($result['user_type']=='admin') ? 'admin/home.php' : 'applicant/home.php';
			unset($_POST);
			header('location: '.$location);
			exit();
		}


	$_SESSION['login_error'] = 'invalid username or password';
}

include 'templates/header.php';
?>

<div class="container main-content">
	<div class="content">
		<div class="banner">
			<h2 class="heading">news</h2>
			<aside>
				<ul class="news">
					<?php 
					$db =  new Database();
					$result =  $db->query('SELECT news FROM news_table');
					while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
						echo "<li>".$row['news']."</li>";
					?>

				</ul>
			</aside>

		</div>
	</div>
	<div class="side-bar">
		<span class="success-msg">
			<?php 
			//show messsage if session contains a message
			if(isset($_SESSION["msg_register"]))
				echo $_SESSION['msg_register'];
				unset($_SESSION['msg_register']);
			?>
		</span>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="signin-form"  method="post">
			<table>
				<tr>
					<td><label for=""><h3>LOGIN</h3></label></td>
					
				</tr>
				<tr>
				
					<td><input placeholder="username" class="form-control" name="username" type="text"></td>
				</tr>
				<tr>
					
					<td><input placeholder="password" class="form-control" name="password" type="text"></td>
				</tr>
				<tr>
					
					<td><input type="submit" class="btn btn-success" name="login" value='login'>

						<?php 
			//show messsage if session contains a message
			if(isset($_SESSION['login_error']))
				echo '<span class="input-error-msg">'.$_SESSION['login_error'].'</span>';
			unset($_SESSION['login_error']);
			?>


					</td>
				</tr>
			</table>
		</form>
	</div>
	
</div>

<?php include 'templates/footer.php';
?>
</body>
</html>