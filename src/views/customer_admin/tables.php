<?php $layout = 'admin'; $title = 'Tables'; $pageTitle = 'Tables'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => true],
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

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tables <span class="badge bg-secondary"><?= h((string) count($tables)) ?>/<?= h((string) $maxTables) ?></span></h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTableModal">
            <i class="bi bi-plus-lg"></i> Add Table
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Token</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tables)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No tables yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tables as $t): ?>
                            <tr>
                                <td><?= h((string) $t['id']) ?></td>
                                <td><strong><?= h($t['name']) ?></strong></td>
                                <td><code class="small"><?= h(substr($t['token'], 0, 16)) ?>…</code></td>
                                <td><?= h((string) $t['capacity']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $t['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= h(ucfirst($t['status'])) ?>
                                    </span>
                                </td>
                                <td><?= h(date('M d, Y', strtotime($t['created_at']))) ?></td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTableModal"
                                            onclick="fillEditTable(<?= h((string) $t['id']) ?>, '<?= h($t['name']) ?>', <?= h((string) $t['capacity']) ?>, '<?= h($t['status']) ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="<?= url('/customer/tables/delete/' . (int) $t['id']) ?>" class="d-inline"
                                          onsubmit="return confirm('Delete this table?')">
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

<!-- Add Table Modal -->
<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= url('/customer/tables/store') ?>">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Table Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Table 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="4" min="1" max="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Table</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Table Modal -->
<div class="modal fade" id="editTableModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editTableForm" action="">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Table Name</label>
                        <input type="text" name="name" id="editTableName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" id="editTableCapacity" class="form-control" min="1" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editTableStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Table</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function fillEditTable(id, name, capacity, status) {
    document.getElementById('editTableForm').action = '<?= url('/customer/tables/update/') ?>' + id;
    document.getElementById('editTableName').value = name;
    document.getElementById('editTableCapacity').value = capacity;
    document.getElementById('editTableStatus').value = status;
}
</script>
