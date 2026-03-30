<?php

class ReportController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $restaurantsByPlan = $db->query(
            'SELECT p.name AS plan_name, COUNT(r.id) AS restaurant_count
             FROM plans p
             LEFT JOIN restaurants r ON r.plan_id = p.id
             GROUP BY p.id, p.name
             ORDER BY restaurant_count DESC'
        )->fetchAll();

        $topRestaurants = $db->query(
            'SELECT r.name, COUNT(o.id) AS order_count, COALESCE(SUM(o.total), 0) AS total_revenue
             FROM restaurants r
             LEFT JOIN orders o ON o.restaurant_id = r.id
             GROUP BY r.id, r.name
             ORDER BY order_count DESC
             LIMIT 10'
        )->fetchAll();

        $monthlyRevenue = $db->query(
            'SELECT DATE_FORMAT(pay.created_at, "%Y-%m") AS month,
                    COUNT(pay.id) AS payment_count,
                    COALESCE(SUM(pay.amount), 0) AS total_amount
             FROM payments pay
             WHERE pay.status = ?
             GROUP BY month
             ORDER BY month DESC
             LIMIT 12',
            ['completed']
        )->fetchAll();

        $summary = [
            'total_restaurants' => (int) ($db->query('SELECT COUNT(*) AS cnt FROM restaurants')->fetch()['cnt'] ?? 0),
            'total_orders'      => (int) ($db->query('SELECT COUNT(*) AS cnt FROM orders')->fetch()['cnt'] ?? 0),
            'total_revenue'     => (float) ($db->query('SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE status = ?', ['completed'])->fetch()['total'] ?? 0),
            'total_users'       => (int) ($db->query('SELECT COUNT(*) AS cnt FROM users')->fetch()['cnt'] ?? 0),
        ];

        view('super_admin.reports', [
            'restaurantsByPlan' => $restaurantsByPlan,
            'topRestaurants'    => $topRestaurants,
            'monthlyRevenue'    => $monthlyRevenue,
            'summary'           => $summary,
        ]);
    }
}
