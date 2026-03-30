<?php
/**
 * Application Configuration
 */
return [
    'name' => getenv('APP_NAME') ?: 'Restaurant QR Order Platform',
    'env' => getenv('APP_ENV') ?: 'local',
    'url' => getenv('APP_URL') ?: 'http://localhost:8080',
    'timezone' => 'UTC',
    'session_lifetime' => 120, // minutes
    'rate_limit' => [
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],
    'form_builder' => [
        'max_fields' => 7,
        'allowed_types' => ['text', 'select', 'textarea', 'button'],
    ],
];
