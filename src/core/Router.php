<?php
/**
 * Router - Simple request router with named parameters.
 *
 * Maps HTTP method + URL path to controller actions or callables.
 */
class Router
{
    /** @var array<string, array{pattern: string, handler: callable|string, paramNames: string[]}> */
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $path, callable|string $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a POST route.
     */
    public function post(string $path, callable|string $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Dispatch the current request to a matching route.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = '/' . trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = [];
                foreach ($route['paramNames'] as $name) {
                    if (isset($matches[$name])) {
                        $params[$name] = $matches[$name];
                    }
                }

                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        // No matching route – 404
        http_response_code(404);
        if (file_exists(__DIR__ . '/../views/errors/404.php')) {
            require __DIR__ . '/../views/errors/404.php';
        } else {
            echo '404 Not Found';
        }
    }

    // ------------------------------------------------------------------
    // Internal helpers
    // ------------------------------------------------------------------

    private function addRoute(string $method, string $path, callable|string $handler): self
    {
        $paramNames = [];

        // Convert {param} placeholders to named regex groups
        $pattern = preg_replace_callback('/\{([a-zA-Z_]+)\}/', function (array $m) use (&$paramNames): string {
            $paramNames[] = $m[1];
            return '(?P<' . $m[1] . '>[^/]+)';
        }, $path);

        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $pattern,
            'handler'    => $handler,
            'paramNames' => $paramNames,
        ];

        return $this;
    }

    /**
     * Resolve and invoke a route handler.
     *
     * @param callable|string $handler  Either a callable or 'ControllerClass@method'.
     * @param array<string, string> $params  Named URL parameters.
     */
    private function callHandler(callable|string $handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        // String handler: 'ControllerClass@method'
        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);

            // Attempt to auto-include the controller file
            $this->requireController($class);

            if (!class_exists($class)) {
                throw new \RuntimeException("Controller class {$class} not found.");
            }

            $controller = new $class();

            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found on {$class}.");
            }

            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new \RuntimeException('Invalid route handler.');
    }

    /**
     * Attempt to load a controller file by class name.
     *
     * Searches src/controllers/ for a matching PHP file, supporting
     * sub-directories via backslash or forward-slash separators
     * (e.g. "SuperAdmin\DashboardController" => controllers/SuperAdmin/DashboardController.php).
     */
    private function requireController(string $class): void
    {
        if (class_exists($class)) {
            return;
        }

        $relative = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $class);
        $file     = __DIR__ . '/../controllers/' . $relative . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}
