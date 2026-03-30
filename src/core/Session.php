<?php
/**
 * Session - Secure session handling with flash message support.
 */
class Session
{
    private static bool $started = false;

    /**
     * Start the session with secure defaults.
     */
    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $appConfig = file_exists(__DIR__ . '/../config/app.php')
            ? require __DIR__ . '/../config/app.php'
            : [];

        $lifetime = ($appConfig['session_lifetime'] ?? 120) * 60;

        // Enforce strict session mode to prevent session fixation via uninitialized IDs
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);

        session_name('qo2qr_session');
        session_start();
        self::$started = true;
    }

    /**
     * Store a value in the session.
     */
    public static function set(string $key, mixed $value): void
    {
        self::ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve a value from the session.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::ensureStarted();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check whether a key exists in the session.
     */
    public static function has(string $key): bool
    {
        self::ensureStarted();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a key from the session.
     */
    public static function remove(string $key): void
    {
        self::ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the entire session.
     */
    public static function destroy(): void
    {
        self::ensureStarted();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$started = false;
    }

    /**
     * Regenerate the session ID (call on login to prevent session fixation).
     */
    public static function regenerate(): void
    {
        self::ensureStarted();
        session_regenerate_id(true);
    }

    /**
     * Set a flash message (available only for the next request).
     */
    public static function flash(string $key, mixed $value): void
    {
        self::ensureStarted();
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Retrieve and remove a flash message.
     */
    public static function getFlash(string $key): mixed
    {
        self::ensureStarted();
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    private static function ensureStarted(): void
    {
        if (!self::$started) {
            self::start();
        }
    }
}
