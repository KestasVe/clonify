<?php
    include('src/config.php');
    include('src/classes/Account.php');
    include('src/classes/Constants.php');

    $account = new Account($con);

    include('src/handlers/login-handler.php');
    include('src/handlers/register-handler.php');

    function getInputValue($name) {
        if (isset($_POST[$name])) {
            echo sanitizeFormUsername($_POST[$name]);
        }
    }

?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Clonify</title>
        <link rel="stylesheet" type="text/css" href="assets/css/register.css"></link>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>   
	</head>
	<body>
        <?php

            if(isset($_POST['register'])) {
                echo "<script>
                    $(document).ready(function() {
                        $('.login-form').hide();
                        $('.register-form').show();
                    })
                </script>";
            } else {
                echo "<script>
                    $(document).ready(function() {
                        $('.login-form').show();
                        $('.register-form').hide();
                    })
                </script>";
            }

        ?>
        <div class="background">
            <div class="login-container">
                <div class="form-container">
                    <form class="login-form" action="register.php" method="POST">
                        <h2>Login to your account</h2>
                        <div class="login-input">
                            <?php echo $account->getError(Constants::$loginFailed) ?>
                            <label for="loginUsername">Username</label>
                            <input id="loginUsername" name="loginUsername" placeholder="e.g. Ninja" type="text" value="<?php getInputValue('loginUsername') ?>" required>
                        </div>
                        <div class="login-input">
                            <label for="loginPassword">Password</label>
                            <input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
                        </div>
                        <button type="submit" name="login">LOG IN</button>
                        <div class="has-account">
                            <span class="hide-login">Don't have account yet? Signup here.</span>
                        </div>
                    </form>

                    <form class="register-form" action="register.php" method="POST">
                        <h2>Create your free account</h2>
                        <div class="register-input">
                            <?php echo $account->getError(Constants::$usernameNameCharacters) ?>
                            <?php echo $account->getError(Constants::$usernameTaken) ?>
                            <label for="username">Username</label>
                            <input id="username" name="username" placeholder="e.g. Ninja" type="text" value="<?php getInputValue('username') ?>" required>
                        </div>
                        <div class="register-input">
                            <?php echo $account->getError(Constants::$firstNameCharacters) ?>
                            <label for="firstName">First name</label>
                            <input id="firstName" name="firstName" placeholder="e.g. John" type="text" value="<?php getInputValue('firstName') ?>" required>
                        </div>
                        <div class="register-input">
                            <?php echo $account->getError(Constants::$lastNameCharacters) ?>
                            <label for="lastName">Last name</label>
                            <input id="lastName" name="lastName" placeholder="e.g. Doe" type="text" value="<?php getInputValue('lastName') ?>" required>
                        </div>
                        <div class="register-input">
                            <?php echo $account->getError(Constants::$emailsDoNotMatch) ?>
                            <?php echo $account->getError(Constants::$emailInvalid) ?>
                            <?php echo $account->getError(Constants::$emailTaken) ?>
                            <label for="email">Email</label>
                            <input id="email" name="email" placeholder="e.g. john@gmail.com" type="email" value="<?php getInputValue('email') ?>" required>
                        </div>
                        <div class="register-input">
                            <label for="email2">Confirm email</label>
                            <input id="email2" name="email2" placeholder="e.g. john@gmail.com" type="email" value="<?php getInputValue('email2') ?>" required>
                        </div>
                        <div class="register-input">
                            <?php echo $account->getError(Constants::$passwordsDoNotMatch) ?>
                            <?php echo $account->getError(Constants::$passwordsNotAlphanumeric) ?>
                            <?php echo $account->getError(Constants::$passwordCharacters) ?>
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" placeholder="Your password" required>
                        </div>
                        <div class="register-input">
                            <label for="password2">Confirm password</label>
                            <input id="password2" name="password2" type="password" placeholder="Your password" required>
                        </div>
                        <button type="submit" name="register">SIGN UP</button>
                        <div class="has-account">
                            <span class="hide-register">Already have an account? Login here.</span>
                        </div>
                    </form>
                </div>
                <div class="login-text">
                    <h1>Get great music, right now</h1>
                    <h2>Listen to loads of songs for free</h2>
                    <ul>
                        <li>Discover music you'll fall in love with</li>
                        <li>Create your own playlists</li>
                        <li>Follow artists to keep up to date</li>
                    </ul>
                </div>
            </div> 
        </div>
        <script src="assets/scripts/register.js"></script>
	</body>
</html>