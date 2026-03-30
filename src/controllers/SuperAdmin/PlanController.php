<?php

class PlanController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $plans = $db->query(
            'SELECT p.*,
                    (SELECT COUNT(*) FROM restaurants r WHERE r.plan_id = p.id) AS subscriber_count
             FROM plans p
             ORDER BY p.price_monthly ASC'
        )->fetchAll();

        view('super_admin.plans', [
            'plans' => $plans,
        ]);
    }

    public function update($id): void
    {
        Auth::requireRole('super_admin');
        CSRF::validateRequest();

        $db = Database::getInstance();

        $plan = $db->query(
            'SELECT id, name FROM plans WHERE id = ?',
            [(int) $id]
        )->fetch();

        if (!$plan) {
            flash_set('error', 'Plan not found.');
            redirect(url('/super-admin/plans'));
        }

        $name       = trim($_POST['name'] ?? '');
        $maxTables  = (int) ($_POST['max_tables'] ?? 0);
        $maxWaiters = (int) ($_POST['max_waiters'] ?? 0);
        $price      = (float) ($_POST['price_monthly'] ?? 0);

        if (empty($name)) {
            flash_set('error', 'Plan name is required.');
            redirect(url('/super-admin/plans'));
        }

        if ($maxTables < 0 || $maxWaiters < 0 || $price < 0) {
            flash_set('error', 'Values cannot be negative.');
            redirect(url('/super-admin/plans'));
        }

        $db->query(
            'UPDATE plans SET name = ?, max_tables = ?, max_waiters = ?, price_monthly = ?, updated_at = NOW()
             WHERE id = ?',
            [$name, $maxTables, $maxWaiters, $price, (int) $id]
        );

        log_activity(
            Auth::id(),
            null,
            'update_plan',
            'Updated plan "' . $name . '" (ID: ' . $id . ')'
        );

        flash_set('success', 'Plan updated successfully.');
        redirect(url('/super-admin/plans'));
    }
}
