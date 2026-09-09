<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="login.php">ExpenseTracker</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 56px);">
        <div class="card p-5 shadow-sm" style="width: 400px;">
            <h2 class="text-center mb-4">Register</h2>

            <form method="POST" action="registercode.php">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-mint w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
