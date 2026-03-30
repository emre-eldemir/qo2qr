<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= CSRF::token() ?>">
    <title><?= isset($pageTitle) ? h($pageTitle) . ' | Waiter Panel' : 'Waiter Panel' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 250px; --accent: #0d9488; --accent-dark: #0f766e; }
        body { min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #0f766e 0%, #0d9488 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.3s ease;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            margin: 2px 0.75rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.2);
        }
        .sidebar .nav-link i { width: 1.5rem; text-align: center; margin-right: 0.5rem; }
        .sidebar-brand {
            padding: 1.25rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content { flex: 1; padding: 1.5rem; background: #f0fdfa; }

        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.5rem;
        }

        .toast-container { z-index: 1090; }

        .badge-waiter { background-color: var(--accent); }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1029;
                display: none;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-qr-code-scan me-1"></i> QR Order
    </div>
    <div class="p-2">
        <ul class="nav flex-column">
            <?php
            $waiterMenu = $sidebarMenu ?? [
                ['url' => url('/waiter/dashboard'), 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
                ['url' => url('/waiter/tables'),    'icon' => 'grid-3x3',     'label' => 'My Tables',  'active' => false],
                ['url' => url('/waiter/orders'),    'icon' => 'receipt',       'label' => 'My Orders',  'active' => false],
            ];
            ?>
            <?php foreach ($waiterMenu as $item): ?>
                <li class="nav-item">
                    <a class="nav-link<?= !empty($item['active']) ? ' active' : '' ?>" href="<?= h($item['url']) ?>">
                        <i class="bi bi-<?= h($item['icon']) ?>"></i>
                        <?= h($item['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark d-lg-none me-2 p-0" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-4"></i>
            </button>
            <?php if (!empty($pageTitle)): ?>
                <h5 class="mb-0"><?= h($pageTitle) ?></h5>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if (Auth::check()): ?>
                <span class="d-none d-sm-inline text-muted"><?= h(Auth::user()['name'] ?? '') ?></span>
                <span class="badge badge-waiter">Waiter</span>
                <a href="<?= url('/logout') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
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

        <?php $flash = flash_get('warning'); if ($flash): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= h($flash) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>
<script>
    // Sidebar toggle (mobile)
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function openSidebar() { sidebar.classList.add('show'); overlay.classList.add('show'); }
        function closeSidebar() { sidebar.classList.remove('show'); overlay.classList.remove('show'); }

        toggle.addEventListener('click', function() {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);
    })();

    // Toast helper
    function showToast(message, title = 'Notification') {
        document.getElementById('toastTitle').textContent = title;
        document.getElementById('toastBody').textContent = message;
        const toast = new bootstrap.Toast(document.getElementById('liveToast'));
        toast.show();
    }

    // CSRF header for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // MQTT Client
    (function() {
        var clientId = 'waiter_' + Math.random().toString(16).substring(2, 10);
        var mqttClient = new Paho.MQTT.Client(window.location.hostname, 9001, clientId);

        mqttClient.onConnectionLost = function(responseObject) {
            if (responseObject.errorCode !== 0) {
                console.warn('MQTT connection lost:', responseObject.errorMessage);
                setTimeout(function() { mqttClient.connect({ onSuccess: onConnect }); }, 5000);
            }
        };

        mqttClient.onMessageArrived = function(message) {
            try {
                var data = JSON.parse(message.payloadString);
                if (data.message) {
                    showToast(data.message, data.title || 'Update');
                }
                $(document).trigger('mqtt:message', [message.destinationName, data]);
            } catch (e) {
                console.error('MQTT message parse error:', e);
            }
        };

        function onConnect() {
            console.log('MQTT connected');
            $(document).trigger('mqtt:connected', [mqttClient]);
        }

        mqttClient.connect({
            onSuccess: onConnect,
            onFailure: function(err) { console.warn('MQTT connection failed:', err.errorMessage); }
        });

        window.mqttClient = mqttClient;
    })();
</script>
</body>
</html>
