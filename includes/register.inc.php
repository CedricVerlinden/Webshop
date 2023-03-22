<?php
include './connect.inc.php';

if (!(isset($_POST["create-account"]))) {
    header("Location: ../public_html/register.php");
    return;
}

$email = $_POST["email"];
$pwd = $_POST["password"];
$pwdRepeat = $_POST["repeatpassword"];

// Check if email is already in use
if (emailExists($email) !== false) {
    header("Location: ../public_html/register.php?error=emailalreadyinuse");
    exit();
}

// Check if passwords match
if (pwdMatch($pwd, $pwdRepeat) !== false) {
    header("Location: ../public_html/register.php?error=passwordsdontmatch");
    exit();
}

createAccount($email, $pwd);


function emailExists($email) {
    global $connection;

    $sql = "SELECT * FROM users WHERE email=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        header("Location: ../public_html/register.php?error=sqlerror");
        die("Error: " . $connection->error);
    }

    $statement->bind_param("s", $email);
    $statement->execute();

    $resultSet = $statement->get_result();
    if ($row = $resultSet->fetch_assoc()) {
        return $row;
    }

    $statement->close();
    return false;
}

function pwdMatch($pwd, $pwdRepeat) {
    if ($pwd !== $pwdRepeat) {
        return true;
    }

    return false;
}

function createAccount($email, $password) {
    global $connection;

    $sql = "INSERT INTO users(email, password, admin) VALUES(?, ?, 0)";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        header("Location: ../public_html/register.php?error=sqlerror");
        die("Error: " . $connection->error);
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $statement->bind_param("ss", $email, $hashedPassword);
    $statement->execute();

    header("Location: ../public_html/register.php?success=accountcreated");
    exit();
}
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */