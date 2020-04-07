<?php
    include('src/includedFiles.php');
?>

<div class="entity-info">
    <div class="center-section">
        <div class="user-info">
            <h1><?php echo $userLoggedIn->getFirstAndLastName(); ?></h1>
        </div>
    </div>
    <div class="button-items">
        <button class="button" onclick="openPage('updateDetails.php')">USER DETAILS</button>
        <button class="button" onclick="logout()">LOGOUT</button>
    </div>
</div>