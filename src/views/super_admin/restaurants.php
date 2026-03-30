<?php $layout = 'admin'; $title = 'Restaurants'; $pageTitle = 'Restaurants'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => true],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Restaurants</h5>
        <span class="badge bg-primary"><?= h((string) count($restaurants)) ?> total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Plan</th>
                        <th>Tables</th>
                        <th>Waiters</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($restaurants)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No restaurants found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($restaurants as $r): ?>
                            <tr>
                                <td><?= h((string) $r['id']) ?></td>
                                <td>
                                    <strong><?= h($r['name']) ?></strong>
                                    <br><small class="text-muted"><?= h($r['email'] ?? '') ?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= h($r['plan_name']) ?></span></td>
                                <td><?= h((string) $r['table_count']) ?></td>
                                <td><?= h((string) $r['waiter_count']) ?></td>
                                <td><?= h((string) $r['order_count']) ?></td>
                                <td>
                                    <?php if ($r['status'] === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h(date('M d, Y', strtotime($r['created_at']))) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= url('/super-admin/restaurants/view/' . (int) $r['id']) ?>"
                                           class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST" action="<?= url('/super-admin/restaurants/toggle-status/' . (int) $r['id']) ?>"
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to change the status of this restaurant?')">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-outline-<?= $r['status'] === 'active' ? 'warning' : 'success' ?>"
                                                    title="<?= $r['status'] === 'active' ? 'Deactivate' : 'Activate' ?>">
                                                <i class="bi bi-<?= $r['status'] === 'active' ? 'pause-circle' : 'play-circle' ?>"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
