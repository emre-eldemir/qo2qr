<?php $layout = 'admin'; $title = 'QR Codes'; $pageTitle = 'QR Codes'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => true],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">QR Codes for Tables</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Table</th><th>Status</th><th>QR Preview</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (empty($tables)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">No tables found. Create tables first.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tables as $t): ?>
                            <tr>
                                <td><strong><?= h($t['name']) ?></strong></td>
                                <td>
                                    <?php if ($t['qr_image_path']): ?>
                                        <span class="badge bg-success">Generated</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Not Generated</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($t['qr_image_path']): ?>
                                        <img src="<?= h($t['qr_image_path']) ?>" alt="QR" style="width:80px;height:80px;" class="border rounded">
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="<?= url('/customer/qr/generate/' . (int) $t['id']) ?>" class="d-inline">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-repeat"></i> <?= $t['qr_image_path'] ? 'Regenerate' : 'Generate' ?>
                                        </button>
                                    </form>
                                    <?php if ($t['qr_image_path']): ?>
                                        <a href="<?= url('/customer/qr/download/' . (int) $t['id']) ?>" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
