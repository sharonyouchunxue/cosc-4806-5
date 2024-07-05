<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Reports</h1>

    <h2>All Reminders</h2>
    <ul>
        <?php foreach ($reminders as $reminder): ?>
            <li><?php echo htmlspecialchars($reminder['subject']); ?> - <?php echo htmlspecialchars($reminder['reminder_time']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>User with Most Reminders</h2>
    <p><?php echo htmlspecialchars($mostRemindersUser['username']) . " with " . $mostRemindersUser['reminder_count'] . " reminders"; ?></p>

    <h2>Total Logins by Username</h2>
    <ul>
        <?php foreach ($loginCounts as $login): ?>
            <li><?php echo htmlspecialchars($login['username']) . ": " . $login['login_count'] . " logins"; ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Chart for visualization -->
    <canvas id="loginChart"></canvas>
    <script>
        const ctx = document.getElementById('loginChart').getContext('2d');
        const loginChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($loginCounts, 'username')); ?>,
                datasets: [{
                    label: 'Total Logins',
                    data: <?php echo json_encode(array_column($loginCounts, 'login_count')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
