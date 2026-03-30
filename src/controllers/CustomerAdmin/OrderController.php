<?php

class OrderController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $statusFilter = $_GET['status'] ?? '';
        $params       = [$rId];
        $where        = 'o.restaurant_id = ?';

        if ($statusFilter !== '' && in_array($statusFilter, ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])) {
            $where   .= ' AND o.status = ?';
            $params[] = $statusFilter;
        }

        $orders = $db->query(
            "SELECT o.id, o.customer_name, o.total, o.status, o.created_at, rt.name AS table_name
             FROM orders o
             JOIN restaurant_tables rt ON rt.id = o.table_id
             WHERE {$where}
             ORDER BY o.created_at DESC
             LIMIT 200",
            $params
        )->fetchAll();

        view('customer_admin.orders', [
            'orders'       => $orders,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function view($id): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $order = $db->query(
            'SELECT o.*, rt.name AS table_name, u.name AS waiter_name
             FROM orders o
             JOIN restaurant_tables rt ON rt.id = o.table_id
             LEFT JOIN users u ON u.id = o.waiter_id
             WHERE o.id = ? AND o.restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$order) {
            flash_set('error', 'Order not found.');
            redirect(url('/customer/orders'));
        }

        $items = $db->query(
            'SELECT oi.*, p.name AS product_name
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?
             ORDER BY oi.id',
            [(int) $id]
        )->fetchAll();

        view('customer_admin.order_view', [
            'order' => $order,
            'items' => $items,
        ]);
    }
}
