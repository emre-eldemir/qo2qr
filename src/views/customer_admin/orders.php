<?php $layout = 'admin'; $title = 'Orders'; $pageTitle = 'Orders'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => true],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<?php
$statusClasses = ['pending'=>'bg-warning text-dark','confirmed'=>'bg-info text-dark','preparing'=>'bg-primary','ready'=>'bg-success','delivered'=>'bg-secondary','cancelled'=>'bg-danger'];
$statuses = ['','pending','confirmed','preparing','ready','delivered','cancelled'];
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Orders</h5>
        <div class="d-flex gap-1">
            <?php foreach ($statuses as $s): ?>
                <a href="<?= url('/customer/orders' . ($s ? '?status=' . $s : '')) ?>"
                   class="btn btn-sm <?= $statusFilter === $s ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <?= $s ? ucfirst($s) : 'All' ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Table</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td><?= h((string) $o['id']) ?></td>
                                <td><?= h($o['customer_name']) ?></td>
                                <td><?= h($o['table_name']) ?></td>
                                <td><?= format_price($o['total']) ?></td>
                                <td><span class="badge <?= $statusClasses[$o['status']] ?? 'bg-secondary' ?>"><?= h(ucfirst($o['status'])) ?></span></td>
                                <td><?= h(date('M d, Y H:i', strtotime($o['created_at']))) ?></td>
                                <td><a href="<?= url('/customer/orders/view/' . (int) $o['id']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
