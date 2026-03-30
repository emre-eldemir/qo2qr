<?php
/**
 * Front Controller - All requests go through here
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Base path
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Load core
require_once BASE_PATH . '/core/helpers.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/CSRF.php';
require_once BASE_PATH . '/core/Router.php';

// Start session
Session::start();

// Initialize router
$router = new Router();

// ========================================
// PUBLIC ROUTES
// ========================================
$router->get('/', 'Public/HomeController@index');
$router->get('/features', 'Public/HomeController@features');
$router->get('/pricing', 'Public/HomeController@pricing');
$router->get('/about', 'Public/HomeController@about');
$router->get('/contact', 'Public/HomeController@contact');
$router->post('/contact', 'Public/HomeController@contactSubmit');

// ========================================
// AUTH ROUTES
// ========================================
$router->get('/login', 'AuthController@loginSelect');
$router->get('/login/admin', 'AuthController@superAdminLogin');
$router->post('/login/admin', 'AuthController@superAdminLoginPost');
$router->get('/login/customer', 'AuthController@customerLogin');
$router->post('/login/customer', 'AuthController@customerLoginPost');
$router->get('/login/waiter', 'AuthController@waiterLogin');
$router->post('/login/waiter', 'AuthController@waiterLoginPost');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');
$router->get('/logout', 'AuthController@logout');

// ========================================
// SUPER ADMIN ROUTES
// ========================================
$router->get('/super-admin', 'SuperAdmin/DashboardController@index');
$router->get('/super-admin/restaurants', 'SuperAdmin/RestaurantController@index');
$router->get('/super-admin/restaurants/view/{id}', 'SuperAdmin/RestaurantController@view');
$router->post('/super-admin/restaurants/update-plan/{id}', 'SuperAdmin/RestaurantController@updatePlan');
$router->post('/super-admin/restaurants/toggle-status/{id}', 'SuperAdmin/RestaurantController@toggleStatus');
$router->get('/super-admin/plans', 'SuperAdmin/PlanController@index');
$router->post('/super-admin/plans/update/{id}', 'SuperAdmin/PlanController@update');
$router->get('/super-admin/payments', 'SuperAdmin/PaymentController@index');
$router->get('/super-admin/reports', 'SuperAdmin/ReportController@index');
$router->get('/super-admin/activity-logs', 'SuperAdmin/ActivityLogController@index');
$router->get('/super-admin/settings', 'SuperAdmin/SettingsController@index');
$router->post('/super-admin/settings', 'SuperAdmin/SettingsController@update');

// ========================================
// CUSTOMER ADMIN ROUTES
// ========================================
$router->get('/customer', 'CustomerAdmin/DashboardController@index');

// Tables
$router->get('/customer/tables', 'CustomerAdmin/TableController@index');
$router->post('/customer/tables/store', 'CustomerAdmin/TableController@store');
$router->post('/customer/tables/update/{id}', 'CustomerAdmin/TableController@update');
$router->post('/customer/tables/delete/{id}', 'CustomerAdmin/TableController@delete');

// Waiters
$router->get('/customer/waiters', 'CustomerAdmin/WaiterController@index');
$router->post('/customer/waiters/store', 'CustomerAdmin/WaiterController@store');
$router->post('/customer/waiters/update/{id}', 'CustomerAdmin/WaiterController@update');
$router->post('/customer/waiters/delete/{id}', 'CustomerAdmin/WaiterController@delete');

// Waiter-Table Assignment
$router->get('/customer/assignments', 'CustomerAdmin/AssignmentController@index');
$router->post('/customer/assignments/store', 'CustomerAdmin/AssignmentController@store');
$router->post('/customer/assignments/delete/{id}', 'CustomerAdmin/AssignmentController@delete');

// Products
$router->get('/customer/products', 'CustomerAdmin/ProductController@index');
$router->post('/customer/categories/store', 'CustomerAdmin/ProductController@storeCategory');
$router->post('/customer/categories/update/{id}', 'CustomerAdmin/ProductController@updateCategory');
$router->post('/customer/categories/delete/{id}', 'CustomerAdmin/ProductController@deleteCategory');
$router->post('/customer/products/store', 'CustomerAdmin/ProductController@store');
$router->post('/customer/products/update/{id}', 'CustomerAdmin/ProductController@update');
$router->post('/customer/products/delete/{id}', 'CustomerAdmin/ProductController@delete');

// QR Codes
$router->get('/customer/qr', 'CustomerAdmin/QRController@index');
$router->post('/customer/qr/generate/{id}', 'CustomerAdmin/QRController@generate');
$router->get('/customer/qr/download/{id}', 'CustomerAdmin/QRController@download');

// Form Builder
$router->get('/customer/forms', 'CustomerAdmin/FormController@index');
$router->get('/customer/forms/create', 'CustomerAdmin/FormController@create');
$router->post('/customer/forms/store', 'CustomerAdmin/FormController@store');
$router->get('/customer/forms/edit/{id}', 'CustomerAdmin/FormController@edit');
$router->post('/customer/forms/update/{id}', 'CustomerAdmin/FormController@update');
$router->post('/customer/forms/delete/{id}', 'CustomerAdmin/FormController@delete');
$router->get('/customer/forms/preview/{id}', 'CustomerAdmin/FormController@preview');

// Form-Table Assignment
$router->get('/customer/form-assignments', 'CustomerAdmin/FormAssignmentController@index');
$router->post('/customer/form-assignments/store', 'CustomerAdmin/FormAssignmentController@store');
$router->post('/customer/form-assignments/delete/{id}', 'CustomerAdmin/FormAssignmentController@delete');

// Location Settings
$router->get('/customer/location', 'CustomerAdmin/LocationController@index');
$router->post('/customer/location/update', 'CustomerAdmin/LocationController@update');

// Orders
$router->get('/customer/orders', 'CustomerAdmin/OrderController@index');
$router->get('/customer/orders/view/{id}', 'CustomerAdmin/OrderController@view');

// Subscription
$router->get('/customer/subscription', 'CustomerAdmin/SubscriptionController@index');

// Activity Logs
$router->get('/customer/activity-logs', 'CustomerAdmin/ActivityLogController@index');

// ========================================
// WAITER ROUTES
// ========================================
$router->get('/waiter', 'Waiter/DashboardController@index');
$router->get('/waiter/tables', 'Waiter/TableController@index');
$router->get('/waiter/orders', 'Waiter/OrderController@index');
$router->get('/waiter/orders/view/{id}', 'Waiter/OrderController@view');
$router->post('/waiter/orders/update-status/{id}', 'Waiter/OrderController@updateStatus');

// ========================================
// QR / PUBLIC ORDER ROUTES
// ========================================
$router->get('/table/{slug}/{token}', 'Public/OrderController@showForm');
$router->post('/table/{slug}/{token}/submit', 'Public/OrderController@submitOrder');

// ========================================
// API ROUTES (for AJAX)
// ========================================
$router->get('/api/orders/pending', 'Api/OrderController@pending');
$router->post('/api/orders/update-status', 'Api/OrderController@updateStatus');

// Dispatch
$router->dispatch();
