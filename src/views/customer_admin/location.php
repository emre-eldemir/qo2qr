<?php $layout = 'admin'; $title = 'Location'; $pageTitle = 'Location Settings'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => true],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Location Settings</h5></div>
            <div class="card-body">
                <form method="POST" action="<?= url('/customer/location/update') ?>">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="<?= h($restaurant['address'] ?? '') ?>" placeholder="Full address">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" name="latitude" id="lat" class="form-control" step="0.00000001" value="<?= h((string) ($restaurant['latitude'] ?? '')) ?>" placeholder="e.g. 40.7128">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" name="longitude" id="lng" class="form-control" step="0.00000001" value="<?= h((string) ($restaurant['longitude'] ?? '')) ?>" placeholder="e.g. -74.0060">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Allowed Order Radius (km)</label>
                        <input type="number" name="allowed_order_radius_km" class="form-control" step="0.1" min="0.1" value="<?= h((string) ($restaurant['allowed_order_radius_km'] ?? '2.00')) ?>">
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" name="location_restriction_enabled" class="form-check-input" id="locEnabled" <?= !empty($restaurant['location_restriction_enabled']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="locEnabled">Enable location restriction</label>
                    </div>
                    <div class="bg-light rounded p-4 mb-3 text-center text-muted">
                        <i class="bi bi-geo-alt fs-1"></i>
                        <p class="mb-0">Map preview placeholder. Enter coordinates above.</p>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
