<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$db = new Database();

$total_budget = (float)$db->getBudget($user_id);
$total_spent = (float)$db->getTotalByType($user_id, 'spent');

$progress_percent = ($total_budget > 0) ? min(100, max(0, ($total_spent / $total_budget) * 100)) : 0;

$recent_expenses = $db->getRecentExpenses($user_id, 4);
$category_breakdown = $db->getCategoryBreakdown($user_id, 4);
$max_category_total = 0;
foreach ($category_breakdown as $cat) {
    if ($cat['total'] > $max_category_total) {
        $max_category_total = $cat['total'];
    }
}
?>
