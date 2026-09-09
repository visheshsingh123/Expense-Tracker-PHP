<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$expense_id = isset($_POST['expense_id']) ? (int)$_POST['expense_id'] : 0;

$db = new Database();
$old = $db->getExpenseById($user_id, $expense_id);

if (!$old) {
    header("Location: report.php");
    exit();
}

$type = $_POST['type'];
$amount = floatval($_POST['amount']);
$category = $_POST['category'];
$description = $_POST['description'];
$expense_date = $_POST['expense_date'];

if ($db->updateExpense($user_id, $expense_id, $type, $amount, $category, $description, $expense_date)) {
    $old_contribution = ($old['type'] === 'earned') ? (float)$old['amount'] : 0;
    $new_contribution = ($type === 'earned') ? $amount : 0;
    $delta = $new_contribution - $old_contribution;

    if ($delta != 0) {
        $db->adjustBudget($user_id, $delta);
    }

    header("Location: report.php");
    exit();
} else {
    echo "Something went wrong. Please try again.";
}
?>
