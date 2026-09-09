<?php
session_start();
include 'database.php';

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$db = new Database();

if ($password != $confirm_password) {
    echo "Passwords do not match.";
} else {
    if ($db->registerUser($name, $username, $password)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>
