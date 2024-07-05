<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
</head>
<body>
    <h1>Reports</h1>

    <h2>All Reminders</h2>
    <ul>
        <?php foreach ($reminders as $reminder): ?>
            <li><?php echo htmlspecialchars($reminder['subject']); ?> - <?php echo htmlspecialchars($reminder['reminder_time']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
