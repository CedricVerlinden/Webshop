<?php
session_start();
include '../includes/data.inc.php';

if (isset($_SESSION["userid"])) {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/account.css">
    <link rel="stylesheet" href="../assets/css/buttons.css">
    <title>Log in - Store</title>
</head>
<body>
    <div class="container">
        <div class="signup-left">
            <h1><a href="index.php">Store</a></h1>
            <div class="content">
                <h2>Welcome back!</h2>
                <p>Log in to access your account and continue your gaming journey</p>
            </div>
            <div style="height:100px"></div>
        </div>

        <div class="signup-right">
            <div class="signup-top">
                <h1>Log in</h1>
                <p>Don't have an account yet? <a href="./register.php">Register here</a></p>
            </div>

            <div class="signup-bottom">
                <form action="../includes/login.inc.php" method="post">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>

                    <input type="submit" name="login" value="Login">
                </form>
                <?php
                if (isset($_GET["error"]) && $_GET["error"] == "wronglogin") {
                    echo '<p class="error">Incorrect email or password.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
<!-- Copyright (c) 2023 Cédric Verlinden. All rights reserved. -->