<?php

class DashboardController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db   = Database::getInstance();
        $rId  = Auth::restaurantId();

        $tableCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM restaurant_tables WHERE restaurant_id = ? AND status = ?',
            [$rId, 'active']
        )->fetch()['cnt'] ?? 0);

        $waiterCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM users WHERE restaurant_id = ? AND role = ? AND status = ?',
            [$rId, 'waiter', 'active']
        )->fetch()['cnt'] ?? 0);

        $orderCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM orders WHERE restaurant_id = ?',
            [$rId]
        )->fetch()['cnt'] ?? 0);

        $todayOrders = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM orders WHERE restaurant_id = ? AND DATE(created_at) = CURDATE()',
            [$rId]
        )->fetch()['cnt'] ?? 0);

        $todayRevenue = (float) ($db->query(
            'SELECT COALESCE(SUM(total), 0) AS rev FROM orders WHERE restaurant_id = ? AND DATE(created_at) = CURDATE() AND status != ?',
            [$rId, 'cancelled']
        )->fetch()['rev'] ?? 0);

        $recentOrders = $db->query(
            'SELECT o.id, o.customer_name, o.total, o.status, o.created_at, rt.name AS table_name
             FROM orders o
             JOIN restaurant_tables rt ON rt.id = o.table_id
             WHERE o.restaurant_id = ?
             ORDER BY o.created_at DESC
             LIMIT 10',
            [$rId]
        )->fetchAll();

        view('customer_admin.dashboard', [
            'tableCount'   => $tableCount,
            'waiterCount'  => $waiterCount,
            'orderCount'   => $orderCount,
            'todayOrders'  => $todayOrders,
            'todayRevenue' => $todayRevenue,
            'recentOrders' => $recentOrders,
        ]);
    }
}
