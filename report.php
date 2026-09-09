<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$db = new Database();

$category_data = $db->getCategoryBreakdown($user_id, 50);
$category_labels = array_map(function ($row) { return $row['category']; }, $category_data);
$category_values = array_map(function ($row) { return (float)$row['total']; }, $category_data);

$today = new DateTime();
$week_labels = [];
$week_values = [];
$start_date = (clone $today)->modify('-6 days')->format('Y-m-d');
$end_date = $today->format('Y-m-d');
$daily_totals = $db->getSpendingByDateRange($user_id, $start_date, $end_date);

for ($i = 6; $i >= 0; $i--) {
    $day = (clone $today)->modify("-$i days");
    $key = $day->format('Y-m-d');
    $week_labels[] = $day->format('D j M');
    $week_values[] = isset($daily_totals[$key]) ? $daily_totals[$key] : 0;
}

$all_expenses = $db->getAllExpenses($user_id);

$palette = ['#3bd085', '#f43e3e', '#4dabf7', '#ffa94d', '#9775fa', '#22b8cf', '#f783ac', '#c0eb75', '#ff8787', '#748ffc'];
$category_colors = [];
foreach ($category_labels as $i => $label) {
    $category_colors[] = $palette[$i % count($palette)];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

    <div class="container-fluid px-4 py-4">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card p-4" style="min-height: 300px;">
                    <h6 class="mb-3 text-white">Spending by Category</h6>
                    <?php if (empty($category_labels)): ?>
                        <div class="d-flex justify-content-center align-items-center text-muted-dashboard" style="height: 220px;">
                            No spending recorded yet
                        </div>
                    <?php else: ?>
                        <div style="height: 240px;" class="d-flex align-items-center">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4" style="min-height: 300px;">
                    <h6 class="mb-3 text-white">Expenses Over Last 7 Days</h6>
                    <div style="height: 240px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card p-4">
                    <h6 class="mb-3 text-white">All Expense Data</h6>
                    <?php if (empty($all_expenses)): ?>
                        <div class="d-flex justify-content-center align-items-center text-muted-dashboard" style="height: 140px;">
                            No expenses recorded yet
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark-custom align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_expenses as $exp): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($exp['expense_date']); ?></td>
                                            <td>
                                                <span class="<?php echo $exp['type'] === 'earned' ? 'badge-earned' : 'badge-spent'; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($exp['type'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($exp['category']); ?></td>
                                            <td><?php echo $exp['description'] !== '' ? htmlspecialchars($exp['description']) : '<span class="text-muted-dashboard">—</span>'; ?></td>
                                            <td><?php echo number_format($exp['amount'], 2); ?> rs</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="expenseedit.php?id=<?php echo (int)$exp['expense_id']; ?>" class="btn btn-mint btn-sm">Edit</a>
                                                    <form method="POST" action="expensedeletecode.php" onsubmit="return confirm('Delete this expense? This cannot be undone.');">
                                                        <input type="hidden" name="expense_id" value="<?php echo (int)$exp['expense_id']; ?>">
                                                        <button type="submit" class="btn btn-spent btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const categoryLabels = <?php echo json_encode($category_labels); ?>;
        const categoryValues = <?php echo json_encode($category_values); ?>;
        const categoryColors = <?php echo json_encode($category_colors); ?>;
        const weekLabels = <?php echo json_encode($week_labels); ?>;
        const weekValues = <?php echo json_encode($week_values); ?>;

        Chart.defaults.color = '#a0a6b0';
        Chart.defaults.font.family = "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif";

        if (categoryLabels.length > 0) {
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: categoryLabels.map((label, i) => ({
                        label: label,
                        data: [categoryValues[i]],
                        backgroundColor: categoryColors[i],
                        borderRadius: 4,
                        maxBarThickness: 60
                    }))
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 14 }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.x.toFixed(2)} rs`
                            }
                        }
                    },
                    scales: {
                        x: { stacked: true, beginAtZero: true, grid: { display: false } },
                        y: { stacked: true, grid: { display: false }, ticks: { display: false } }
                    }
                }
            });
        }

        new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Spent (rs)',
                    data: weekValues,
                    backgroundColor: '#f43e3e',
                    borderRadius: 6,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#333a40' } }
                }
            }
        });
    </script>
</body>
</html>
