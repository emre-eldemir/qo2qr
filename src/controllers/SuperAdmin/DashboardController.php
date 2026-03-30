<?php

class DashboardController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $totalRestaurants = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM restaurants'
        )->fetch()['cnt'] ?? 0);

        $totalOrders = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM orders'
        )->fetch()['cnt'] ?? 0);

        $totalRevenue = (float) ($db->query(
            'SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE status = ?',
            ['completed']
        )->fetch()['total'] ?? 0);

        $activeSubscriptions = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM subscriptions WHERE status = ?',
            ['active']
        )->fetch()['cnt'] ?? 0);

        $recentOrders = $db->query(
            'SELECT o.id, o.customer_name, o.total, o.status, o.created_at,
                    r.name AS restaurant_name, rt.name AS table_name
             FROM orders o
             JOIN restaurants r ON r.id = o.restaurant_id
             JOIN restaurant_tables rt ON rt.id = o.table_id
             ORDER BY o.created_at DESC
             LIMIT 10'
        )->fetchAll();

        $recentActivity = $db->query(
            'SELECT al.action, al.description, al.created_at,
                    u.name AS user_name
             FROM activity_logs al
             JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT 10'
        )->fetchAll();

        view('super_admin.dashboard', [
            'totalRestaurants'    => $totalRestaurants,
            'totalOrders'         => $totalOrders,
            'totalRevenue'        => $totalRevenue,
            'activeSubscriptions' => $activeSubscriptions,
            'recentOrders'        => $recentOrders,
            'recentActivity'      => $recentActivity,
        ]);
    }
}
