<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];

$db = new Database();

if ($db->updateProfile($user_id, $name, $username)) {
    if ($password !== '') {
        $db->updatePassword($user_id, $password);
    }

    $_SESSION['name'] = $name;
    $_SESSION['username'] = $username;

    header("Location: settings.php?updated=profile");
    exit();
} else {
    echo "Something went wrong. Please try again.";
}
?>
