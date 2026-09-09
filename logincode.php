<?php
session_start();
include 'database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$db = new Database();
$user = $db->getUserByUsername($username);

if ($user) {
    if ($password == $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "User not found.";
}
?>
