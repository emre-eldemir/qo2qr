<?php

class SubscriptionController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $restaurant = $db->query(
            'SELECT r.*, p.name AS plan_name, p.slug AS plan_slug, p.max_tables, p.max_waiters, p.price_monthly
             FROM restaurants r
             JOIN plans p ON p.id = r.plan_id
             WHERE r.id = ?',
            [$rId]
        )->fetch();

        $subscription = $db->query(
            'SELECT * FROM subscriptions WHERE restaurant_id = ? AND status = ? ORDER BY ends_at DESC LIMIT 1',
            [$rId, 'active']
        )->fetch();

        $tableCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM restaurant_tables WHERE restaurant_id = ?',
            [$rId]
        )->fetch()['cnt'] ?? 0);

        $waiterCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM users WHERE restaurant_id = ? AND role = ?',
            [$rId, 'waiter']
        )->fetch()['cnt'] ?? 0);

        $plans = $db->query(
            'SELECT * FROM plans WHERE status = ? ORDER BY price_monthly',
            ['active']
        )->fetchAll();

        view('customer_admin.subscription', [
            'restaurant'   => $restaurant,
            'subscription' => $subscription,
            'tableCount'   => $tableCount,
            'waiterCount'  => $waiterCount,
            'plans'        => $plans,
        ]);
    }
}
