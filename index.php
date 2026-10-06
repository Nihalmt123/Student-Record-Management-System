<!DOCTYPE html>
<html>

<head>
	<title>Student information system</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
	<header>
		<div class="main">
			<ul>
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#">Departments</a></li>
				<li><a href="#">Admission</a></li>
				<li><a href="#">Contact</a></li>
				<li><a href="#">About</a></li>
			</ul>
		</div>
		<div class="title">
			<h1>Student Information System</h1>
		</div>
		<div class="button">
			<a href="page.php" class="btn">Student login</a>
			<a href="login.php" class="btn">Admin login</a>

		</div>
	</header>

</body>

</html>

<?php
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
	header('Location: admin/admindash.php');
	exit();
}

// Redirect to login page
header('Location: login.php');
exit();
?>