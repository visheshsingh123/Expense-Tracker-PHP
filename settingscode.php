<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$budget = $_POST['budget'];

$db = new Database();

if ($db->updateBudget($user_id, $budget)) {
    header("Location: settings.php?updated=budget");
    exit();
} else {
    echo "Something went wrong. Please try again.";
}
?>
