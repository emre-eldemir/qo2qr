<?php $layout = 'admin'; $title = 'Products'; $pageTitle = 'Products & Categories'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => true],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => false],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#categoriesTab">Categories</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#productsTab">Products</a></li>
</ul>

<div class="tab-content">
    <!-- Categories Tab -->
    <div class="tab-pane fade show active" id="categoriesTab">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Categories</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="bi bi-plus-lg"></i> Add Category</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>#</th><th>Name</th><th>Sort Order</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No categories yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($categories as $c): ?>
                                    <tr>
                                        <td><?= h((string) $c['id']) ?></td>
                                        <td><strong><?= h($c['name']) ?></strong></td>
                                        <td><?= h((string) $c['sort_order']) ?></td>
                                        <td><span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'danger' ?>"><?= h(ucfirst($c['status'])) ?></span></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                                    onclick="fillEditCat(<?= h((string) $c['id']) ?>,'<?= h($c['name']) ?>',<?= h((string) $c['sort_order']) ?>,'<?= h($c['status']) ?>')"><i class="bi bi-pencil"></i></button>
                                            <form method="POST" action="<?= url('/customer/categories/delete/' . (int) $c['id']) ?>" class="d-inline" onsubmit="return confirm('Delete this category?')">
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
    </div>

    <!-- Products Tab -->
    <div class="tab-pane fade" id="productsTab">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-lg"></i> Add Product</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>#</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No products yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td><?= h((string) $p['id']) ?></td>
                                        <td><strong><?= h($p['name']) ?></strong><br><small class="text-muted"><?= h($p['description'] ?? '') ?></small></td>
                                        <td><?= h($p['category_name']) ?></td>
                                        <td><?= format_price($p['price']) ?></td>
                                        <td><span class="badge bg-<?= $p['status'] === 'active' ? 'success' : 'danger' ?>"><?= h(ucfirst($p['status'])) ?></span></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    onclick="fillEditProd(<?= h((string) $p['id']) ?>,'<?= h($p['name']) ?>',<?= h((string) $p['category_id']) ?>,'<?= h($p['description'] ?? '') ?>',<?= h((string) $p['price']) ?>,'<?= h($p['status']) ?>')"><i class="bi bi-pencil"></i></button>
                                            <form method="POST" action="<?= url('/customer/products/delete/' . (int) $p['id']) ?>" class="d-inline" onsubmit="return confirm('Delete this product?')">
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
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1"><div class="modal-dialog"><form method="POST" action="<?= url('/customer/categories/store') ?>"><?= CSRF::field() ?>
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create</button></div>
</div></form></div></div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1"><div class="modal-dialog"><form method="POST" id="editCatForm" action=""><?= CSRF::field() ?>
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" id="editCatName" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" id="editCatSort" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Status</label><select name="status" id="editCatStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div>
</div></form></div></div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1"><div class="modal-dialog"><form method="POST" action="<?= url('/customer/products/store') ?>"><?= CSRF::field() ?>
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select" required><option value="">Select...</option><?php foreach ($categories as $c): ?><option value="<?= h((string) $c['id']) ?>"><?= h($c['name']) ?></option><?php endforeach; ?></select></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label class="form-label">Price</label><input type="number" name="price" class="form-control" step="0.01" min="0" required></div>
    <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create</button></div>
</div></form></div></div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1"><div class="modal-dialog"><form method="POST" id="editProdForm" action=""><?= CSRF::field() ?>
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" id="editProdName" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Category</label><select name="category_id" id="editProdCat" class="form-select" required><?php foreach ($categories as $c): ?><option value="<?= h((string) $c['id']) ?>"><?= h($c['name']) ?></option><?php endforeach; ?></select></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" id="editProdDesc" class="form-control" rows="2"></textarea></div>
    <div class="mb-3"><label class="form-label">Price</label><input type="number" name="price" id="editProdPrice" class="form-control" step="0.01" min="0" required></div>
    <div class="mb-3"><label class="form-label">Status</label><select name="status" id="editProdStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div>
</div></form></div></div>

<script>
function fillEditCat(id,name,sort,status){
    document.getElementById('editCatForm').action='<?= url('/customer/categories/update/') ?>'+id;
    document.getElementById('editCatName').value=name;
    document.getElementById('editCatSort').value=sort;
    document.getElementById('editCatStatus').value=status;
}
function fillEditProd(id,name,catId,desc,price,status){
    document.getElementById('editProdForm').action='<?= url('/customer/products/update/') ?>'+id;
    document.getElementById('editProdName').value=name;
    document.getElementById('editProdCat').value=catId;
    document.getElementById('editProdDesc').value=desc;
    document.getElementById('editProdPrice').value=price;
    document.getElementById('editProdStatus').value=status;
}
</script>
