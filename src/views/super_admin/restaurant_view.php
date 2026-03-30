<?php $layout = 'admin'; $title = h($restaurant['name']); $pageTitle = 'Restaurant Details'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => true],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<div class="mb-3">
    <a href="<?= url('/super-admin/restaurants') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Restaurants
    </a>
</div>

<!-- Restaurant Info Card -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><?= h($restaurant['name']) ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Owner:</strong> <?= h($restaurant['owner_name']) ?></p>
                        <p><strong>Email:</strong> <?= h($restaurant['email'] ?? $restaurant['owner_email']) ?></p>
                        <p><strong>Phone:</strong> <?= h($restaurant['phone'] ?? 'N/A') ?></p>
                        <p><strong>Address:</strong> <?= h($restaurant['address'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Slug:</strong> <code><?= h($restaurant['slug']) ?></code></p>
                        <p><strong>Current Plan:</strong> <span class="badge bg-info text-dark"><?= h($restaurant['plan_name']) ?></span></p>
                        <p><strong>Status:</strong>
                            <?php if ($restaurant['status'] === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Created:</strong> <?= h(date('M d, Y H:i', strtotime($restaurant['created_at']))) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Change Plan</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('/super-admin/restaurants/update-plan/' . (int) $restaurant['id']) ?>">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label for="plan_id" class="form-label">Select Plan</label>
                        <select name="plan_id" id="plan_id" class="form-select" required>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= h((string) $plan['id']) ?>"
                                    <?= (int) $plan['id'] === (int) $restaurant['plan_id'] ? 'selected' : '' ?>>
                                    <?= h($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Update Plan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tables-pane"
                type="button" role="tab">
            <i class="bi bi-grid-3x3"></i> Tables (<?= h((string) count($tables)) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="waiters-tab" data-bs-toggle="tab" data-bs-target="#waiters-pane"
                type="button" role="tab">
            <i class="bi bi-people"></i> Waiters (<?= h((string) count($waiters)) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-pane"
                type="button" role="tab">
            <i class="bi bi-receipt"></i> Orders (<?= h((string) count($orders)) ?>)
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- Tables Tab -->
    <div class="tab-pane fade show active" id="tables-pane" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tables)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No tables found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($tables as $table): ?>
                                    <tr>
                                        <td><?= h((string) $table['id']) ?></td>
                                        <td><?= h($table['name']) ?></td>
                                        <td><?= h((string) $table['capacity']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $table['status'] === 'active' ? 'success' : 'danger' ?>">
                                                <?= h(ucfirst($table['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= h(date('M d, Y', strtotime($table['created_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Waiters Tab -->
    <div class="tab-pane fade" id="waiters-pane" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($waiters)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No waiters found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($waiters as $waiter): ?>
                                    <tr>
                                        <td><?= h((string) $waiter['id']) ?></td>
                                        <td><?= h($waiter['name']) ?></td>
                                        <td><?= h($waiter['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $waiter['status'] === 'active' ? 'success' : 'danger' ?>">
                                                <?= h(ucfirst($waiter['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= h(date('M d, Y', strtotime($waiter['created_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Tab -->
    <div class="tab-pane fade" id="orders-pane" role="tabpanel">
        <div class="card border-0 shadow-sm">
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
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No orders found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
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
    </div>
</div>
