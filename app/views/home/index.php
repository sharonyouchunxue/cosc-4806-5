<?php require_once 'app/views/templates/header.php'; ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Welcome to COSC 4806</h1>
                <p class="lead"><?= date("F jS, Y"); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p>An introduction to the design and implementation of web interfaces to database systems. Web data models, web query languages, change management systems, and website management are discussed.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p><a href="/logout">Click here to logout</a></p>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Admin login successful!
        </div>
    </div>
</div>

<script>
// Display success message as toast notification
<?php if (isset($_SESSION['success'])): ?>
document.addEventListener('DOMContentLoaded', function () {
    var successToast = new bootstrap.Toast(document.getElementById('successToast'));
    successToast.show();
});
<?php unset($_SESSION['success']); endif; ?>
</script>

<?php require_once 'app/views/templates/footer.php'; ?>
