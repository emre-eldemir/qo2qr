<?php

class AssignmentController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $assignments = $db->query(
            'SELECT wta.id, u.name AS waiter_name, u.id AS waiter_id, rt.name AS table_name, rt.id AS table_id, wta.created_at
             FROM waiter_table_assignments wta
             JOIN users u ON u.id = wta.waiter_id
             JOIN restaurant_tables rt ON rt.id = wta.table_id
             WHERE rt.restaurant_id = ?
             ORDER BY u.name, rt.name',
            [$rId]
        )->fetchAll();

        $waiters = $db->query(
            'SELECT id, name FROM users WHERE restaurant_id = ? AND role = ? AND status = ? ORDER BY name',
            [$rId, 'waiter', 'active']
        )->fetchAll();

        $tables = $db->query(
            'SELECT id, name FROM restaurant_tables WHERE restaurant_id = ? AND status = ? ORDER BY name',
            [$rId, 'active']
        )->fetchAll();

        view('customer_admin.assignments', [
            'assignments' => $assignments,
            'waiters'     => $waiters,
            'tables'      => $tables,
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $waiterId = (int) ($_POST['waiter_id'] ?? 0);
        $tableId  = (int) ($_POST['table_id'] ?? 0);

        $waiter = $db->query(
            'SELECT id FROM users WHERE id = ? AND restaurant_id = ? AND role = ?',
            [$waiterId, $rId, 'waiter']
        )->fetch();

        $table = $db->query(
            'SELECT id FROM restaurant_tables WHERE id = ? AND restaurant_id = ?',
            [$tableId, $rId]
        )->fetch();

        if (!$waiter || !$table) {
            flash_set('error', 'Invalid waiter or table selection.');
            redirect(url('/customer/assignments'));
        }

        $existing = $db->query(
            'SELECT id FROM waiter_table_assignments WHERE waiter_id = ? AND table_id = ?',
            [$waiterId, $tableId]
        )->fetch();

        if ($existing) {
            flash_set('error', 'This assignment already exists.');
            redirect(url('/customer/assignments'));
        }

        $db->query(
            'INSERT INTO waiter_table_assignments (waiter_id, table_id) VALUES (?, ?)',
            [$waiterId, $tableId]
        );

        log_activity(Auth::id(), $rId, 'assignment_created', 'Assigned waiter to table');
        flash_set('success', 'Assignment created successfully.');
        redirect(url('/customer/assignments'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $assignment = $db->query(
            'SELECT wta.id
             FROM waiter_table_assignments wta
             JOIN restaurant_tables rt ON rt.id = wta.table_id
             WHERE wta.id = ? AND rt.restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$assignment) {
            flash_set('error', 'Assignment not found.');
            redirect(url('/customer/assignments'));
        }

        $db->query('DELETE FROM waiter_table_assignments WHERE id = ?', [(int) $id]);

        log_activity(Auth::id(), $rId, 'assignment_deleted', 'Removed waiter-table assignment');
        flash_set('success', 'Assignment removed successfully.');
        redirect(url('/customer/assignments'));
    }
}
