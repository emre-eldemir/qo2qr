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

        $this->pdo->exec("SET NAMES '{$config['charset']}' COLLATE '{$config['collation']}'");
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
