<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $user_id = $_SESSION['user_id'];
    $budget = floatval($_POST['budget']);

    if ($budget > 0) {
        $db = new Database();
        $db->updateBudget($user_id, $budget);
    }
}

header("Location: dashboard.php");
exit();
?>
