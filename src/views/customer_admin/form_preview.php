<?php $layout = 'admin'; $title = 'Form Preview'; $pageTitle = 'Form Preview: ' . h($form['name']); ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => true],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= h($form['name']) ?></h5>
                <a href="<?= url('/customer/forms') ?>" class="btn btn-outline-secondary btn-sm">Back to Forms</a>
            </div>
            <div class="card-body">
                <?php if (empty($formData['fields'])): ?>
                    <p class="text-muted text-center">This form has no fields.</p>
                <?php else: ?>
                    <?php foreach ($formData['fields'] as $field): ?>
                        <?php $req = !empty($field['required']) ? ' <span class="text-danger">*</span>' : ''; ?>
                        <div class="mb-3">
                            <?php if ($field['type'] === 'text'): ?>
                                <label class="form-label"><?= h($field['label']) ?><?= $req ?></label>
                                <input type="text" class="form-control" placeholder="<?= h($field['placeholder'] ?? '') ?>" disabled>
                            <?php elseif ($field['type'] === 'textarea'): ?>
                                <label class="form-label"><?= h($field['label']) ?><?= $req ?></label>
                                <textarea class="form-control" placeholder="<?= h($field['placeholder'] ?? '') ?>" disabled rows="3"></textarea>
                            <?php elseif ($field['type'] === 'select'): ?>
                                <label class="form-label"><?= h($field['label']) ?><?= $req ?></label>
                                <select class="form-select" disabled>
                                    <option value=""><?= h($field['placeholder'] ?? 'Select...') ?></option>
                                    <?php foreach (($field['options'] ?? []) as $opt): ?>
                                        <option><?= h($opt) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($field['type'] === 'button'): ?>
                                <button type="button" class="btn btn-primary" disabled><?= h($field['label']) ?></button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
