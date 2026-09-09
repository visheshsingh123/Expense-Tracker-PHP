<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$type = (isset($_GET['type']) && $_GET['type'] === 'spent') ? 'spent' : 'earned';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
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
                    <a class="nav-link" href="report.php">Report</a>
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
            <h4 class="text-center mb-4 text-white">Add Expense</h4>

            <form method="POST" action="expensecode.php">
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="type" id="type_earned" value="earned" autocomplete="off" <?php echo $type === 'earned' ? 'checked' : ''; ?>>
                        <label class="btn btn-mint w-100" for="type_earned">Earned</label>

                        <input type="radio" class="btn-check" name="type" id="type_spent" value="spent" autocomplete="off" <?php echo $type === 'spent' ? 'checked' : ''; ?>>
                        <label class="btn btn-spent w-100" for="type_spent">Spent</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount (rs)</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="e.g. 500" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select bg-dark text-light" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Salary">Salary</option>
                        <option value="Business">Business</option>
                        <option value="Food">Food</option>
                        <option value="Rent">Rent</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Transport">Transport</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Optional note">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <button type="submit" class="btn btn-mint w-100 mt-2">Add Expense</button>
            </form>
        </div>
    </div>
</body>
</html>
