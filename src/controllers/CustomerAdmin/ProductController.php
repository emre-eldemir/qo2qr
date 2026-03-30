<?php

class ProductController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $categories = $db->query(
            'SELECT * FROM product_categories WHERE restaurant_id = ? ORDER BY sort_order, name',
            [$rId]
        )->fetchAll();

        $products = $db->query(
            'SELECT p.*, pc.name AS category_name
             FROM products p
             JOIN product_categories pc ON pc.id = p.category_id
             WHERE p.restaurant_id = ?
             ORDER BY pc.sort_order, pc.name, p.name',
            [$rId]
        )->fetchAll();

        view('customer_admin.products', [
            'categories' => $categories,
            'products'   => $products,
        ]);
    }

    public function storeCategory(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $name      = trim($_POST['name'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if ($name === '') {
            flash_set('error', 'Category name is required.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'INSERT INTO product_categories (restaurant_id, name, sort_order) VALUES (?, ?, ?)',
            [$rId, $name, $sortOrder]
        );

        log_activity(Auth::id(), $rId, 'category_created', "Created category: {$name}");
        flash_set('success', 'Category created successfully.');
        redirect(url('/customer/products'));
    }

    public function updateCategory($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $category = $db->query(
            'SELECT * FROM product_categories WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$category) {
            flash_set('error', 'Category not found.');
            redirect(url('/customer/products'));
        }

        $name      = trim($_POST['name'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $status    = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if ($name === '') {
            flash_set('error', 'Category name is required.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'UPDATE product_categories SET name = ?, sort_order = ?, status = ? WHERE id = ? AND restaurant_id = ?',
            [$name, $sortOrder, $status, (int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'category_updated', "Updated category: {$name}");
        flash_set('success', 'Category updated successfully.');
        redirect(url('/customer/products'));
    }

    public function deleteCategory($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $category = $db->query(
            'SELECT * FROM product_categories WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$category) {
            flash_set('error', 'Category not found.');
            redirect(url('/customer/products'));
        }

        $productCount = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM products WHERE category_id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch()['cnt'] ?? 0);

        if ($productCount > 0) {
            flash_set('error', 'Cannot delete category with existing products. Remove products first.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'DELETE FROM product_categories WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'category_deleted', "Deleted category: {$category['name']}");
        flash_set('success', 'Category deleted successfully.');
        redirect(url('/customer/products'));
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $name        = trim($_POST['name'] ?? '');
        $categoryId  = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price       = max(0, (float) ($_POST['price'] ?? 0));
        $status      = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if ($name === '' || $categoryId <= 0) {
            flash_set('error', 'Product name and category are required.');
            redirect(url('/customer/products'));
        }

        $category = $db->query(
            'SELECT id FROM product_categories WHERE id = ? AND restaurant_id = ?',
            [$categoryId, $rId]
        )->fetch();

        if (!$category) {
            flash_set('error', 'Invalid category.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'INSERT INTO products (restaurant_id, category_id, name, description, price, status) VALUES (?, ?, ?, ?, ?, ?)',
            [$rId, $categoryId, $name, $description, $price, $status]
        );

        log_activity(Auth::id(), $rId, 'product_created', "Created product: {$name}");
        flash_set('success', 'Product created successfully.');
        redirect(url('/customer/products'));
    }

    public function update($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $product = $db->query(
            'SELECT * FROM products WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$product) {
            flash_set('error', 'Product not found.');
            redirect(url('/customer/products'));
        }

        $name        = trim($_POST['name'] ?? '');
        $categoryId  = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price       = max(0, (float) ($_POST['price'] ?? 0));
        $status      = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if ($name === '' || $categoryId <= 0) {
            flash_set('error', 'Product name and category are required.');
            redirect(url('/customer/products'));
        }

        $category = $db->query(
            'SELECT id FROM product_categories WHERE id = ? AND restaurant_id = ?',
            [$categoryId, $rId]
        )->fetch();

        if (!$category) {
            flash_set('error', 'Invalid category.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'UPDATE products SET name = ?, category_id = ?, description = ?, price = ?, status = ? WHERE id = ? AND restaurant_id = ?',
            [$name, $categoryId, $description, $price, $status, (int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'product_updated', "Updated product: {$name}");
        flash_set('success', 'Product updated successfully.');
        redirect(url('/customer/products'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $product = $db->query(
            'SELECT * FROM products WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$product) {
            flash_set('error', 'Product not found.');
            redirect(url('/customer/products'));
        }

        $db->query(
            'DELETE FROM products WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'product_deleted', "Deleted product: {$product['name']}");
        flash_set('success', 'Product deleted successfully.');
        redirect(url('/customer/products'));
    }
}
