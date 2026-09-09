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
$expense = $db->getExpenseById($user_id, $expense_id);

if ($expense) {
    if ($db->deleteExpense($user_id, $expense_id)) {
        if ($expense['type'] === 'earned') {
            $db->adjustBudget($user_id, -(float)$expense['amount']);
        }
    }
}

header("Location: report.php");
exit();
?>
