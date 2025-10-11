<?php
session_start();

// Redirect logged-in users directly to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mindfulness Wellness App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container text-center py-5">
    <div class="card mx-auto p-4 shadow" style="max-width: 500px;">
        <h2 class="mb-3">🧘‍♀️ Welcome to the Mindfulness Wellness App</h2>
        <p class="text-muted mb-4">
            Track your daily mindful activities and improve your well-being one task at a time.
        </p>

        <a href="login.php" class="btn btn-primary w-100 mb-2">Login</a>
        <a href="register.php" class="btn btn-outline-secondary w-100">Create New Account</a>
    </div>
</div>
</body>
</html>
