<?php $layout = 'admin'; $title = 'Assignments'; $pageTitle = 'Waiter-Table Assignments'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => true],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">New Assignment</h5></div>
            <div class="card-body">
                <form method="POST" action="<?= url('/customer/assignments/store') ?>">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Waiter</label>
                        <select name="waiter_id" class="form-select" required>
                            <option value="">Select waiter...</option>
                            <?php foreach ($waiters as $w): ?>
                                <option value="<?= h((string) $w['id']) ?>"><?= h($w['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Table</label>
                        <select name="table_id" class="form-select" required>
                            <option value="">Select table...</option>
                            <?php foreach ($tables as $t): ?>
                                <option value="<?= h((string) $t['id']) ?>"><?= h($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Assign</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current Assignments</h5>
                <span class="badge bg-primary"><?= h((string) count($assignments)) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Waiter</th><th>Table</th><th>Since</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($assignments)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">No assignments yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($assignments as $a): ?>
                                    <tr>
                                        <td><?= h($a['waiter_name']) ?></td>
                                        <td><?= h($a['table_name']) ?></td>
                                        <td><?= h(date('M d, Y', strtotime($a['created_at']))) ?></td>
                                        <td>
                                            <form method="POST" action="<?= url('/customer/assignments/delete/' . (int) $a['id']) ?>" class="d-inline" onsubmit="return confirm('Remove this assignment?')">
                                                <?= CSRF::field() ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-lg"></i></button>
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
    </div>
</div>
