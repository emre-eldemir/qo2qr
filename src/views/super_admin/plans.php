<?php $layout = 'admin'; $title = 'Plans'; $pageTitle = 'Plans'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => true],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Subscription Plans</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Max Tables</th>
                        <th>Max Waiters</th>
                        <th>Price (Monthly)</th>
                        <th>Subscribers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($plans)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No plans found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td><?= h((string) $plan['id']) ?></td>
                                <td><strong><?= h($plan['name']) ?></strong></td>
                                <td><?= h((string) $plan['max_tables']) ?></td>
                                <td><?= h((string) $plan['max_waiters']) ?></td>
                                <td><?= format_price($plan['price_monthly']) ?></td>
                                <td><span class="badge bg-primary"><?= h((string) $plan['subscriber_count']) ?></span></td>
                                <td>
                                    <span class="badge bg-<?= $plan['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= h(ucfirst($plan['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPlanModal<?= h((string) $plan['id']) ?>">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Plan Modals -->
<?php foreach ($plans as $plan): ?>
    <div class="modal fade" id="editPlanModal<?= h((string) $plan['id']) ?>" tabindex="-1"
         aria-labelledby="editPlanLabel<?= h((string) $plan['id']) ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?= url('/super-admin/plans/update/' . (int) $plan['id']) ?>">
                    <?= CSRF::field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlanLabel<?= h((string) $plan['id']) ?>">
                            Edit Plan: <?= h($plan['name']) ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_<?= h((string) $plan['id']) ?>" class="form-label">Plan Name</label>
                            <input type="text" class="form-control" id="name_<?= h((string) $plan['id']) ?>"
                                   name="name" value="<?= h($plan['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_tables_<?= h((string) $plan['id']) ?>" class="form-label">Max Tables</label>
                            <input type="number" class="form-control" id="max_tables_<?= h((string) $plan['id']) ?>"
                                   name="max_tables" value="<?= h((string) $plan['max_tables']) ?>" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_waiters_<?= h((string) $plan['id']) ?>" class="form-label">Max Waiters</label>
                            <input type="number" class="form-control" id="max_waiters_<?= h((string) $plan['id']) ?>"
                                   name="max_waiters" value="<?= h((string) $plan['max_waiters']) ?>" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="price_monthly_<?= h((string) $plan['id']) ?>" class="form-label">Price (Monthly)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="price_monthly_<?= h((string) $plan['id']) ?>"
                                       name="price_monthly" value="<?= h((string) $plan['price_monthly']) ?>"
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
