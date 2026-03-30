SET NAMES utf8mb4;
USE qr_order;
SET FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------
-- Schema
-- --------------------------------------------------------

DROP TABLE IF EXISTS `table_form_assignments`;
DROP TABLE IF EXISTS `menu_forms`;
DROP TABLE IF EXISTS `qr_codes`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `waiter_table_assignments`;
DROP TABLE IF EXISTS `restaurant_tables`;
DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `restaurants`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `plans`;

-- plans
CREATE TABLE `plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `max_tables` INT UNSIGNED NOT NULL DEFAULT 0,
  `max_waiters` INT UNSIGNED NOT NULL DEFAULT 0,
  `price_monthly` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plans_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- users
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('super_admin','customer_admin','waiter') NOT NULL DEFAULT 'customer_admin',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- restaurants
CREATE TABLE `restaurants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `phone` VARCHAR(50) NULL DEFAULT NULL,
  `address` VARCHAR(500) NULL DEFAULT NULL,
  `latitude` DECIMAL(10,8) NULL DEFAULT NULL,
  `longitude` DECIMAL(11,8) NULL DEFAULT NULL,
  `allowed_order_radius_km` DECIMAL(5,2) NOT NULL DEFAULT 2.00,
  `location_restriction_enabled` TINYINT NOT NULL DEFAULT 1,
  `plan_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurants_slug_unique` (`slug`),
  KEY `restaurants_user_id_foreign` (`user_id`),
  KEY `restaurants_plan_id_foreign` (`plan_id`),
  CONSTRAINT `restaurants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `restaurants_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- back-reference from users to restaurants
ALTER TABLE `users`
  ADD KEY `users_restaurant_id_foreign` (`restaurant_id`),
  ADD CONSTRAINT `users_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL;

-- subscriptions
CREATE TABLE `subscriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `plan_id` BIGINT UNSIGNED NOT NULL,
  `starts_at` DATE NOT NULL,
  `ends_at` DATE NOT NULL,
  `status` ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subscriptions_restaurant_id_foreign` (`restaurant_id`),
  KEY `subscriptions_plan_id_foreign` (`plan_id`),
  CONSTRAINT `subscriptions_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- restaurant_tables
CREATE TABLE `restaurant_tables` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `capacity` INT UNSIGNED NOT NULL DEFAULT 4,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurant_tables_token_unique` (`token`),
  KEY `restaurant_tables_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `restaurant_tables_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- waiter_table_assignments
CREATE TABLE `waiter_table_assignments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `waiter_id` BIGINT UNSIGNED NOT NULL,
  `table_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `wta_waiter_id_foreign` (`waiter_id`),
  KEY `wta_table_id_foreign` (`table_id`),
  CONSTRAINT `wta_waiter_id_foreign` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wta_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- product_categories
CREATE TABLE `product_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_categories_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `product_categories_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- products
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image` VARCHAR(500) NULL DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `products_restaurant_id_foreign` (`restaurant_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- orders
CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `table_id` BIGINT UNSIGNED NOT NULL,
  `waiter_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_latitude` DECIMAL(10,8) NULL DEFAULT NULL,
  `customer_longitude` DECIMAL(11,8) NULL DEFAULT NULL,
  `form_data` JSON NULL DEFAULT NULL,
  `status` ENUM('pending','confirmed','preparing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `orders_restaurant_id_foreign` (`restaurant_id`),
  KEY `orders_table_id_foreign` (`table_id`),
  KEY `orders_waiter_id_foreign` (`waiter_id`),
  CONSTRAINT `orders_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_waiter_id_foreign` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- order_items
CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- payments
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `subscription_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `method` VARCHAR(50) NOT NULL DEFAULT 'credit_card',
  `status` ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `payments_restaurant_id_foreign` (`restaurant_id`),
  KEY `payments_subscription_id_foreign` (`subscription_id`),
  CONSTRAINT `payments_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- activity_logs
CREATE TABLE `activity_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `restaurant_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activity_logs_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- qr_codes
CREATE TABLE `qr_codes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_id` BIGINT UNSIGNED NOT NULL,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `qr_url` VARCHAR(500) NOT NULL,
  `qr_image_path` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `qr_codes_table_id_foreign` (`table_id`),
  KEY `qr_codes_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `qr_codes_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `qr_codes_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- menu_forms
CREATE TABLE `menu_forms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `form_json` JSON NOT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_forms_restaurant_id_foreign` (`restaurant_id`),
  CONSTRAINT `menu_forms_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- table_form_assignments
CREATE TABLE `table_form_assignments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_id` BIGINT UNSIGNED NOT NULL,
  `form_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tfa_table_id_foreign` (`table_id`),
  KEY `tfa_form_id_foreign` (`form_id`),
  CONSTRAINT `tfa_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tfa_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `menu_forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Seed Data
-- --------------------------------------------------------

-- Plans (id 1-3)
INSERT INTO `plans` (`id`, `name`, `slug`, `max_tables`, `max_waiters`, `price_monthly`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Free',  'free', 5,   1,  0.00, 'active', '2025-01-01 00:00:00', '2025-01-01 00:00:00'),
(2, 'Pro',   'pro',  50,  5, 10.00, 'active', '2025-01-01 00:00:00', '2025-01-01 00:00:00'),
(3, 'Plus',  'plus', 500, 50, 25.00, 'active', '2025-01-01 00:00:00', '2025-01-01 00:00:00');

-- Users (id 1 = super admin, 2-4 = customer admins, 5-10 = waiters)
INSERT INTO `users` (`id`, `restaurant_id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1,  NULL, 'Super Admin',    'admin@qrorder.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin',    'active', '2025-01-01 00:00:00', '2025-01-01 00:00:00'),
(2,  NULL, 'Mario Rossi',    'customer1@test.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer_admin', 'active', '2025-01-02 10:00:00', '2025-01-02 10:00:00'),
(3,  NULL, 'Kenji Tanaka',   'customer2@test.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer_admin', 'active', '2025-01-03 10:00:00', '2025-01-03 10:00:00'),
(4,  NULL, 'John Smith',     'customer3@test.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer_admin', 'active', '2025-01-04 10:00:00', '2025-01-04 10:00:00'),
(5,  NULL, 'Luigi Bianchi',  'waiter1@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00'),
(6,  NULL, 'Yuki Sato',      'waiter2@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00'),
(7,  NULL, 'Hana Suzuki',    'waiter3@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00'),
(8,  NULL, 'Mike Johnson',   'waiter4@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00'),
(9,  NULL, 'Sarah Williams', 'waiter5@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00'),
(10, NULL, 'Tom Davis',      'waiter6@test.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter',         'active', '2025-01-05 10:00:00', '2025-01-05 10:00:00');

-- Restaurants (id 1-3)
INSERT INTO `restaurants` (`id`, `user_id`, `name`, `slug`, `email`, `phone`, `address`, `latitude`, `longitude`, `allowed_order_radius_km`, `location_restriction_enabled`, `plan_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'The Italian Place', 'the-italian-place', 'info@italianplace.com',  '+1-212-555-0101', '123 Mulberry St, New York, NY 10013',   40.71280000, -74.00600000, 2.00, 1, 1, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2, 3, 'Sushi Master',      'sushi-master',      'info@sushimaster.com',   '+1-310-555-0202', '456 Sawtelle Blvd, Los Angeles, CA 90025', 34.05220000, -118.24370000, 3.00, 1, 2, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(3, 4, 'Burger House',      'burger-house',      'info@burgerhouse.com',   '+1-312-555-0303', '789 Michigan Ave, Chicago, IL 60611',    41.87810000, -87.62980000, 5.00, 1, 3, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Link users back to their restaurants
UPDATE `users` SET `restaurant_id` = 1  WHERE `id` = 2;
UPDATE `users` SET `restaurant_id` = 2  WHERE `id` = 3;
UPDATE `users` SET `restaurant_id` = 3  WHERE `id` = 4;
UPDATE `users` SET `restaurant_id` = 1  WHERE `id` = 5;
UPDATE `users` SET `restaurant_id` = 2  WHERE `id` IN (6, 7);
UPDATE `users` SET `restaurant_id` = 3  WHERE `id` IN (8, 9, 10);

-- Subscriptions (id 1-3)
INSERT INTO `subscriptions` (`id`, `restaurant_id`, `plan_id`, `starts_at`, `ends_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-01-02', '2026-01-02', 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2, 2, 2, '2025-01-03', '2026-01-03', 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(3, 3, 3, '2025-01-04', '2026-01-04', 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Restaurant Tables
-- Italian Place: 3 tables (id 1-3)
-- Sushi Master: 4 tables (id 4-7)
-- Burger House: 5 tables (id 8-12)
INSERT INTO `restaurant_tables` (`id`, `restaurant_id`, `name`, `token`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
(1,  1, 'Table 1',  'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', 4, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2,  1, 'Table 2',  'b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7', 2, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(3,  1, 'Table 3',  'c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8', 6, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(4,  2, 'Bar Seat 1',    'd4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9', 1, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(5,  2, 'Bar Seat 2',    'e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0', 1, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(6,  2, 'Booth A',       'f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1', 4, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(7,  2, 'Booth B',       'g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2', 4, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(8,  3, 'Patio 1',       'h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3', 4, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(9,  3, 'Patio 2',       'i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4', 4, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(10, 3, 'Indoor 1',      'j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5', 6, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(11, 3, 'Indoor 2',      'k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6', 6, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(12, 3, 'VIP Room',       'l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7', 8, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Waiter Table Assignments
-- Waiter 5 (Italian Place) -> tables 1,2,3
-- Waiter 6 (Sushi Master) -> tables 4,5   |  Waiter 7 -> tables 6,7
-- Waiter 8 (Burger House) -> tables 8,9   |  Waiter 9 -> tables 10,11  |  Waiter 10 -> table 12
INSERT INTO `waiter_table_assignments` (`id`, `waiter_id`, `table_id`, `created_at`) VALUES
(1,  5, 1,  '2025-01-06 08:00:00'),
(2,  5, 2,  '2025-01-06 08:00:00'),
(3,  5, 3,  '2025-01-06 08:00:00'),
(4,  6, 4,  '2025-01-06 08:00:00'),
(5,  6, 5,  '2025-01-06 08:00:00'),
(6,  7, 6,  '2025-01-06 08:00:00'),
(7,  7, 7,  '2025-01-06 08:00:00'),
(8,  8, 8,  '2025-01-06 08:00:00'),
(9,  8, 9,  '2025-01-06 08:00:00'),
(10, 9, 10, '2025-01-06 08:00:00'),
(11, 9, 11, '2025-01-06 08:00:00'),
(12, 10, 12, '2025-01-06 08:00:00');

-- Product Categories
-- Italian Place (restaurant 1): Antipasti(1), Pasta(2), Pizza(3), Drinks(4)
-- Sushi Master (restaurant 2): Nigiri(5), Rolls(6), Ramen(7), Beverages(8)
-- Burger House (restaurant 3): Burgers(9), Sides(10), Desserts(11), Drinks(12)
INSERT INTO `product_categories` (`id`, `restaurant_id`, `name`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1,  1, 'Antipasti',  1, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2,  1, 'Pasta',      2, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(3,  1, 'Pizza',      3, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(4,  1, 'Drinks',     4, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(5,  2, 'Nigiri',     1, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(6,  2, 'Rolls',      2, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(7,  2, 'Ramen',      3, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(8,  2, 'Beverages',  4, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(9,  3, 'Burgers',    1, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(10, 3, 'Sides',      2, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(11, 3, 'Desserts',   3, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(12, 3, 'Drinks',     4, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Products
-- Italian Place (restaurant 1)
INSERT INTO `products` (`id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1,  1, 1, 'Bruschetta',           'Toasted bread with fresh tomatoes, garlic, and basil',       8.50,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2,  1, 1, 'Caprese Salad',        'Fresh mozzarella, tomatoes, and basil with balsamic glaze', 10.00,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(3,  1, 2, 'Spaghetti Carbonara',  'Classic Roman pasta with egg, pecorino, and guanciale',     14.50,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(4,  1, 2, 'Penne Arrabbiata',     'Penne in spicy tomato sauce with garlic and chili',         12.00,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(5,  1, 3, 'Margherita Pizza',     'San Marzano tomatoes, fresh mozzarella, basil',             13.00,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(6,  1, 3, 'Quattro Formaggi',     'Four cheese pizza with mozzarella, gorgonzola, parmesan, fontina', 15.00, NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(7,  1, 4, 'Sparkling Water',      '750ml bottle of sparkling mineral water',                    3.50,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(8,  1, 4, 'House Red Wine',       'Glass of Chianti Classico',                                  9.00,  NULL, 'active', '2025-01-02 12:00:00', '2025-01-02 12:00:00');

-- Sushi Master (restaurant 2)
INSERT INTO `products` (`id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(9,  2, 5, 'Salmon Nigiri',        'Two pieces of fresh Atlantic salmon over sushi rice',        6.00,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(10, 2, 5, 'Tuna Nigiri',          'Two pieces of bluefin tuna over sushi rice',                 7.50,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(11, 2, 5, 'Ebi Nigiri',           'Two pieces of cooked shrimp over sushi rice',                5.50,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(12, 2, 6, 'California Roll',      'Crab, avocado, and cucumber inside-out roll',                9.00,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(13, 2, 6, 'Spicy Tuna Roll',      'Spicy tuna with cucumber and spicy mayo',                  10.50,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(14, 2, 7, 'Tonkotsu Ramen',       'Rich pork bone broth with chashu, egg, and noodles',       15.00,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(15, 2, 7, 'Miso Ramen',           'Fermented soybean broth with corn, butter, and noodles',   13.50,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(16, 2, 8, 'Green Tea',            'Hot Japanese green tea',                                     2.50,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(17, 2, 8, 'Sake',                 'House sake, served warm or cold',                            8.00,  NULL, 'active', '2025-01-03 12:00:00', '2025-01-03 12:00:00');

-- Burger House (restaurant 3)
INSERT INTO `products` (`id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(18, 3, 9,  'Classic Burger',       'Angus beef patty with lettuce, tomato, onion, and pickles', 11.00,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(19, 3, 9,  'Bacon Cheeseburger',   'Double patty with cheddar, crispy bacon, and special sauce', 14.50, NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(20, 3, 9,  'Veggie Burger',        'Plant-based patty with avocado, sprouts, and chipotle aioli', 12.00, NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(21, 3, 10, 'French Fries',         'Crispy golden fries with sea salt',                          4.50,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(22, 3, 10, 'Onion Rings',          'Beer-battered onion rings with ranch dip',                   5.50,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(23, 3, 10, 'Coleslaw',             'Creamy homemade coleslaw',                                   3.50,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(24, 3, 11, 'Chocolate Brownie',    'Warm chocolate brownie with vanilla ice cream',              7.00,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(25, 3, 11, 'Milkshake',            'Thick milkshake — vanilla, chocolate, or strawberry',        6.50,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(26, 3, 12, 'Craft Beer',           'Local IPA on tap',                                           6.00,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(27, 3, 12, 'Soft Drink',           'Coca-Cola, Sprite, or Fanta',                                3.00,  NULL, 'active', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Menu Forms (id 1-3, one per restaurant)
INSERT INTO `menu_forms` (`id`, `restaurant_id`, `name`, `form_json`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Italian Order Form', JSON_ARRAY(
  JSON_OBJECT('type', 'text',     'name', 'customer_name',  'label', 'Your Name',            'required', true,  'placeholder', 'Enter your name'),
  JSON_OBJECT('type', 'select',   'name', 'party_size',     'label', 'Party Size',            'required', true,  'options', JSON_ARRAY('1','2','3','4','5','6')),
  JSON_OBJECT('type', 'select',   'name', 'course_pref',    'label', 'Start With',            'required', false, 'options', JSON_ARRAY('Antipasti','Primo','Secondo')),
  JSON_OBJECT('type', 'textarea', 'name', 'allergies',      'label', 'Allergies / Dietary',   'required', false, 'placeholder', 'e.g. gluten-free, nut allergy'),
  JSON_OBJECT('type', 'select',   'name', 'wine_pairing',   'label', 'Wine Pairing?',         'required', false, 'options', JSON_ARRAY('Yes','No')),
  JSON_OBJECT('type', 'textarea', 'name', 'special_request','label', 'Special Requests',      'required', false, 'placeholder', 'Any special requests?'),
  JSON_OBJECT('type', 'button',   'name', 'submit',         'label', 'Place Order',           'action', 'submit')
), 'active', '2025-01-02 14:00:00', '2025-01-02 14:00:00'),

(2, 2, 'Sushi Order Form', JSON_ARRAY(
  JSON_OBJECT('type', 'text',     'name', 'customer_name',  'label', 'Your Name',             'required', true,  'placeholder', 'Enter your name'),
  JSON_OBJECT('type', 'select',   'name', 'seating',        'label', 'Seating Preference',    'required', true,  'options', JSON_ARRAY('Bar','Booth','No preference')),
  JSON_OBJECT('type', 'select',   'name', 'spice_level',    'label', 'Spice Level',           'required', false, 'options', JSON_ARRAY('Mild','Medium','Hot','Extra Hot')),
  JSON_OBJECT('type', 'textarea', 'name', 'allergies',      'label', 'Allergies',             'required', false, 'placeholder', 'Shellfish, soy, etc.'),
  JSON_OBJECT('type', 'select',   'name', 'drink_pref',     'label', 'Drink With Meal?',      'required', false, 'options', JSON_ARRAY('Green Tea','Sake','Water','None')),
  JSON_OBJECT('type', 'textarea', 'name', 'notes',          'label', 'Additional Notes',      'required', false, 'placeholder', 'Anything we should know?'),
  JSON_OBJECT('type', 'button',   'name', 'submit',         'label', 'Submit Order',          'action', 'submit')
), 'active', '2025-01-03 14:00:00', '2025-01-03 14:00:00'),

(3, 3, 'Burger Order Form', JSON_ARRAY(
  JSON_OBJECT('type', 'text',     'name', 'customer_name',  'label', 'Your Name',             'required', true,  'placeholder', 'Enter your name'),
  JSON_OBJECT('type', 'select',   'name', 'cook_level',     'label', 'Burger Doneness',       'required', true,  'options', JSON_ARRAY('Rare','Medium Rare','Medium','Well Done')),
  JSON_OBJECT('type', 'select',   'name', 'bun_type',       'label', 'Bun Type',              'required', false, 'options', JSON_ARRAY('Classic','Brioche','Lettuce Wrap','Gluten-Free')),
  JSON_OBJECT('type', 'textarea', 'name', 'extra_toppings', 'label', 'Extra Toppings',        'required', false, 'placeholder', 'Jalapeños, mushrooms, etc.'),
  JSON_OBJECT('type', 'select',   'name', 'combo',          'label', 'Make it a Combo?',      'required', false, 'options', JSON_ARRAY('No','With Fries','With Rings','With Both')),
  JSON_OBJECT('type', 'textarea', 'name', 'notes',          'label', 'Special Requests',      'required', false, 'placeholder', 'Any special requests?'),
  JSON_OBJECT('type', 'button',   'name', 'submit',         'label', 'Order Now',             'action', 'submit')
), 'active', '2025-01-04 14:00:00', '2025-01-04 14:00:00');

-- Table Form Assignments (every table gets its restaurant's form)
INSERT INTO `table_form_assignments` (`id`, `table_id`, `form_id`, `created_at`, `updated_at`) VALUES
(1,  1,  1, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(2,  2,  1, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(3,  3,  1, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(4,  4,  2, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(5,  5,  2, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(6,  6,  2, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(7,  7,  2, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(8,  8,  3, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(9,  9,  3, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(10, 10, 3, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(11, 11, 3, '2025-01-06 08:00:00', '2025-01-06 08:00:00'),
(12, 12, 3, '2025-01-06 08:00:00', '2025-01-06 08:00:00');

-- QR Codes (one per table)
INSERT INTO `qr_codes` (`id`, `table_id`, `restaurant_id`, `qr_url`, `qr_image_path`, `created_at`, `updated_at`) VALUES
(1,  1,  1, 'https://qrorder.com/order/the-italian-place?token=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6', '/qr/1.png',  '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(2,  2,  1, 'https://qrorder.com/order/the-italian-place?token=b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7', '/qr/2.png',  '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(3,  3,  1, 'https://qrorder.com/order/the-italian-place?token=c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8', '/qr/3.png',  '2025-01-02 12:00:00', '2025-01-02 12:00:00'),
(4,  4,  2, 'https://qrorder.com/order/sushi-master?token=d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9',      '/qr/4.png',  '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(5,  5,  2, 'https://qrorder.com/order/sushi-master?token=e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0',      '/qr/5.png',  '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(6,  6,  2, 'https://qrorder.com/order/sushi-master?token=f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1',      '/qr/6.png',  '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(7,  7,  2, 'https://qrorder.com/order/sushi-master?token=g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2',      '/qr/7.png',  '2025-01-03 12:00:00', '2025-01-03 12:00:00'),
(8,  8,  3, 'https://qrorder.com/order/burger-house?token=h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3',      '/qr/8.png',  '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(9,  9,  3, 'https://qrorder.com/order/burger-house?token=i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4',      '/qr/9.png',  '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(10, 10, 3, 'https://qrorder.com/order/burger-house?token=j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5',      '/qr/10.png', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(11, 11, 3, 'https://qrorder.com/order/burger-house?token=k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6',      '/qr/11.png', '2025-01-04 12:00:00', '2025-01-04 12:00:00'),
(12, 12, 3, 'https://qrorder.com/order/burger-house?token=l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7',      '/qr/12.png', '2025-01-04 12:00:00', '2025-01-04 12:00:00');

-- Orders
-- Italian Place: 2 orders (id 1-2)
INSERT INTO `orders` (`id`, `restaurant_id`, `table_id`, `waiter_id`, `customer_name`, `customer_latitude`, `customer_longitude`, `form_data`, `status`, `total`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'Alice Johnson',  40.71285000, -74.00605000,
  '{"customer_name":"Alice Johnson","party_size":"2","course_pref":"Antipasti","allergies":"None","wine_pairing":"Yes"}',
  'delivered', 45.50, 'Anniversary dinner', '2025-01-10 19:00:00', '2025-01-10 20:30:00'),
(2, 1, 3, 5, 'Bob Martinez',   40.71270000, -74.00590000,
  '{"customer_name":"Bob Martinez","party_size":"4","course_pref":"Primo","allergies":"Lactose intolerant","wine_pairing":"No"}',
  'preparing', 54.00, NULL, '2025-01-11 18:30:00', '2025-01-11 19:00:00');

-- Sushi Master: 3 orders (id 3-5)
INSERT INTO `orders` (`id`, `restaurant_id`, `table_id`, `waiter_id`, `customer_name`, `customer_latitude`, `customer_longitude`, `form_data`, `status`, `total`, `notes`, `created_at`, `updated_at`) VALUES
(3, 2, 4, 6, 'Charlie Lee',    34.05230000, -118.24380000,
  '{"customer_name":"Charlie Lee","seating":"Bar","spice_level":"Hot","allergies":"","drink_pref":"Sake"}',
  'delivered', 31.50, NULL, '2025-01-12 12:00:00', '2025-01-12 13:00:00'),
(4, 2, 6, 7, 'Diana Park',     34.05210000, -118.24360000,
  '{"customer_name":"Diana Park","seating":"Booth","spice_level":"Mild","allergies":"Shellfish","drink_pref":"Green Tea"}',
  'confirmed', 28.00, 'No raw shrimp please', '2025-01-12 18:00:00', '2025-01-12 18:15:00'),
(5, 2, 7, 7, 'Ethan Wong',     34.05250000, -118.24400000,
  '{"customer_name":"Ethan Wong","seating":"Booth","spice_level":"Medium","allergies":"","drink_pref":"None"}',
  'pending', 15.00, NULL, '2025-01-12 19:00:00', '2025-01-12 19:00:00');

-- Burger House: 3 orders (id 6-8)
INSERT INTO `orders` (`id`, `restaurant_id`, `table_id`, `waiter_id`, `customer_name`, `customer_latitude`, `customer_longitude`, `form_data`, `status`, `total`, `notes`, `created_at`, `updated_at`) VALUES
(6, 3, 8,  8,  'Fiona Green',   41.87820000, -87.62990000,
  '{"customer_name":"Fiona Green","cook_level":"Medium Rare","bun_type":"Brioche","extra_toppings":"Mushrooms","combo":"With Fries"}',
  'delivered', 22.50, NULL, '2025-01-13 12:30:00', '2025-01-13 13:15:00'),
(7, 3, 10, 9,  'George Brown',  41.87800000, -87.62970000,
  '{"customer_name":"George Brown","cook_level":"Well Done","bun_type":"Classic","extra_toppings":"","combo":"With Both"}',
  'ready', 30.00, 'Extra ketchup on the side', '2025-01-13 18:00:00', '2025-01-13 18:45:00'),
(8, 3, 12, 10, 'Hannah White',  41.87790000, -87.62960000,
  '{"customer_name":"Hannah White","cook_level":"Medium","bun_type":"Lettuce Wrap","extra_toppings":"Jalapeños, avocado","combo":"No"}',
  'cancelled', 12.00, 'Cancelled — customer left', '2025-01-14 19:00:00', '2025-01-14 19:05:00');

-- Order Items
-- Order 1 (Italian Place — Alice): Bruschetta x1, Spaghetti Carbonara x1, House Red Wine x2
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(1,  1, 1, 'Bruschetta',          1,  8.50, NULL, '2025-01-10 19:00:00'),
(2,  1, 3, 'Spaghetti Carbonara', 1, 14.50, 'Extra pecorino', '2025-01-10 19:00:00'),
(3,  1, 8, 'House Red Wine',      2,  9.00, NULL, '2025-01-10 19:00:00');

-- Order 2 (Italian Place — Bob): Caprese x2, Penne Arrabbiata x2, Margherita Pizza x1, Sparkling Water x2
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(4,  2, 2, 'Caprese Salad',       2, 10.00, NULL, '2025-01-11 18:30:00'),
(5,  2, 4, 'Penne Arrabbiata',    2, 12.00, 'Not too spicy', '2025-01-11 18:30:00'),
(6,  2, 5, 'Margherita Pizza',    1, 13.00, NULL, '2025-01-11 18:30:00'),
(7,  2, 7, 'Sparkling Water',     2,  3.50, NULL, '2025-01-11 18:30:00');

-- Order 3 (Sushi — Charlie): Salmon Nigiri x2, Spicy Tuna Roll x1, Sake x1
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(8,  3, 9,  'Salmon Nigiri',   2,  6.00, NULL, '2025-01-12 12:00:00'),
(9,  3, 13, 'Spicy Tuna Roll', 1, 10.50, 'Extra spicy mayo', '2025-01-12 12:00:00'),
(10, 3, 17, 'Sake',            1,  8.00, 'Cold please', '2025-01-12 12:00:00');

-- Order 4 (Sushi — Diana): California Roll x1, Tonkotsu Ramen x1, Green Tea x1
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(11, 4, 12, 'California Roll',  1,  9.00, 'No real crab please', '2025-01-12 18:00:00'),
(12, 4, 14, 'Tonkotsu Ramen',   1, 15.00, 'Extra noodles', '2025-01-12 18:00:00'),
(13, 4, 16, 'Green Tea',        1,  2.50, NULL, '2025-01-12 18:00:00');

-- Order 5 (Sushi — Ethan): Miso Ramen x1
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(14, 5, 15, 'Miso Ramen', 1, 13.50, NULL, '2025-01-12 19:00:00');

-- Order 6 (Burger — Fiona): Bacon Cheeseburger x1, French Fries x1, Soft Drink x1
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(15, 6, 19, 'Bacon Cheeseburger', 1, 14.50, NULL, '2025-01-13 12:30:00'),
(16, 6, 21, 'French Fries',       1,  4.50, NULL, '2025-01-13 12:30:00'),
(17, 6, 27, 'Soft Drink',         1,  3.00, 'Coca-Cola', '2025-01-13 12:30:00');

-- Order 7 (Burger — George): Classic Burger x1, Bacon Cheeseburger x1, Onion Rings x1, French Fries x1, Craft Beer x2
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(18, 7, 18, 'Classic Burger',     1, 11.00, NULL, '2025-01-13 18:00:00'),
(19, 7, 19, 'Bacon Cheeseburger', 1, 14.50, 'No pickles', '2025-01-13 18:00:00'),
(20, 7, 22, 'Onion Rings',        1,  5.50, NULL, '2025-01-13 18:00:00'),
(21, 7, 21, 'French Fries',       1,  4.50, NULL, '2025-01-13 18:00:00'),
(22, 7, 26, 'Craft Beer',         2,  6.00, NULL, '2025-01-13 18:00:00');

-- Order 8 (Burger — Hannah): Veggie Burger x1 (cancelled)
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `item_name`, `quantity`, `price`, `notes`, `created_at`) VALUES
(23, 8, 20, 'Veggie Burger', 1, 12.00, NULL, '2025-01-14 19:00:00');

-- Payments (one per subscription)
INSERT INTO `payments` (`id`, `restaurant_id`, `subscription_id`, `amount`, `method`, `status`, `transaction_id`, `created_at`) VALUES
(1, 1, 1,  0.00, 'free',        'completed', 'TXN-FREE-0001',       '2025-01-02 12:00:00'),
(2, 2, 2, 10.00, 'credit_card', 'completed', 'TXN-STRIPE-A2B3C4D5', '2025-01-03 12:00:00'),
(3, 3, 3, 25.00, 'credit_card', 'completed', 'TXN-STRIPE-E6F7G8H9', '2025-01-04 12:00:00');

-- Activity Logs
INSERT INTO `activity_logs` (`id`, `user_id`, `restaurant_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1,  1, NULL, 'login',               'Super admin logged in',                             '127.0.0.1',     '2025-01-01 08:00:00'),
(2,  1, NULL, 'create_plan',         'Created Free plan',                                 '127.0.0.1',     '2025-01-01 08:05:00'),
(3,  1, NULL, 'create_plan',         'Created Pro plan',                                  '127.0.0.1',     '2025-01-01 08:06:00'),
(4,  1, NULL, 'create_plan',         'Created Plus plan',                                 '127.0.0.1',     '2025-01-01 08:07:00'),
(5,  2, 1,    'register',            'Registered restaurant The Italian Place',            '203.0.113.10',  '2025-01-02 10:00:00'),
(6,  2, 1,    'create_table',        'Created Table 1, Table 2, Table 3',                  '203.0.113.10',  '2025-01-02 12:05:00'),
(7,  2, 1,    'add_waiter',          'Added waiter Luigi Bianchi',                          '203.0.113.10',  '2025-01-05 10:05:00'),
(8,  3, 2,    'register',            'Registered restaurant Sushi Master',                  '198.51.100.20', '2025-01-03 10:00:00'),
(9,  3, 2,    'create_table',        'Created Bar Seat 1, Bar Seat 2, Booth A, Booth B',    '198.51.100.20', '2025-01-03 12:05:00'),
(10, 3, 2,    'add_waiter',          'Added waiters Yuki Sato and Hana Suzuki',             '198.51.100.20', '2025-01-05 10:10:00'),
(11, 4, 3,    'register',            'Registered restaurant Burger House',                   '192.0.2.30',    '2025-01-04 10:00:00'),
(12, 4, 3,    'create_table',        'Created Patio 1, Patio 2, Indoor 1, Indoor 2, VIP Room', '192.0.2.30', '2025-01-04 12:05:00'),
(13, 4, 3,    'add_waiter',          'Added waiters Mike Johnson, Sarah Williams, Tom Davis',   '192.0.2.30', '2025-01-05 10:15:00'),
(14, 5, 1,    'order_delivered',     'Marked order #1 as delivered',                         '203.0.113.11',  '2025-01-10 20:30:00'),
(15, 7, 2,    'order_confirmed',     'Confirmed order #4',                                   '198.51.100.21', '2025-01-12 18:15:00'),
(16, 8, 3,    'order_delivered',     'Marked order #6 as delivered',                         '192.0.2.31',    '2025-01-13 13:15:00'),
(17, 10, 3,   'order_cancelled',     'Cancelled order #8 — customer left',                  '192.0.2.32',    '2025-01-14 19:05:00');

SET FOREIGN_KEY_CHECKS=1;
