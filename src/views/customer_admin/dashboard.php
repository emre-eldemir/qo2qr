<?php $layout = 'admin'; $title = 'Dashboard'; $pageTitle = 'Dashboard'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => true],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-grid-3x3 fs-3 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tables</h6>
                        <h3 class="mb-0"><?= h((string) $tableCount) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Waiters</h6>
                        <h3 class="mb-0"><?= h((string) $waiterCount) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-receipt fs-3 text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0"><?= h((string) $orderCount) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-cart-check fs-3 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Today's Orders</h6>
                        <h3 class="mb-0"><?= h((string) $todayOrders) ?></h3>
                        <small class="text-muted"><?= format_price($todayRevenue) ?> revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <a href="<?= url('/customer/orders') ?>" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Table</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No orders yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?= h((string) $order['id']) ?></td>
                                <td><?= h($order['customer_name']) ?></td>
                                <td><?= h($order['table_name']) ?></td>
                                <td><?= format_price($order['total']) ?></td>
                                <td>
                                    <?php
                                    $statusClasses = [
                                        'pending'   => 'bg-warning text-dark',
                                        'confirmed' => 'bg-info text-dark',
                                        'preparing' => 'bg-primary',
                                        'ready'     => 'bg-success',
                                        'delivered' => 'bg-secondary',
                                        'cancelled' => 'bg-danger',
                                    ];
                                    $cls = $statusClasses[$order['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= h(ucfirst($order['status'])) ?></span>
                                </td>
                                <td><?= h(date('M d, Y H:i', strtotime($order['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
