<?php include __DIR__ . '/../partials/header.php'; ?>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['flash'])): 
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php require $content; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>