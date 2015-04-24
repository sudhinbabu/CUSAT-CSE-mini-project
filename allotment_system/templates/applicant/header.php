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
		<span class="logout"><a href="<?php echo $_SERVER['PHP_SELF'].'/?logout=yes'; ?>">logout</a></span>
		<nav class="header-nav-menu">
			<ul>
				<li><a class="<?=(file_name()=='home.php'?'active': '') ?>"href="<?php echo BASE_URL.'/applicant/home.php'; ?>">Home</a>
			</ul>
		</nav>

	</header>
