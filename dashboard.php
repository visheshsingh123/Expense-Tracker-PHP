<?php
include 'dashboardcode.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseTracker</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fs-5" href="dashboard.php">ExpenseTracker</a>
            <ul class="navbar-nav ms-auto flex-row gap-4">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">Home</a>
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

    <div class="container-fluid px-4 py-4">
        <h4 class="text-white mb-4">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></h4>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-4 h-100 d-flex flex-column">
                    <h6 class="mb-3 text-white">Add Expense</h6>
                    <div class="d-flex flex-column gap-2 align-items-center justify-content-center flex-grow-1" style="max-width: 220px; margin: 0 auto; width: 100%;">
                        <a href="addexpense.php?type=earned" class="btn btn-mint w-100">Earned</a>
                        <a href="addexpense.php?type=spent" class="btn btn-spent w-100">Spent</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-3 text-white">Expense Remaining</h6>
                        <h3 class="fw-bold mb-3 text-white">
                            <?php echo number_format($total_spent, 0, '.', ''); ?>/<?php echo number_format($total_budget, 0, '.', ''); ?> rs
                        </h3>
                    </div>
                    <div class="progress-mint mt-auto">
                        <div class="progress-mint-bar" style="width: <?php echo number_format($progress_percent, 1, '.', ''); ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4 h-100" style="min-height: 250px;">
                    <h6 class="mb-3 text-white">Recent Expenses</h6>
                    <?php if (empty($recent_expenses)): ?>
                        <div class="d-flex justify-content-center align-items-center text-muted-dashboard" style="height: 140px;">
                            *budget table in tabular form*
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark-custom align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_expenses as $exp): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($exp['expense_date']); ?></td>
                                            <td>
                                                <span class="<?php echo $exp['type'] === 'earned' ? 'badge-earned' : 'badge-spent'; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($exp['type'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($exp['category']); ?></td>
                                            <td><?php echo number_format($exp['amount'], 2); ?> rs</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4 h-100" style="min-height: 250px;">
                    <h6 class="mb-3 text-white">Top Spending Categories</h6>
                    <?php if (empty($category_breakdown)): ?>
                        <div class="d-flex justify-content-center align-items-center text-muted-dashboard" style="height: 140px;">
                            No spending recorded yet
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($category_breakdown as $cat):
                                $pct = $max_category_total > 0 ? ($cat['total'] / $max_category_total) * 100 : 0;
                            ?>
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-white"><?php echo htmlspecialchars($cat['category']); ?></span>
                                        <span class="text-secondary"><?php echo number_format($cat['total'], 2); ?> rs</span>
                                    </div>
                                    <div class="category-bar">
                                        <div class="category-bar-fill" style="width: <?php echo number_format($pct, 1, '.', ''); ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
