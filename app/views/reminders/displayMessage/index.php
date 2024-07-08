<?php require_once 'app/views/templates/header.php'; ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Message Display</h1>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="messageToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <small class="text-muted">just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php if (isset($data['message'])): ?>
                    <?php echo htmlspecialchars($data['message']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Show toast if there is a message
        <?php if (isset($data['message'])): ?>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('messageToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
        <?php endif; ?>

        // Redirect to the reminders list after 3 seconds
        setTimeout(function() {
            window.location.href = '/reminders';
        }, 3000);
    </script>
</main>
<?php require_once 'app/views/templates/footer.php'; ?>
