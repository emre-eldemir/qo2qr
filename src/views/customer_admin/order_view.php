<?php $layout = 'admin'; $title = 'Order #' . h((string)$order['id']); $pageTitle = 'Order Details'; ?>
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
<?php $statusClasses = ['pending'=>'bg-warning text-dark','confirmed'=>'bg-info text-dark','preparing'=>'bg-primary','ready'=>'bg-success','delivered'=>'bg-secondary','cancelled'=>'bg-danger']; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #<?= h((string) $order['id']) ?></h5>
                <span class="badge <?= $statusClasses[$order['status']] ?? 'bg-secondary' ?>"><?= h(ucfirst($order['status'])) ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Customer</th><td><?= h($order['customer_name']) ?></td></tr>
                    <tr><th class="text-muted">Table</th><td><?= h($order['table_name']) ?></td></tr>
                    <tr><th class="text-muted">Waiter</th><td><?= h($order['waiter_name'] ?? '—') ?></td></tr>
                    <tr><th class="text-muted">Total</th><td><strong><?= format_price($order['total']) ?></strong></td></tr>
                    <tr><th class="text-muted">Notes</th><td><?= h($order['notes'] ?? '—') ?></td></tr>
                    <tr><th class="text-muted">Created</th><td><?= h(date('M d, Y H:i:s', strtotime($order['created_at']))) ?></td></tr>
                    <tr><th class="text-muted">Updated</th><td><?= h(date('M d, Y H:i:s', strtotime($order['updated_at']))) ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Form Data -->
        <?php if (!empty($order['form_data'])): ?>
            <?php $formData = is_string($order['form_data']) ? json_decode($order['form_data'], true) : $order['form_data']; ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Form Data</h6></div>
                <div class="card-body">
                    <?php if (is_array($formData)): ?>
                        <table class="table table-sm mb-0">
                            <?php foreach ($formData as $key => $val): ?>
                                <tr><th class="text-muted"><?= h((string) $key) ?></th><td><?= h(is_array($val) ? json_encode($val) : (string) $val) ?></td></tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p class="text-muted mb-0">No form data.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Order Items -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Order Items</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">No items.</td></tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= h($item['item_name']) ?><?php if (!empty($item['notes'])): ?><br><small class="text-muted"><?= h($item['notes']) ?></small><?php endif; ?></td>
                                        <td><?= h((string) $item['quantity']) ?></td>
                                        <td><?= format_price($item['price']) ?></td>
                                        <td><?= format_price($item['price'] * $item['quantity']) ?></td>
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
<div class="mt-3"><a href="<?= url('/customer/orders') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Orders</a></div>
