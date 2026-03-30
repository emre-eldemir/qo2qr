<?php

class PaymentController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $payments = $db->query(
            'SELECT pay.id, pay.amount, pay.method, pay.status, pay.transaction_id, pay.created_at,
                    r.name AS restaurant_name
             FROM payments pay
             JOIN restaurants r ON r.id = pay.restaurant_id
             ORDER BY pay.created_at DESC'
        )->fetchAll();

        view('super_admin.payments', [
            'payments' => $payments,
        ]);
    }
}
