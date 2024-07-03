<?php require_once 'app/views/templates/header.php'; ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Message Display</h1>
            </div>
        </div>
    </div>

    <!-- Success Message display -->
    <?php if (isset($data['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($data['message']); ?></div>
    <?php endif; ?>

    <script>
        // Redirect to the reminders list after 3 seconds
        setTimeout(function() {
            window.location.href = '/reminders';
        }, 3000);
    </script>
</main>
<?php require_once 'app/views/templates/footer.php'; ?>
