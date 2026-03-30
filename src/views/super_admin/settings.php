<?php $layout = 'admin'; $title = 'Settings'; $pageTitle = 'Settings'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => true],
]; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">System Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('/super-admin/settings') ?>">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name"
                               value="QR Order Platform" placeholder="Enter site name">
                        <div class="form-text">The name displayed across the platform.</div>
                    </div>

                    <div class="mb-3">
                        <label for="default_radius" class="form-label">Default Order Radius (km)</label>
                        <input type="number" class="form-control" id="default_radius" name="default_radius"
                               value="2" min="0" step="0.1" placeholder="Enter default radius">
                        <div class="form-text">Default allowed radius for QR ordering (in kilometres).</div>
                    </div>

                    <div class="mb-3">
                        <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                        <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts"
                               value="5" min="1" placeholder="Enter max attempts">
                        <div class="form-text">Maximum number of failed login attempts before rate limiting.</div>
                    </div>

                    <div class="mb-3">
                        <label for="session_lifetime" class="form-label">Session Lifetime (minutes)</label>
                        <input type="number" class="form-control" id="session_lifetime" name="session_lifetime"
                               value="120" min="5" placeholder="Enter session lifetime">
                        <div class="form-text">How long user sessions remain active.</div>
                    </div>

                    <div class="mb-3">
                        <label for="support_email" class="form-label">Support Email</label>
                        <input type="email" class="form-control" id="support_email" name="support_email"
                               value="" placeholder="support@example.com">
                        <div class="form-text">Email address for customer support inquiries.</div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold">PHP Version</td>
                            <td><?= h(phpversion()) ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Server Software</td>
                            <td><?= h($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Platform Version</td>
                            <td>1.0.0 (MVP)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
