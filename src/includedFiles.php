<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include('src/config.php');
    include('src/classes/User.php');
    include('src/classes/Artist.php');
    include('src/classes/Album.php');
    include('src/classes/Song.php');
    include('src/classes/Playlist.php');

    if(isset($_GET['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    } else {
        echo "Username variable was not passed into page. Check the openPage JS function.";
        exit();
    }
} else {
    include('src/header.php');
    include('src/footer.php');

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}