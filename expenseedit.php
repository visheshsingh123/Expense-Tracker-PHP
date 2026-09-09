<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$expense_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$db = new Database();
$expense = $db->getExpenseById($user_id, $expense_id);

if (!$expense) {
    header("Location: report.php");
    exit();
}

$categories = ['Salary', 'Business', 'Food', 'Rent', 'Utilities', 'Transport', 'Shopping', 'Entertainment', 'Health', 'Education', 'Other'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fs-5" href="dashboard.php">ExpenseTracker</a>
            <ul class="navbar-nav ms-auto flex-row gap-4">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="report.php">Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="p-5 d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 80px);">
        <div class="card p-5 shadow-sm" style="width: 420px;">
            <h4 class="text-center mb-4 text-white">Edit Expense</h4>

            <form method="POST" action="expenseeditcode.php">
                <input type="hidden" name="expense_id" value="<?php echo (int)$expense['expense_id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="type" id="type_earned" value="earned" autocomplete="off" <?php echo $expense['type'] === 'earned' ? 'checked' : ''; ?>>
                        <label class="btn btn-mint w-100" for="type_earned">Earned</label>

                        <input type="radio" class="btn-check" name="type" id="type_spent" value="spent" autocomplete="off" <?php echo $expense['type'] === 'spent' ? 'checked' : ''; ?>>
                        <label class="btn btn-spent w-100" for="type_spent">Spent</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount (rs)</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" value="<?php echo htmlspecialchars($expense['amount']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select bg-dark text-light" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $expense['category'] === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Optional note" value="<?php echo htmlspecialchars($expense['description']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-control" value="<?php echo htmlspecialchars($expense['expense_date']); ?>" required>
                </div>

                <button type="submit" class="btn btn-mint w-100 mt-2">Save Changes</button>
                <a href="report.php" class="btn btn-outline-light w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
