<?php $layout = 'admin'; $title = 'Dashboard'; $pageTitle = 'Dashboard'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => true],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-shop fs-3 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Restaurants</h6>
                        <h3 class="mb-0"><?= h((string) $totalRestaurants) ?></h3>
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
                        <i class="bi bi-receipt fs-3 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0"><?= h((string) $totalOrders) ?></h3>
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
                        <i class="bi bi-currency-dollar fs-3 text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Revenue</h6>
                        <h3 class="mb-0"><?= format_price($totalRevenue) ?></h3>
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
                        <i class="bi bi-check-circle fs-3 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Active Subscriptions</h6>
                        <h3 class="mb-0"><?= h((string) $activeSubscriptions) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="<?= url('/super-admin/reports') ?>" class="btn btn-sm btn-outline-primary">View Reports</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Restaurant</th>
                                <th>Table</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><?= h((string) $order['id']) ?></td>
                                        <td><?= h($order['customer_name']) ?></td>
                                        <td><?= h($order['restaurant_name']) ?></td>
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
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Activity</h5>
                <a href="<?= url('/super-admin/activity-logs') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recentActivity)): ?>
                        <div class="list-group-item text-center text-muted py-4">No activity yet.</div>
                    <?php else: ?>
                        <?php foreach ($recentActivity as $activity): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= h($activity['user_name']) ?></strong>
                                        <span class="badge bg-light text-dark ms-1"><?= h($activity['action']) ?></span>
                                        <p class="mb-0 text-muted small"><?= h($activity['description'] ?? '') ?></p>
                                    </div>
                                </div>
                                <small class="text-muted"><?= h(date('M d, H:i', strtotime($activity['created_at']))) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
