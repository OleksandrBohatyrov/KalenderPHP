<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="events.php">Kalender</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Sündmused</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_events.php">Halda sündmusi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_reminders.php">Halda meeldetuletusi</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link btn btn-danger text-white" href="logout.php">Logi välja</a>
                    <?php else: ?>
                    <a class="nav-link btn btn-danger text-white" href="login.php">Sisene</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
