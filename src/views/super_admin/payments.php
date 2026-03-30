<?php $layout = 'admin'; $title = 'Payments'; $pageTitle = 'Payments'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => true],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Payments</h5>
        <span class="badge bg-primary"><?= h((string) count($payments)) ?> total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Restaurant</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No payments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $pay): ?>
                            <tr>
                                <td><?= h((string) $pay['id']) ?></td>
                                <td><?= h($pay['restaurant_name']) ?></td>
                                <td><strong><?= format_price($pay['amount']) ?></strong></td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= h(ucwords(str_replace('_', ' ', $pay['method']))) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClasses = [
                                        'pending'   => 'bg-warning text-dark',
                                        'completed' => 'bg-success',
                                        'failed'    => 'bg-danger',
                                        'refunded'  => 'bg-secondary',
                                    ];
                                    $cls = $statusClasses[$pay['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= h(ucfirst($pay['status'])) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($pay['transaction_id'])): ?>
                                        <code><?= h($pay['transaction_id']) ?></code>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h(date('M d, Y H:i', strtotime($pay['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
