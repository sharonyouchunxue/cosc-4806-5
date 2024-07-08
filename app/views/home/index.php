<?php require_once 'app/views/templates/header.php'; ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Welcome to COSC 2806</h1>
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
<?php require_once 'app/views/templates/footer.php'; ?>
