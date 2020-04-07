<?php

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['loginUsername']);
    $password = htmlspecialchars($_POST['loginPassword']);

    $result = $account->login($username, $password);

    if($result) {
        $_SESSION['userLoggedIn'] = $username;
        header('Location: index.php');
    }
}