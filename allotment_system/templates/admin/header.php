<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Allotment system</title>
	<link rel="stylesheet" href="<?php echo BASE_URL.'/css/bootstrap/css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL.'/css/style.css'; ?>">
</head>
<body>
	<header class="container">

		<h1 class="heading text-align-center">Online allotment system</h1>
		<h2 class="heading text-align-center">College of engineering kottarakkara</h2>
		
		<span class="logout"><h3> Admin</h3><a href="<?php echo $_SERVER['PHP_SELF'].'/?logout=yes'; ?>">logout</a></span>
			
			<!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
				<label for="logoutSubmit"><span class="logout">logout</span></label>
				<input  type="submit" id="logoutSubmit" style="display: none;" name="logout" value="logout">

			</form> -->
		<nav class="header-nav-menu">
			<ul>
				<li><a class="<?=(file_name()=='home.php'?'active': '') ?>"href="<?php echo BASE_URL.'/admin/home.php'; ?>">Home</a>
				<li><a class="<?=(file_name()=='courses.php'?'active': '') ?>" href="courses.php">Courses</a></li>
				<li><a class="<?=(file_name()=='religion.php'?'active':'') ?>"href="religion.php">Relegions</a></li>
				<li><a class="<?=(file_name()=='allotments.php'?'active': '') ?>"href="allotments.php">Allotments</a></li>
				<li><a class="<?=(file_name()=='applications.php'?'active': '') ?>"href="applications.php">Applications</a></li>
			</ul>
		</nav>

	</header>
