<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= CSRF::token() ?>">
    <title><?= isset($title) ? h($title) . ' | QR Order' : 'QR Order' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }
        .navbar-brand { font-weight: 700; letter-spacing: -0.5px; }
        footer { background: #f8f9fa; }
        .toast-container { z-index: 1090; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="<?= url('/') ?>">
            <i class="bi bi-qr-code-scan me-1"></i>QR Order
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= url('/') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/features') ?>">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/pricing') ?>">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/about') ?>">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/contact') ?>">Contact</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Login
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= url('/login/super-admin') ?>">Super Admin</a></li>
                        <li><a class="dropdown-item" href="<?= url('/login/customer') ?>">Customer</a></li>
                        <li><a class="dropdown-item" href="<?= url('/login/waiter') ?>">Waiter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container py-4">
        <?php $flash = flash_get('success'); if ($flash): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= h($flash) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php $flash = flash_get('error'); if ($flash): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= h($flash) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php $flash = flash_get('info'); if ($flash): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= h($flash) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</main>

<footer class="py-4 mt-auto">
    <div class="container text-center text-muted">
        <p class="mb-0">&copy; <?= date('Y') ?> QR Order. All rights reserved.</p>
    </div>
</footer>

<!-- Toast Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastBody"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showToast(message, title = 'Notification') {
        document.getElementById('toastTitle').textContent = title;
        document.getElementById('toastBody').textContent = message;
        const toast = new bootstrap.Toast(document.getElementById('liveToast'));
        toast.show();
    }

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>
</body>
</html>
