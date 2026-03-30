<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?= CSRF::token() ?>">
    <title><?= isset($title) ? h($title) . ' | QR Order' : 'QR Order' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .qr-brand {
            text-align: center;
            padding: 2rem 1rem 1rem;
            font-weight: 700;
            font-size: 1.5rem;
            color: #0d6efd;
        }
        .qr-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            max-width: 480px;
            margin: 0 auto;
            width: 100%;
            padding: 0 1rem 2rem;
        }
    </style>
</head>
<body>

<div class="qr-brand">
    <i class="bi bi-qr-code-scan"></i> QR Order
</div>

<div class="qr-container">
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // CSRF header for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Geolocation setup
    window.userLocation = { latitude: null, longitude: null, error: null };

    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                window.userLocation.latitude = position.coords.latitude;
                window.userLocation.longitude = position.coords.longitude;
                $(document).trigger('geolocation:success', [window.userLocation]);
            },
            function(error) {
                window.userLocation.error = error.message;
                $(document).trigger('geolocation:error', [error]);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    }
</script>
</body>
</html>
