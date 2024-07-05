<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
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
</body>
</html>
