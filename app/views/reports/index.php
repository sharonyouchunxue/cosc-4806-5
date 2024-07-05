<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #loginPieChart {
            max-width: 500px;
            max-height: 500px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1>Reports</h1>

    <h2>All Reminders</h2>
    <ul>
        <?php foreach ($data['reminders'] as $reminder): ?>
            <li><?php echo htmlspecialchars($reminder['subject']); ?> - <?php echo htmlspecialchars($reminder['reminder_time']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>User with Most Reminders</h2>
    <?php if ($data['mostRemindersUser']): ?>
        <p><?php echo htmlspecialchars($data['mostRemindersUser']['username']) . " with " . $data['mostRemindersUser']['reminder_count'] . " reminders"; ?></p>

        <h3>Reminders for <?php echo htmlspecialchars($data['mostRemindersUser']['username']); ?></h3>
        <ul>
            <?php foreach ($data['userReminders'] as $reminder): ?>
                <li><?php echo htmlspecialchars($reminder['subject']); ?> - <?php echo htmlspecialchars($reminder['reminder_time']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reminders found.</p>
    <?php endif; ?>

    <h2>Total Logins by Username</h2>
    <ul>
        <?php foreach ($data['totalLogins'] as $login): ?>
            <li><?php echo htmlspecialchars($login['username']) . ": " . $login['total_logins'] . " logins"; ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Pie Chart for login counts by Users -->
    <h2>Login Attempts Pie Chart</h2>
    <canvas id="loginPieChart"></canvas>
    <script>
        const ctx = document.getElementById('loginPieChart').getContext('2d');
        const loginPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($data['totalLogins'], 'username')); ?>,
                datasets: [{
                    label: 'Total Logins',
                    data: <?php echo json_encode(array_column($data['totalLogins'], 'total_logins')); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
