<?php
include('src/config.php');
include('src/classes/User.php');
include('src/classes/Artist.php');
include('src/classes/Album.php');
include('src/classes/Song.php');
include('src/classes/Playlist.php');

//session_destroy();

if (isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
	$username = $userLoggedIn->getUsername();
	echo "<script>userLoggedIn = '$username';</script>";
} else {
	header('Location: register.php');
}

?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Clonify</title>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
		<script src="assets/scripts/script.js"></script>
	</head>
	<body>
		<div class="main-container">
			<div class="top-container">
				<?php include('src/navbar.php') ?>
				<div class="main-view-container">
					<div class="main-content">