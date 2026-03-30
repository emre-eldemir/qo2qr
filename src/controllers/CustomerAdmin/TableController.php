<?php

class TableController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $tables = $db->query(
            'SELECT * FROM restaurant_tables WHERE restaurant_id = ? ORDER BY name',
            [$rId]
        )->fetchAll();

        $plan = $db->query(
            'SELECT p.max_tables FROM restaurants r JOIN plans p ON p.id = r.plan_id WHERE r.id = ?',
            [$rId]
        )->fetch();

        view('customer_admin.tables', [
            'tables'    => $tables,
            'maxTables' => (int) ($plan['max_tables'] ?? 0),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $plan = $db->query(
            'SELECT p.max_tables FROM restaurants r JOIN plans p ON p.id = r.plan_id WHERE r.id = ?',
            [$rId]
        )->fetch();

        $currentCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM restaurant_tables WHERE restaurant_id = ?',
            [$rId]
        )->fetch()['cnt'] ?? 0);

        if ($currentCount >= (int) ($plan['max_tables'] ?? 0)) {
            flash_set('error', 'Table limit reached for your plan. Please upgrade.');
            redirect(url('/customer/tables'));
        }

        $name     = trim($_POST['name'] ?? '');
        $capacity = max(1, (int) ($_POST['capacity'] ?? 4));

        if ($name === '') {
            flash_set('error', 'Table name is required.');
            redirect(url('/customer/tables'));
        }

        $token = generate_token();

        $db->query(
            'INSERT INTO restaurant_tables (restaurant_id, name, token, capacity, status) VALUES (?, ?, ?, ?, ?)',
            [$rId, $name, $token, $capacity, 'active']
        );

        log_activity(Auth::id(), $rId, 'table_created', "Created table: {$name}");
        flash_set('success', 'Table created successfully.');
        redirect(url('/customer/tables'));
    }

    public function update($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $table = $db->query(
            'SELECT * FROM restaurant_tables WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$table) {
            flash_set('error', 'Table not found.');
            redirect(url('/customer/tables'));
        }

        $name     = trim($_POST['name'] ?? '');
        $capacity = max(1, (int) ($_POST['capacity'] ?? 4));
        $status   = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if ($name === '') {
            flash_set('error', 'Table name is required.');
            redirect(url('/customer/tables'));
        }

        $db->query(
            'UPDATE restaurant_tables SET name = ?, capacity = ?, status = ? WHERE id = ? AND restaurant_id = ?',
            [$name, $capacity, $status, (int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'table_updated', "Updated table: {$name}");
        flash_set('success', 'Table updated successfully.');
        redirect(url('/customer/tables'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $table = $db->query(
            'SELECT * FROM restaurant_tables WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$table) {
            flash_set('error', 'Table not found.');
            redirect(url('/customer/tables'));
        }

        $db->query(
            'DELETE FROM restaurant_tables WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'table_deleted', "Deleted table: {$table['name']}");
        flash_set('success', 'Table deleted successfully.');
        redirect(url('/customer/tables'));
    }
}
