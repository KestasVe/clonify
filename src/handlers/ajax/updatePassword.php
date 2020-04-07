<?php
include('../../config.php');
if(!isset($_POST['username'])) {
    echo "ERROR: Could not set username";
    exit();
}

if(!isset($_POST['oldPasword']) || !isset($_POST['newPasword1']) || !isset($_POST['newPasword2'])) {
    echo "Not all paswwords have been set";
    exit();
}

if($_POST['oldPasword'] == "" || $_POST['newPasword1'] == "" || $_POST['newPasword2'] == "") {
    echo "Please fill in all fields";
    exit();
}

$username = $_POST['username'];
$oldPassword = htmlentities($_POST['oldPasword']);
$newPasword1 = htmlentities($_POST['newPasword1']);
$newPasword2 = htmlentities($_POST['newPasword2']);

$oldMd5 = md5($oldPassword);

$passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
if(mysqli_num_rows($passwordCheck) != 1) {
    echo "Password is incorrect";
    exit();
}

if($newPasword1 != $newPasword2) {
    echo "Your password do not match";
    exit();
}

if(preg_match('/[^A-Za-z0-9]/', $newPasword1)) {
    echo "Your password must only contain letters and/or numbers";
    exit();
}

if(strlen($newPasword1) > 30 || strlen($newPasword1) < 6) {
    echo "Your password must be between 6 and 30 characters";
    exit();
}

$newMd5 = md5($newPasword1);
$updateQuery = mysqli_query($con, "UPDATE users SET password='$newMd5' WHERE username='$username'");
echo "Update successful";