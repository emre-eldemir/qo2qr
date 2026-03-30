<?php $layout = 'admin'; $title = 'Waiters'; $pageTitle = 'Waiters'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => true],
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

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Waiters <span class="badge bg-secondary"><?= h((string) count($waiters)) ?>/<?= h((string) $maxWaiters) ?></span></h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addWaiterModal">
            <i class="bi bi-plus-lg"></i> Add Waiter
        </button>
    </div>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($waiters)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No waiters yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($waiters as $w): ?>
                            <tr>
                                <td><?= h((string) $w['id']) ?></td>
                                <td><strong><?= h($w['name']) ?></strong></td>
                                <td><?= h($w['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $w['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= h(ucfirst($w['status'])) ?>
                                    </span>
                                </td>
                                <td><?= h(date('M d, Y', strtotime($w['created_at']))) ?></td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editWaiterModal"
                                            onclick="fillEditWaiter(<?= h((string) $w['id']) ?>, '<?= h($w['name']) ?>', '<?= h($w['email']) ?>', '<?= h($w['status']) ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="<?= url('/customer/waiters/delete/' . (int) $w['id']) ?>" class="d-inline"
                                          onsubmit="return confirm('Delete this waiter?')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Waiter Modal -->
<div class="modal fade" id="addWaiterModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= url('/customer/waiters/store') ?>">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Waiter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Waiter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Waiter Modal -->
<div class="modal fade" id="editWaiterModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editWaiterForm" action="">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Waiter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editWaiterName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editWaiterEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editWaiterStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Waiter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function fillEditWaiter(id, name, email, status) {
    document.getElementById('editWaiterForm').action = '<?= url('/customer/waiters/update/') ?>' + id;
    document.getElementById('editWaiterName').value = name;
    document.getElementById('editWaiterEmail').value = email;
    document.getElementById('editWaiterStatus').value = status;
}
</script>
