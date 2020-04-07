<div class="nav-container">
    <nav class="navbar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            <img src="assets/images/icons/logo.png" alt="Logo">
        </span>
        <div class="group">
            <div class="nav-item">
                <span role="link" tabindex="0" onclick="openPage('search.php')" class="nav-item-link">Search
                    <img src="assets/images/icons/search.png" alt="Search" class="icon">
                </span>
            </div>
        </div>
        <div class="group">
            <div class="nav-item">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="nav-item-link">Browse</span>
            </div>
            <div class="nav-item">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="nav-item-link">Your Music</span>
            </div>
            <div class="nav-item">
                <span role="link" tabindex="0" onclick="openPage('profile.php')" class="nav-item-link"><?php echo $userLoggedIn->getFirstAndLastName(); ?></span>
            </div>
        </div>
    </nav>
</div>