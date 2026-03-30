<?php $layout = 'admin'; $title = 'Forms'; $pageTitle = 'Menu Forms'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => true],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Menu Forms</h5>
        <a href="<?= url('/customer/forms/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Create Form</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>#</th><th>Name</th><th>Fields</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (empty($forms)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No forms yet. Create your first form.</td></tr>
                    <?php else: ?>
                        <?php foreach ($forms as $f): ?>
                            <?php $fd = json_decode($f['form_json'], true); $fieldCount = count($fd['fields'] ?? []); ?>
                            <tr>
                                <td><?= h((string) $f['id']) ?></td>
                                <td><strong><?= h($f['name']) ?></strong></td>
                                <td><span class="badge bg-light text-dark"><?= h((string) $fieldCount) ?> fields</span></td>
                                <td><span class="badge bg-<?= $f['status'] === 'active' ? 'success' : 'danger' ?>"><?= h(ucfirst($f['status'])) ?></span></td>
                                <td><?= h(date('M d, Y', strtotime($f['created_at']))) ?></td>
                                <td>
                                    <a href="<?= url('/customer/forms/preview/' . (int) $f['id']) ?>" class="btn btn-outline-info btn-sm" title="Preview"><i class="bi bi-eye"></i></a>
                                    <a href="<?= url('/customer/forms/edit/' . (int) $f['id']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="<?= url('/customer/forms/delete/' . (int) $f['id']) ?>" class="d-inline" onsubmit="return confirm('Delete this form?')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
