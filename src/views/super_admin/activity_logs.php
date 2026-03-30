<?php $layout = 'admin'; $title = 'Activity Logs'; $pageTitle = 'Activity Logs'; ?>
<?php $sidebarMenu = [
    ['url' => '/super-admin', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/super-admin/restaurants', 'icon' => 'shop', 'label' => 'Restaurants', 'active' => false],
    ['url' => '/super-admin/plans', 'icon' => 'box', 'label' => 'Plans', 'active' => false],
    ['url' => '/super-admin/payments', 'icon' => 'credit-card', 'label' => 'Payments', 'active' => false],
    ['url' => '/super-admin/reports', 'icon' => 'graph-up', 'label' => 'Reports', 'active' => false],
    ['url' => '/super-admin/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => true],
    ['url' => '/super-admin/settings', 'icon' => 'gear', 'label' => 'Settings', 'active' => false],
]; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activity Logs</h5>
        <span class="text-muted"><?= h((string) $totalRows) ?> total entries</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No activity logs found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= h((string) $log['id']) ?></td>
                                <td><strong><?= h($log['user_name']) ?></strong></td>
                                <td><span class="badge bg-light text-dark"><?= h($log['action']) ?></span></td>
                                <td><?= h($log['description'] ?? '') ?></td>
                                <td><code><?= h($log['ip_address'] ?? '—') ?></code></td>
                                <td><?= h(date('M d, Y H:i', strtotime($log['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white">
            <nav aria-label="Activity log pagination">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url('/super-admin/activity-logs?page=' . ($page - 1)) ?>">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                    </li>

                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    ?>

                    <?php if ($startPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/super-admin/activity-logs?page=1') ?>">1</a>
                        </li>
                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/super-admin/activity-logs?page=' . $i) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/super-admin/activity-logs?page=' . $totalPages) ?>">
                                <?= $totalPages ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url('/super-admin/activity-logs?page=' . ($page + 1)) ?>">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <p class="text-center text-muted mt-2 mb-0 small">
                Page <?= h((string) $page) ?> of <?= h((string) $totalPages) ?>
            </p>
        </div>
    <?php endif; ?>
</div>
