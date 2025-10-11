<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("
    SELECT u.full_name, u.username, r.role_name
    FROM users u
    JOIN user_roles r ON u.role_id = r.role_id
    WHERE u.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Mindfulness Wellness App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card p-4 shadow-sm">
        <h3 class="mb-3">Welcome back, <?= htmlspecialchars($user['full_name'] ?? $user['username']) ?> 👋</h3>
        <p class="text-muted">Role: <strong><?= htmlspecialchars($user['role_name']) ?></strong></p>

        <hr>

        <h5>Your Recent Mindfulness Activities</h5>

        <?php
        $activities = $mysqli->prepare("
            SELECT a.activity_name, s.sub_task_name, a.schedule_datetime, a.is_done
            FROM activities a
            JOIN sub_tasks s ON a.sub_task_id = s.sub_task_id
            WHERE a.user_id = ?
            ORDER BY a.schedule_datetime DESC
            LIMIT 5
        ");
        $activities->bind_param("i", $user_id);
        $activities->execute();
        $result = $activities->get_result();

        if ($result->num_rows > 0): ?>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Sub-Task</th>
                        <th>Activity</th>
                        <th>Schedule</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['sub_task_name']) ?></td>
                            <td><?= htmlspecialchars($row['activity_name']) ?></td>
                            <td><?= date("M d, Y h:i A", strtotime($row['schedule_datetime'])) ?></td>
                            <td><?= $row['is_done'] ? '✅ Done' : '⏳ Pending' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mt-3">No activities found yet. Start by scheduling your first task!</p>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-outline-danger mt-3">Logout</a>
    </div>
</div>
</body>
</html>
