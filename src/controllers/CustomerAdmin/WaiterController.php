<?php

class WaiterController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $waiters = $db->query(
            'SELECT id, name, email, status, created_at FROM users WHERE restaurant_id = ? AND role = ? ORDER BY name',
            [$rId, 'waiter']
        )->fetchAll();

        $plan = $db->query(
            'SELECT p.max_waiters FROM restaurants r JOIN plans p ON p.id = r.plan_id WHERE r.id = ?',
            [$rId]
        )->fetch();

        view('customer_admin.waiters', [
            'waiters'    => $waiters,
            'maxWaiters' => (int) ($plan['max_waiters'] ?? 0),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $plan = $db->query(
            'SELECT p.max_waiters FROM restaurants r JOIN plans p ON p.id = r.plan_id WHERE r.id = ?',
            [$rId]
        )->fetch();

        $currentCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM users WHERE restaurant_id = ? AND role = ?',
            [$rId, 'waiter']
        )->fetch()['cnt'] ?? 0);

        if ($currentCount >= (int) ($plan['max_waiters'] ?? 0)) {
            flash_set('error', 'Waiter limit reached for your plan. Please upgrade.');
            redirect(url('/customer/waiters'));
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            flash_set('error', 'Name, email and password are required.');
            redirect(url('/customer/waiters'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Invalid email address.');
            redirect(url('/customer/waiters'));
        }

        $existing = $db->query('SELECT id FROM users WHERE email = ?', [$email])->fetch();
        if ($existing) {
            flash_set('error', 'A user with this email already exists.');
            redirect(url('/customer/waiters'));
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $db->query(
            'INSERT INTO users (restaurant_id, name, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?)',
            [$rId, $name, $email, $hashedPassword, 'waiter', 'active']
        );

        log_activity(Auth::id(), $rId, 'waiter_created', "Created waiter: {$name}");
        flash_set('success', 'Waiter created successfully.');
        redirect(url('/customer/waiters'));
    }

    public function update($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $waiter = $db->query(
            'SELECT * FROM users WHERE id = ? AND restaurant_id = ? AND role = ?',
            [(int) $id, $rId, 'waiter']
        )->fetch();

        if (!$waiter) {
            flash_set('error', 'Waiter not found.');
            redirect(url('/customer/waiters'));
        }

        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if ($name === '' || $email === '') {
            flash_set('error', 'Name and email are required.');
            redirect(url('/customer/waiters'));
        }

        $existing = $db->query('SELECT id FROM users WHERE email = ? AND id != ?', [$email, (int) $id])->fetch();
        if ($existing) {
            flash_set('error', 'A user with this email already exists.');
            redirect(url('/customer/waiters'));
        }

        $password = $_POST['password'] ?? '';
        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $db->query(
                'UPDATE users SET name = ?, email = ?, password = ?, status = ? WHERE id = ? AND restaurant_id = ?',
                [$name, $email, $hashedPassword, $status, (int) $id, $rId]
            );
        } else {
            $db->query(
                'UPDATE users SET name = ?, email = ?, status = ? WHERE id = ? AND restaurant_id = ?',
                [$name, $email, $status, (int) $id, $rId]
            );
        }

        log_activity(Auth::id(), $rId, 'waiter_updated', "Updated waiter: {$name}");
        flash_set('success', 'Waiter updated successfully.');
        redirect(url('/customer/waiters'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $waiter = $db->query(
            'SELECT * FROM users WHERE id = ? AND restaurant_id = ? AND role = ?',
            [(int) $id, $rId, 'waiter']
        )->fetch();

        if (!$waiter) {
            flash_set('error', 'Waiter not found.');
            redirect(url('/customer/waiters'));
        }

        $db->query(
            'DELETE FROM users WHERE id = ? AND restaurant_id = ? AND role = ?',
            [(int) $id, $rId, 'waiter']
        );

        log_activity(Auth::id(), $rId, 'waiter_deleted', "Deleted waiter: {$waiter['name']}");
        flash_set('success', 'Waiter deleted successfully.');
        redirect(url('/customer/waiters'));
    }
}
