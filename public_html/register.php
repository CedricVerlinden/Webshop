<?php
session_start();
include '../includes/data.inc.php';

if (isset($_SESSION["userid"])) {
    header("Location: ./index.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/account.css">
    <title>Register - Store</title>
</head>
<body>
    <div class="container">
        <div class="signup-left">
            <h1><a href="./index.php">Store</a></h1>
            <div class="content">
                <h2>Start your journey with us.</h2>
                <p>Discover the world's best games, components, and peripherals</p>
            </div>
            <div style="height:100px"></div>
        </div>

        <div class="signup-right">
            <div class="signup-top">
                <h1>Sign up</h1>
                <p>Have an account? <a href="./login.php">Sign in</a></p>
            </div>

            <div class="signup-bottom">
                <form action="../includes/register.inc.php" method="post">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <label for="repeatpassword">Repeat password</label>
                    <input type="password" name="repeatpassword" id="repeatpassword" required>

                    <input type="submit" name="create-account" value="Create account">
                </form>

                <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "emailalreadyinuse") {
                        echo '<p class="error">That email is already being used by another account.</p>';
                    }
                    
                    if ($_GET["error"] == "passwordsdontmatch") {
                        echo '<p class="error">Your passwords do not match.</p>';
                    }
                    
                    if ($_GET["error"] == "sqlerror") {
                        echo '<p class="error">Internal SQL error. Contact an administrator.</p>';
                    }
                }

                if (isset($_GET["success"]) && $_GET["success"] == "accountcreated") {
                    echo '<p class="success">Your account has been created. You can now sign in.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
<!-- Copyright (c) 2023 Cédric Verlinden. All rights reserved. -->