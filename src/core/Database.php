<?php
/**
 * Database - Singleton PDO wrapper
 *
 * Provides a single shared PDO connection with prepared statement support.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    private ?PDOStatement $stmt = null;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        $allowedCharsets    = ['utf8', 'utf8mb4', 'latin1', 'ascii'];
        $allowedCollations  = ['utf8mb4_unicode_ci', 'utf8mb4_general_ci', 'utf8_general_ci', 'latin1_swedish_ci'];
        $charset   = in_array($config['charset'], $allowedCharsets, true) ? $config['charset'] : 'utf8mb4';
        $collation = in_array($config['collation'], $allowedCollations, true) ? $config['collation'] : 'utf8mb4_unicode_ci';

        // Safe: values are strictly whitelisted above, so literal concatenation is acceptable.
        $this->pdo->exec('SET NAMES ' . $charset . ' COLLATE ' . $collation);
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Execute a query with optional bound parameters.
     */
    public function query(string $sql, array $params = []): self
    {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($params);
        return $this;
    }

    /**
     * Fetch a single row from the last executed statement.
     */
    public function fetch(): array|false
    {
        return $this->stmt->fetch();
    }

    /**
     * Fetch all rows from the last executed statement.
     */
    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }

    /**
     * Return the ID of the last inserted row.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Return the number of rows affected by the last statement.
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    /**
     * Get the underlying PDO instance for advanced usage.
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
