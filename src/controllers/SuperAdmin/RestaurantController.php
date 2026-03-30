<?php

class RestaurantController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $restaurants = $db->query(
            'SELECT r.id, r.name, r.slug, r.email, r.phone, r.status, r.created_at,
                    p.name AS plan_name,
                    (SELECT COUNT(*) FROM restaurant_tables rt WHERE rt.restaurant_id = r.id) AS table_count,
                    (SELECT COUNT(*) FROM users w WHERE w.restaurant_id = r.id AND w.role = ?) AS waiter_count,
                    (SELECT COUNT(*) FROM orders o WHERE o.restaurant_id = r.id) AS order_count
             FROM restaurants r
             JOIN plans p ON p.id = r.plan_id
             ORDER BY r.created_at DESC',
            ['waiter']
        )->fetchAll();

        view('super_admin.restaurants', [
            'restaurants' => $restaurants,
        ]);
    }

    public function view($id): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $restaurant = $db->query(
            'SELECT r.*, p.name AS plan_name, p.id AS plan_id,
                    u.name AS owner_name, u.email AS owner_email
             FROM restaurants r
             JOIN plans p ON p.id = r.plan_id
             JOIN users u ON u.id = r.user_id
             WHERE r.id = ?',
            [(int) $id]
        )->fetch();

        if (!$restaurant) {
            flash_set('error', 'Restaurant not found.');
            redirect(url('/super-admin/restaurants'));
        }

        $tables = $db->query(
            'SELECT * FROM restaurant_tables WHERE restaurant_id = ? ORDER BY name',
            [(int) $id]
        )->fetchAll();

        $waiters = $db->query(
            'SELECT u.id, u.name, u.email, u.status, u.created_at
             FROM users u
             WHERE u.restaurant_id = ? AND u.role = ?
             ORDER BY u.name',
            [(int) $id, 'waiter']
        )->fetchAll();

        $orders = $db->query(
            'SELECT o.id, o.customer_name, o.total, o.status, o.created_at,
                    rt.name AS table_name
             FROM orders o
             JOIN restaurant_tables rt ON rt.id = o.table_id
             WHERE o.restaurant_id = ?
             ORDER BY o.created_at DESC
             LIMIT 50',
            [(int) $id]
        )->fetchAll();

        $plans = $db->query(
            'SELECT id, name FROM plans WHERE status = ? ORDER BY price_monthly',
            ['active']
        )->fetchAll();

        view('super_admin.restaurant_view', [
            'restaurant' => $restaurant,
            'tables'     => $tables,
            'waiters'    => $waiters,
            'orders'     => $orders,
            'plans'      => $plans,
        ]);
    }

    public function updatePlan($id): void
    {
        Auth::requireRole('super_admin');
        CSRF::validateRequest();

        $db = Database::getInstance();

        $restaurant = $db->query(
            'SELECT id, name, plan_id FROM restaurants WHERE id = ?',
            [(int) $id]
        )->fetch();

        if (!$restaurant) {
            flash_set('error', 'Restaurant not found.');
            redirect(url('/super-admin/restaurants'));
        }

        $planId = (int) ($_POST['plan_id'] ?? 0);

        $plan = $db->query(
            'SELECT id, name FROM plans WHERE id = ? AND status = ?',
            [$planId, 'active']
        )->fetch();

        if (!$plan) {
            flash_set('error', 'Invalid plan selected.');
            redirect(url('/super-admin/restaurants/view/' . (int) $id));
        }

        $db->query(
            'UPDATE restaurants SET plan_id = ?, updated_at = NOW() WHERE id = ?',
            [$planId, (int) $id]
        );

        $db->query(
            'UPDATE subscriptions SET plan_id = ?, updated_at = NOW()
             WHERE restaurant_id = ? AND status = ?',
            [$planId, (int) $id, 'active']
        );

        log_activity(
            Auth::id(),
            null,
            'update_plan',
            'Changed plan for restaurant "' . $restaurant['name'] . '" to "' . $plan['name'] . '"'
        );

        flash_set('success', 'Restaurant plan updated successfully.');
        redirect(url('/super-admin/restaurants/view/' . (int) $id));
    }

    public function toggleStatus($id): void
    {
        Auth::requireRole('super_admin');
        CSRF::validateRequest();

        $db = Database::getInstance();

        $restaurant = $db->query(
            'SELECT id, name, status FROM restaurants WHERE id = ?',
            [(int) $id]
        )->fetch();

        if (!$restaurant) {
            flash_set('error', 'Restaurant not found.');
            redirect(url('/super-admin/restaurants'));
        }

        $newStatus = $restaurant['status'] === 'active' ? 'inactive' : 'active';

        $db->query(
            'UPDATE restaurants SET status = ?, updated_at = NOW() WHERE id = ?',
            [$newStatus, (int) $id]
        );

        log_activity(
            Auth::id(),
            null,
            'toggle_restaurant_status',
            'Set restaurant "' . $restaurant['name'] . '" to ' . $newStatus
        );

        flash_set('success', 'Restaurant status changed to ' . $newStatus . '.');
        redirect(url('/super-admin/restaurants'));
    }
}
