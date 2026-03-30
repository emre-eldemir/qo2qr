<?php

class AuthController
{
    public function loginSelect(): void
    {
        view('auth.login_select');
    }

    public function superAdminLogin(): void
    {
        view('auth.super_admin_login');
    }

    public function superAdminLoginPost(): void
    {
        $this->handleLogin('super_admin', '/super-admin', '/login/admin');
    }

    public function customerLogin(): void
    {
        view('auth.customer_login');
    }

    public function customerLoginPost(): void
    {
        $this->handleLogin('customer_admin', '/customer', '/login/customer');
    }

    public function waiterLogin(): void
    {
        view('auth.waiter_login');
    }

    public function waiterLoginPost(): void
    {
        $this->handleLogin('waiter', '/waiter', '/login/waiter');
    }

    public function register(): void
    {
        view('auth.register');
    }

    public function registerPost(): void
    {
        CSRF::validateRequest();

        if (!rate_limit_check('register', 3, 300)) {
            flash_set('error', 'Too many registration attempts. Please try again later.');
            redirect(url('/register'));
        }

        $name            = trim($_POST['name'] ?? '');
        $restaurantName  = trim($_POST['restaurant_name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $oldInput        = ['name' => $name, 'restaurant_name' => $restaurantName, 'email' => $email];

        if (empty($name) || empty($restaurantName) || empty($email) || empty($password)) {
            flash_set('error', 'Please fill in all required fields.');
            flash_set('_old_input', $oldInput);
            redirect(url('/register'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Please enter a valid email address.');
            flash_set('_old_input', $oldInput);
            redirect(url('/register'));
        }

        if (strlen($password) < 8) {
            flash_set('error', 'Password must be at least 8 characters long.');
            flash_set('_old_input', $oldInput);
            redirect(url('/register'));
        }

        if ($password !== $passwordConfirm) {
            flash_set('error', 'Passwords do not match.');
            flash_set('_old_input', $oldInput);
            redirect(url('/register'));
        }

        $db = Database::getInstance();

        $existing = $db->query(
            'SELECT id FROM users WHERE email = ? LIMIT 1',
            [$email]
        )->fetch();

        if ($existing) {
            flash_set('error', 'An account with this email already exists.');
            flash_set('_old_input', $oldInput);
            redirect(url('/register'));
        }

        $freePlan = $db->query(
            'SELECT id FROM plans WHERE slug = ? AND status = ? LIMIT 1',
            ['free', 'active']
        )->fetch();

        if (!$freePlan) {
            flash_set('error', 'Registration is currently unavailable. Please try again later.');
            redirect(url('/register'));
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $db->query(
            'INSERT INTO users (name, email, password, role, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
            [$name, $email, $hashedPassword, 'customer_admin', 'active']
        );
        $userId = (int) $db->lastInsertId();

        // Generate a unique restaurant slug
        $slug     = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $restaurantName), '-'));
        $baseSlug = $slug;
        $counter  = 1;
        while ($db->query('SELECT id FROM restaurants WHERE slug = ? LIMIT 1', [$slug])->fetch()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $db->query(
            'INSERT INTO restaurants (user_id, name, slug, email, plan_id, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [$userId, $restaurantName, $slug, $email, $freePlan['id'], 'active']
        );
        $restaurantId = (int) $db->lastInsertId();

        $db->query(
            'UPDATE users SET restaurant_id = ? WHERE id = ?',
            [$restaurantId, $userId]
        );

        $db->query(
            'INSERT INTO subscriptions (restaurant_id, plan_id, starts_at, ends_at, status, created_at, updated_at)
             VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), ?, NOW(), NOW())',
            [$restaurantId, $freePlan['id'], 'active']
        );

        log_activity($userId, $restaurantId, 'register', 'New restaurant registered: ' . $restaurantName);

        flash_set('success', 'Registration successful! Please log in to continue.');
        redirect(url('/login/customer'));
    }

    public function logout(): void
    {
        if (Auth::check()) {
            log_activity(Auth::id(), Auth::restaurantId(), 'logout', 'User logged out');
        }

        Auth::logout();
        flash_set('success', 'You have been logged out successfully.');
        redirect(url('/'));
    }

    /**
     * Shared login handler for all roles.
     */
    private function handleLogin(string $role, string $redirectPath, string $loginPath): void
    {
        CSRF::validateRequest();

        if (!rate_limit_check('login_' . $role, 5, 300)) {
            flash_set('error', 'Too many login attempts. Please try again later.');
            redirect(url($loginPath));
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash_set('error', 'Please enter your email and password.');
            flash_set('_old_input', ['email' => $email]);
            redirect(url($loginPath));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Please enter a valid email address.');
            flash_set('_old_input', ['email' => $email]);
            redirect(url($loginPath));
        }

        if (Auth::login($email, $password, $role)) {
            $user = Auth::user();
            $roleName = ucwords(str_replace('_', ' ', $role));
            log_activity($user['id'], $user['restaurant_id'], 'login', $roleName . ' logged in');
            flash_set('success', 'Welcome back, ' . $user['name'] . '!');
            redirect(url($redirectPath));
        }

        flash_set('error', 'Invalid email or password.');
        flash_set('_old_input', ['email' => $email]);
        redirect(url($loginPath));
    }
}
