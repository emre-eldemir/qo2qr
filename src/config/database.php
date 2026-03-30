<?php
/**
 * Database Configuration
 */
return [
    'host' => getenv('DB_HOST') ?: 'db',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'qr_order',
    'username' => getenv('DB_USERNAME') ?: 'qr_user',
    'password' => getenv('DB_PASSWORD') ?: 'qr_secret',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
