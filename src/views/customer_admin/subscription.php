<?php $layout = 'admin'; $title = 'Subscription'; $pageTitle = 'Subscription'; ?>
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
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => true],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Current Plan</h5></div>
            <div class="card-body">
                <h3><?= h($restaurant['plan_name']) ?></h3>
                <p class="text-muted mb-3"><?= format_price($restaurant['price_monthly']) ?>/month</p>
                <?php if ($subscription): ?>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : 'danger' ?>"><?= h(ucfirst($subscription['status'])) ?></span></p>
                    <p class="mb-1"><strong>Started:</strong> <?= h($subscription['starts_at']) ?></p>
                    <p class="mb-0"><strong>Expires:</strong> <?= h($subscription['ends_at']) ?></p>
                <?php else: ?>
                    <p class="text-muted">No active subscription record.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Usage</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Tables</span>
                        <span><?= h((string) $tableCount) ?>/<?= h((string) $restaurant['max_tables']) ?></span>
                    </div>
                    <?php $pctT = $restaurant['max_tables'] > 0 ? min(100, round($tableCount / $restaurant['max_tables'] * 100)) : 0; ?>
                    <div class="progress"><div class="progress-bar bg-<?= $pctT > 80 ? 'danger' : 'primary' ?>" style="width:<?= $pctT ?>%"></div></div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Waiters</span>
                        <span><?= h((string) $waiterCount) ?>/<?= h((string) $restaurant['max_waiters']) ?></span>
                    </div>
                    <?php $pctW = $restaurant['max_waiters'] > 0 ? min(100, round($waiterCount / $restaurant['max_waiters'] * 100)) : 0; ?>
                    <div class="progress"><div class="progress-bar bg-<?= $pctW > 80 ? 'danger' : 'success' ?>" style="width:<?= $pctW ?>%"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Plans -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Available Plans</h5></div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($plans as $p): ?>
                <div class="col-md-4">
                    <div class="card h-100 <?= $p['id'] == $restaurant['plan_id'] ? 'border-primary' : '' ?>">
                        <div class="card-body text-center">
                            <h5><?= h($p['name']) ?></h5>
                            <h3 class="text-primary"><?= format_price($p['price_monthly']) ?><small class="text-muted">/mo</small></h3>
                            <ul class="list-unstyled text-muted">
                                <li><?= h((string) $p['max_tables']) ?> tables</li>
                                <li><?= h((string) $p['max_waiters']) ?> waiters</li>
                            </ul>
                            <?php if ($p['id'] == $restaurant['plan_id']): ?>
                                <span class="badge bg-primary">Current Plan</span>
                            <?php else: ?>
                                <button class="btn btn-outline-primary btn-sm" disabled>Contact to Upgrade</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
