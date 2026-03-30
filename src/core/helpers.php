<?php
/**
 * Global helper functions for the Restaurant QR Order Platform.
 */

/**
 * Escape a string for safe HTML output (XSS prevention).
 */
function h(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Send a redirect header and terminate the script.
 */
function redirect(string $url): never
{
    // Strip control characters to prevent header injection (CRLF)
    $url = str_replace(["\r", "\n", "\0"], '', $url);
    header('Location: ' . $url);
    exit;
}

/**
 * Build a full URL using the configured base URL.
 */
function url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $config = file_exists(__DIR__ . '/../config/app.php')
            ? require __DIR__ . '/../config/app.php'
            : [];
        $base = rtrim($config['url'] ?? '', '/');
    }
    return $base . '/' . ltrim($path, '/');
}

/**
 * Render a view file inside a layout with extracted data.
 *
 * Uses dot notation: 'public.home' => views/public/home.php
 * View files set $layout and $title variables; their HTML output is
 * captured as $content and injected into the layout.
 */
function view(string $name, array $data = []): never
{
    $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
    $file = $basePath . '/views/' . str_replace('.', '/', $name) . '.php';

    if (!file_exists($file)) {
        throw new RuntimeException("View [{$name}] not found at {$file}");
    }

    extract($data, EXTR_SKIP);

    ob_start();
    require $file;
    $content = ob_get_clean();

    $layout = $layout ?? 'public';
    $layoutFile = $basePath . '/views/layouts/' . $layout . '.php';

    if (file_exists($layoutFile)) {
        require $layoutFile;
    } else {
        echo $content;
    }

    exit;
}

/**
 * Send a JSON response with proper headers.
 */
function json_response(mixed $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Retrieve old form input from flash data (useful after validation failures).
 * Caches the flash data so multiple calls within the same request work correctly.
 */
function old(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = Session::getFlash('_old_input') ?? [];
    }
    return isset($cache[$key]) ? (string) $cache[$key] : $default;
}

/**
 * Set a flash message for the next request.
 */
function flash_set(string $key, mixed $value): void
{
    Session::flash($key, $value);
}

/**
 * Get and remove a flash message.
 */
function flash_get(string $key): mixed
{
    return Session::getFlash($key);
}

/**
 * Check whether the current request is an AJAX (XMLHttpRequest) call.
 */
function is_ajax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Return the current request URL path.
 */
function current_url(): string
{
    return $_SERVER['REQUEST_URI'] ?? '/';
}

/**
 * Generate a URL to a static asset (relative to the public directory).
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Format a numeric amount as a price string ($X.XX).
 */
function format_price(float|int|string $amount): string
{
    return '$' . number_format((float) $amount, 2, '.', ',');
}

/**
 * Generate a cryptographically secure random token (hex string).
 */
function generate_token(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

/**
 * Calculate the Haversine distance between two GPS coordinates.
 *
 * @return float Distance in kilometres.
 */
function haversine_distance(
    float $lat1,
    float $lon1,
    float $lat2,
    float $lon2
): float {
    $earthRadius = 6371.0; // km

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) ** 2
       + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

/**
 * Basic rate-limit check using session storage.
 *
 * @param string $key   Unique key identifying the action.
 * @param int    $max   Maximum allowed attempts within the window.
 * @param int    $decay Window size in seconds.
 * @return bool  True if the request is allowed; false if rate-limited.
 */
function rate_limit_check(string $key, int $max = 60, int $decay = 60): bool
{
    Session::start();
    $storeKey = '_rate_limit_' . $key;
    $data = Session::get($storeKey, ['count' => 0, 'expires_at' => 0]);

    $now = time();

    if ($now > $data['expires_at']) {
        $data = ['count' => 1, 'expires_at' => $now + $decay];
        Session::set($storeKey, $data);
        return true;
    }

    if ($data['count'] < $max) {
        $data['count']++;
        Session::set($storeKey, $data);
        return true;
    }

    return false;
}

/**
 * Log an activity to the activity_logs table.
 */
function log_activity(
    int $userId,
    ?int $restaurantId,
    string $action,
    ?string $description = null
): void {
    try {
        Database::getInstance()->query(
            'INSERT INTO activity_logs (user_id, restaurant_id, action, description, ip_address)
             VALUES (?, ?, ?, ?, ?)',
            [
                $userId,
                $restaurantId,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]
        );
    } catch (Throwable) {
        // Silently fail – logging should never break the application.
    }
}
