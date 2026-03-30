<?php
/**
 * Auth - Static authentication helpers.
 *
 * Manages login / logout, role checks, and access-control guards.
 */
class Auth
{
    /**
     * Attempt to log in a user by email, password, and role.
     *
     * @return bool True on success, false on invalid credentials.
     */
    public static function login(string $email, string $password, string $role): bool
    {
        $db = Database::getInstance();

        $user = $db->query(
            'SELECT * FROM users WHERE email = ? AND role = ? AND status = ? LIMIT 1',
            [$email, $role, 'active']
        )->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        Session::start();
        Session::regenerate();

        Session::set('user', [
            'id'            => (int) $user['id'],
            'restaurant_id' => $user['restaurant_id'] ? (int) $user['restaurant_id'] : null,
            'name'          => $user['name'],
            'email'         => $user['email'],
            'role'          => $user['role'],
        ]);

        return true;
    }

    /**
     * Log the current user out and destroy the session.
     */
    public static function logout(): void
    {
        Session::start();
        Session::destroy();
    }

    /**
     * Check whether a user is currently authenticated.
     */
    public static function check(): bool
    {
        Session::start();
        return Session::has('user');
    }

    /**
     * Return the current user array, or null if not logged in.
     */
    public static function user(): ?array
    {
        Session::start();
        return Session::get('user');
    }

    /**
     * Return the current user's ID.
     */
    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    /**
     * Return the current user's role.
     */
    public static function role(): ?string
    {
        return self::user()['role'] ?? null;
    }

    /**
     * Return the current user's restaurant_id.
     */
    public static function restaurantId(): ?int
    {
        return self::user()['restaurant_id'] ?? null;
    }

    /**
     * Redirect to login page if not authenticated.
     */
    public static function requireAuth(): void
    {
        if (!self::check()) {
            redirect(url('/login'));
        }
    }

    /**
     * Require the current user to have a specific role.
     * Returns a 403 response if the role does not match.
     */
    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if (self::role() !== $role) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function isCustomerAdmin(): bool
    {
        return self::role() === 'customer_admin';
    }

    public static function isWaiter(): bool
    {
        return self::role() === 'waiter';
    }
}
