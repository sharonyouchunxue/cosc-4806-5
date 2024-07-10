<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #loginPieChart, #mostRemindersChart {
            max-width: 500px;
            max-height: 500px;
            margin: auto;
        }
        .nav-link {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'app/views/templates/headerPublic.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Reports</h1>

        <ul class="nav nav-pills justify-content-center mb-4" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-reminders-tab" data-toggle="pill" href="#all-reminders" role="tab" aria-controls="all-reminders" aria-selected="true">All Reminders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="most-reminders-tab" data-toggle="pill" href="#most-reminders" role="tab" aria-controls="most-reminders" aria-selected="false">User with Most Reminders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="total-logins-tab" data-toggle="pill" href="#total-logins" role="tab" aria-controls="total-logins" aria-selected="false">Total Logins by Username</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="login-pie-chart-tab" data-toggle="pill" href="#login-pie-chart" role="tab" aria-controls="login-pie-chart" aria-selected="false">Login Attempts Pie Chart</a>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">
            <div class="tab-pane fade show active" id="all-reminders" role="tabpanel" aria-labelledby="all-reminders-tab">
                <div id="remindersAccordion">
                    <?php foreach ($data['reminders'] as $index => $reminder): ?>
                    <div class="card">
                        <div class="card-header" id="reminderHeading-<?php echo $index; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#reminderCollapse-<?php echo $index; ?>" aria-expanded="true" aria-controls="reminderCollapse-<?php echo $index; ?>">
                                    <?php echo htmlspecialchars($reminder['subject']); ?>
                                    -
                                    <?php echo htmlspecialchars($reminder['reminder_time']); ?>
                                </button>
                            </h5>
                        </div>

                        <div id="reminderCollapse-<?php echo $index; ?>" class="collapse" aria-labelledby="reminderHeading-<?php echo $index; ?>" data-parent="#remindersAccordion">
                            <div class="card-body">
                                <p>
                                    <strong>Created on:</strong>
                                    <?php echo htmlspecialchars($reminder['create_at']); ?>
                                </p>
                                <p>
                                    <strong>Reminder Time:</strong>
                                    <?php echo htmlspecialchars($reminder['reminder_time']); ?>
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    <?php echo $reminder['completed'] ? 'Completed' : 'Not Completed'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="most-reminders" role="tabpanel" aria-labelledby="most-reminders-tab">
                <div id="mostRemindersUserAccordion">
                    <?php if ($data['mostRemindersUser']): ?>
                    <div class="card">
                        <div class="card-header" id="mostRemindersUserHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#mostRemindersUserCollapse" aria-expanded="true" aria-controls="mostRemindersUserCollapse">
                                    <?php echo htmlspecialchars($data['mostRemindersUser']['username']) . " with " . $data['mostRemindersUser']['reminder_count'] . " reminders"; ?>
                                </button>
                            </h5>
                        </div>

                        <div id="mostRemindersUserCollapse" class="collapse show" aria-labelledby="mostRemindersUserHeading" data-parent="#mostRemindersUserAccordion">
                            <div class="card-body">
                                <h3>
                                    Reminders for
                                    <?php echo htmlspecialchars($data['mostRemindersUser']['username']); ?>
                                </h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Reminder Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['userReminders'] as $reminder): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reminder['subject']); ?></td>
                                            <td><?php echo htmlspecialchars($reminder['reminder_time']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <p>No reminders found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="total-logins" role="tabpanel" aria-labelledby="total-logins-tab">
                <div id="loginsAccordion">
                    <?php foreach ($data['totalLogins'] as $index => $login): ?>
                    <div class="card">
                        <div class="card-header" id="loginHeading-<?php echo $index; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#loginCollapse-<?php echo $index; ?>" aria-expanded="true" aria-controls="loginCollapse-<?php echo $index; ?>">
                                    <?php echo htmlspecialchars($login['username']) . ": " . $login['total_logins'] . " logins"; ?>
                                </button>
                            </h5>
                        </div>

                        <div id="loginCollapse-<?php echo $index; ?>" class="collapse" aria-labelledby="loginHeading-<?php echo $index; ?>" data-parent="#loginsAccordion">
                            <div class="card-body">
                                <p>
                                    <strong>Total Logins:</strong>
                                    <?php echo $login['total_logins']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="login-pie-chart" role="tabpanel" aria-labelledby="login-pie-chart-tab">
                <div class="card">
                    <div class="card-body">
                        <h2>Login Attempts Donut Chart</h2>
                        <canvas id="loginPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function initializePieChart() {
            const ctx = document.getElementById('loginPieChart').getContext('2d');
            const loginPieChart = new Chart(ctx, {
                type: 'doughnut',
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
        }

        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            if (e.target.id === 'login-pie-chart-tab') {
                if (!$('#loginPieChart').hasClass('initialized')) {
                    initializePieChart();
                    $('#loginPieChart').addClass('initialized');
                }
            }
        });
    </script>
</body>
</html>
