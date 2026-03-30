<?php
/**
 * CSRF - Cross-Site Request Forgery protection.
 */
class CSRF
{
    private const SESSION_KEY = '_csrf_token';

    /**
     * Generate a new CSRF token and store it in the session.
     */
    public static function generate(): string
    {
        Session::start();
        $token = bin2hex(random_bytes(32));
        Session::set(self::SESSION_KEY, $token);
        return $token;
    }

    /**
     * Return the current CSRF token, generating one if none exists.
     */
    public static function token(): string
    {
        Session::start();
        $token = Session::get(self::SESSION_KEY);
        if ($token === null) {
            $token = self::generate();
        }
        return $token;
    }

    /**
     * Return an HTML hidden input containing the CSRF token.
     */
    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Validate a given token against the one stored in session.
     */
    public static function validate(?string $token): bool
    {
        Session::start();
        $stored = Session::get(self::SESSION_KEY);
        if ($stored === null || $token === null) {
            return false;
        }
        return hash_equals($stored, $token);
    }

    /**
     * Validate the CSRF token from the current POST request.
     * Terminates with 403 if the token is missing or invalid.
     */
    public static function validateRequest(): void
    {
        $token = $_POST['csrf_token'] ?? null;
        if (!self::validate($token)) {
            http_response_code(403);
            echo '403 Forbidden – Invalid CSRF token';
            exit;
        }
    }
}
