<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$type = $_POST['type'];
$amount = floatval($_POST['amount']);
$category = $_POST['category'];
$description = $_POST['description'];
$expense_date = $_POST['expense_date'];

$db = new Database();

if ($db->addExpense($user_id, $type, $amount, $category, $description, $expense_date)) {
    if ($type === 'earned') {
        $db->adjustBudget($user_id, $amount);
    }

    header("Location: dashboard.php");
    exit();
} else {
    echo "Something went wrong. Please try again.";
}
?>
