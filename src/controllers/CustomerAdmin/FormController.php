<?php

class FormController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $forms = $db->query(
            'SELECT * FROM menu_forms WHERE restaurant_id = ? ORDER BY created_at DESC',
            [$rId]
        )->fetchAll();

        view('customer_admin.forms', ['forms' => $forms]);
    }

    public function create(): void
    {
        Auth::requireRole('customer_admin');

        view('customer_admin.form_builder', [
            'form'     => null,
            'formData' => null,
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $name     = trim($_POST['name'] ?? '');
        $formJson = $_POST['form_json'] ?? '';

        if ($name === '') {
            flash_set('error', 'Form name is required.');
            redirect(url('/customer/forms/create'));
        }

        $validation = $this->validateFormJson($formJson);
        if ($validation !== true) {
            flash_set('error', $validation);
            redirect(url('/customer/forms/create'));
        }

        $db->query(
            'INSERT INTO menu_forms (restaurant_id, name, form_json, status) VALUES (?, ?, ?, ?)',
            [$rId, $name, $formJson, 'active']
        );

        log_activity(Auth::id(), $rId, 'form_created', "Created form: {$name}");
        flash_set('success', 'Form created successfully.');
        redirect(url('/customer/forms'));
    }

    public function edit($id): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $form = $db->query(
            'SELECT * FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$form) {
            flash_set('error', 'Form not found.');
            redirect(url('/customer/forms'));
        }

        $formData = json_decode($form['form_json'], true);

        view('customer_admin.form_builder', [
            'form'     => $form,
            'formData' => $formData,
        ]);
    }

    public function update($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $form = $db->query(
            'SELECT * FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$form) {
            flash_set('error', 'Form not found.');
            redirect(url('/customer/forms'));
        }

        $name     = trim($_POST['name'] ?? '');
        $formJson = $_POST['form_json'] ?? '';

        if ($name === '') {
            flash_set('error', 'Form name is required.');
            redirect(url('/customer/forms/edit/' . (int) $id));
        }

        $validation = $this->validateFormJson($formJson);
        if ($validation !== true) {
            flash_set('error', $validation);
            redirect(url('/customer/forms/edit/' . (int) $id));
        }

        $db->query(
            'UPDATE menu_forms SET name = ?, form_json = ? WHERE id = ? AND restaurant_id = ?',
            [$name, $formJson, (int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'form_updated', "Updated form: {$name}");
        flash_set('success', 'Form updated successfully.');
        redirect(url('/customer/forms'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $form = $db->query(
            'SELECT * FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$form) {
            flash_set('error', 'Form not found.');
            redirect(url('/customer/forms'));
        }

        $db->query(
            'DELETE FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        );

        log_activity(Auth::id(), $rId, 'form_deleted', "Deleted form: {$form['name']}");
        flash_set('success', 'Form deleted successfully.');
        redirect(url('/customer/forms'));
    }

    public function preview($id): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $form = $db->query(
            'SELECT * FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$form) {
            flash_set('error', 'Form not found.');
            redirect(url('/customer/forms'));
        }

        $formData = json_decode($form['form_json'], true);

        view('customer_admin.form_preview', [
            'form'     => $form,
            'formData' => $formData,
        ]);
    }

    private function validateFormJson(string $json): string|bool
    {
        $config       = require BASE_PATH . '/config/app.php';
        $maxFields    = $config['form_builder']['max_fields'] ?? 7;
        $allowedTypes = $config['form_builder']['allowed_types'] ?? ['text', 'select', 'textarea', 'button'];

        $data = json_decode($json, true);

        if ($data === null) {
            return 'Invalid JSON format.';
        }

        if (!isset($data['fields']) || !is_array($data['fields'])) {
            return 'Form must contain a fields array.';
        }

        if (count($data['fields']) > $maxFields) {
            return "Maximum of {$maxFields} fields allowed.";
        }

        if (count($data['fields']) === 0) {
            return 'Form must contain at least one field.';
        }

        foreach ($data['fields'] as $i => $field) {
            if (!isset($field['type']) || !in_array($field['type'], $allowedTypes)) {
                $types = implode(', ', $allowedTypes);
                return "Field #" . ($i + 1) . " has an invalid type. Allowed: {$types}.";
            }

            if (!isset($field['label']) || trim($field['label']) === '') {
                return "Field #" . ($i + 1) . " must have a label.";
            }
        }

        return true;
    }
}
