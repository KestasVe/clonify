<?php
include('../../config.php');

if(isset($_POST['name']) && $_POST['username']) {
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $date = date("Y-m-d");

    $query = mysqli_query($con, "INSERT INTO playlists VALUES ('', '$name', '$username', '$date')");
} else {
    echo "Name or username parameters was not passed into file";
}