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
    <link rel="stylesheet" href="frontend/index.css">
</head>
<body>
    <div class="headbars">
        <header>
            <nav>
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#">LEARN MORE</a></li>
                    <li><a href="#">CONTACT US</a></li>
                </ul>
            </nav>
            <a href="login.php" class="logo-link">LOGIN</a>
        </header>
    </div>
    <div class="main">
        <div class="container">
            <img src="pics/Logo.png" alt="logo" class="logo">
            <h1>MINDFULNESS</h1>
            <p>your guide towards a better headspace</p>
        </div>
    </div>
</body>
</html>
