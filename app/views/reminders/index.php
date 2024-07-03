<?php require_once 'app/views/templates/header.php'; ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>List of Reminders</h1>
            </div>
        </div>
    </div>

    <!-- Display success message for reminder creation or update -->
    <?php
    session_start();
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" id="success-message">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    } else {
        echo '<div class="alert alert-success" id="success-message" style="display: none;"></div>';
    }
    ?>

    <!-- Reminders List Container -->
    <div class="row">
        <div class="col-lg-12 create-container">
            <?php $counter = 1; ?>
            <?php foreach ($data['reminders'] as $reminder): ?>
                <div class="reminder-item" id="reminder-<?php echo $reminder['id']; ?>">
                    <p class="info-display info-display-bold"><?php echo $counter . '. ' . htmlspecialchars($reminder['subject']); ?></p>
                    <p class="info-display">Created on: <?php echo htmlspecialchars($reminder['create_at']); ?></p>
                    <p class="info-display">Reminder Time: <?php echo htmlspecialchars($reminder['reminder_time']); ?></p>
                    <p class="info-display completion-status" id="status-<?php echo $reminder['id']; ?>">
                        Status: <?php echo $reminder['completed'] ? 'Completed' : 'Not Completed'; ?>
                    </p>
                    <!-- Update Form -->
                    <form class="update-form" method="POST" action="/reminders/update/<?php echo $reminder['id']; ?>" data-id="<?php echo $reminder['id']; ?>" style="display: inline;">
                        <input type="text" name="subject" value="<?php echo htmlspecialchars($reminder['subject']); ?>" required>
                        <label for="date-<?php echo $reminder['id']; ?>">Date</label>
                        <input type="date" id="date-<?php echo $reminder['id']; ?>" name="date" value="<?php echo date('Y-m-d', strtotime($reminder['reminder_time'])); ?>" required>
                        <label for="time-<?php echo $reminder['id']; ?>">Time</label>
                        <input type="time" id="time-<?php echo $reminder['id']; ?>" name="time" value="<?php echo date('H:i', strtotime($reminder['reminder_time'])); ?>" required>
                        <label for="completed-<?php echo $reminder['id']; ?>">Completed</label>
                        <input type="checkbox" id="completed-<?php echo $reminder['id']; ?>" name="completed" value="1" <?php echo $reminder['completed'] ? 'checked' : ''; ?>>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </form>
                    <!-- Delete Form -->
                    <form method="POST" action="/reminders/delete/<?php echo $reminder['id']; ?>" style="display: inline;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
                <?php $counter++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- jQuery for AJAX requests -->
<script src="c:/xampp/htdocs/COSC4806/app/views/js/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.update-form').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this);
        let id = $form.data('id');
        let formData = $form.serialize();

        $.ajax({
            type: 'POST',
            url: '/reminders/update/' + id,
            data: formData,
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success) {
                    $('#success-message').text(res.message).show();
                    setTimeout(function() {
                        $('#success-message').fadeOut();
                    }, 3000);

                    //update the reminder item in the user interface
                    $form.closest('.reminder-item').find('p').first().text($form.find('input[name="subject"]').val());

                    // Update the completion status
                    let status = $form.find('input[name="completed"]').is(':checked') ? 'Status: Completed' : 'Status: Not Completed';
                    $('#status-' + id).text(status);
                }
            }
        });
    });
});
</script>

<?php require_once 'app/views/templates/footer.php'; ?>
