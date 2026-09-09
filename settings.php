<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$db = new Database();
$currentBudget = $db->getBudget($user_id);
$user = $db->getUserById($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
                    <a class="nav-link active" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex justify-content-center align-items-start py-5" style="min-height: calc(100vh - 80px);">
        <div class="d-flex flex-row flex-wrap justify-content-center gap-4" style="max-width: 900px;">

            <div style="width: 420px;">
                <div class="card p-5 shadow-sm">
                    <h4 class="text-center mb-4 text-white">Account Details</h4>

                    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'profile'): ?>
                        <p class="text-center mb-3" style="color: var(--mint);">Profile updated successfully.</p>
                    <?php endif; ?>

                    <form method="POST" action="accountcode.php">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                        </div>

                        <button type="submit" class="btn btn-mint w-100 mt-2">Save Changes</button>
                    </form>
                </div>
            </div>

            <div style="width: 420px;">
                <div class="card p-5 shadow-sm">
                    <h4 class="text-center mb-4 text-white">Budget</h4>

                    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'budget'): ?>
                        <p class="text-center mb-3" style="color: var(--mint);">Balance updated successfully.</p>
                    <?php endif; ?>

                    <form method="POST" action="settingscode.php">
                        <div class="mb-3">
                            <label class="form-label">Current Balance (rs)</label>
                            <input type="number" step="0.01" min="0" name="budget" class="form-control" value="<?php echo htmlspecialchars($currentBudget); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-mint w-100 mt-2">Save Changes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
