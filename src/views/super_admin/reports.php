<?php $layout = 'admin'; $title = 'Reports'; $pageTitle = 'Reports'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => true],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-shop fs-2 text-primary"></i>
                <h3 class="mt-2 mb-0"><?= h((string) $summary['total_restaurants']) ?></h3>
                <p class="text-muted mb-0">Restaurants</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-receipt fs-2 text-success"></i>
                <h3 class="mt-2 mb-0"><?= h((string) $summary['total_orders']) ?></h3>
                <p class="text-muted mb-0">Orders</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-currency-dollar fs-2 text-warning"></i>
                <h3 class="mt-2 mb-0"><?= format_price($summary['total_revenue']) ?></h3>
                <p class="text-muted mb-0">Total Revenue</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-people fs-2 text-info"></i>
                <h3 class="mt-2 mb-0"><?= h((string) $summary['total_users']) ?></h3>
                <p class="text-muted mb-0">Users</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Restaurants by Plan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Restaurants by Plan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Plan</th>
                                <th class="text-end">Restaurants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($restaurantsByPlan)): ?>
                                <tr><td colspan="2" class="text-center text-muted py-4">No data available.</td></tr>
                            <?php else: ?>
                                <?php foreach ($restaurantsByPlan as $row): ?>
                                    <tr>
                                        <td><?= h($row['plan_name']) ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-primary"><?= h((string) $row['restaurant_count']) ?></span>
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

    <!-- Top Restaurants by Orders -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Restaurants by Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Restaurant</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topRestaurants)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">No data available.</td></tr>
                            <?php else: ?>
                                <?php $rank = 1; ?>
                                <?php foreach ($topRestaurants as $row): ?>
                                    <tr>
                                        <td><?= $rank++ ?></td>
                                        <td><?= h($row['name']) ?></td>
                                        <td class="text-end"><span class="badge bg-info text-dark"><?= h((string) $row['order_count']) ?></span></td>
                                        <td class="text-end"><?= format_price($row['total_revenue']) ?></td>
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

<!-- Monthly Revenue -->
<div class="row g-4 mt-0">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Monthly Revenue</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Payments</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($monthlyRevenue)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">No revenue data available.</td></tr>
                            <?php else: ?>
                                <?php foreach ($monthlyRevenue as $row): ?>
                                    <tr>
                                        <td><?= h($row['month']) ?></td>
                                        <td class="text-end"><?= h((string) $row['payment_count']) ?></td>
                                        <td class="text-end"><strong><?= format_price($row['total_amount']) ?></strong></td>
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
