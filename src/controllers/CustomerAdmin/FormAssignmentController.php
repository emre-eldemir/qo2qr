<?php

class FormAssignmentController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $assignments = $db->query(
            'SELECT tfa.id, mf.name AS form_name, mf.id AS form_id, rt.name AS table_name, rt.id AS table_id, tfa.created_at
             FROM table_form_assignments tfa
             JOIN menu_forms mf ON mf.id = tfa.form_id
             JOIN restaurant_tables rt ON rt.id = tfa.table_id
             WHERE mf.restaurant_id = ? AND rt.restaurant_id = ?
             ORDER BY rt.name, mf.name',
            [$rId, $rId]
        )->fetchAll();

        $forms = $db->query(
            'SELECT id, name FROM menu_forms WHERE restaurant_id = ? AND status = ? ORDER BY name',
            [$rId, 'active']
        )->fetchAll();

        $tables = $db->query(
            'SELECT id, name FROM restaurant_tables WHERE restaurant_id = ? AND status = ? ORDER BY name',
            [$rId, 'active']
        )->fetchAll();

        view('customer_admin.form_assignments', [
            'assignments' => $assignments,
            'forms'       => $forms,
            'tables'      => $tables,
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $formId  = (int) ($_POST['form_id'] ?? 0);
        $tableId = (int) ($_POST['table_id'] ?? 0);

        $form = $db->query(
            'SELECT id FROM menu_forms WHERE id = ? AND restaurant_id = ?',
            [$formId, $rId]
        )->fetch();

        $table = $db->query(
            'SELECT id FROM restaurant_tables WHERE id = ? AND restaurant_id = ?',
            [$tableId, $rId]
        )->fetch();

        if (!$form || !$table) {
            flash_set('error', 'Invalid form or table selection.');
            redirect(url('/customer/form-assignments'));
        }

        $existing = $db->query(
            'SELECT id FROM table_form_assignments WHERE table_id = ? AND form_id = ?',
            [$tableId, $formId]
        )->fetch();

        if ($existing) {
            flash_set('error', 'This form-table assignment already exists.');
            redirect(url('/customer/form-assignments'));
        }

        $db->query(
            'INSERT INTO table_form_assignments (table_id, form_id) VALUES (?, ?)',
            [$tableId, $formId]
        );

        log_activity(Auth::id(), $rId, 'form_assignment_created', 'Assigned form to table');
        flash_set('success', 'Form assigned to table successfully.');
        redirect(url('/customer/form-assignments'));
    }

    public function delete($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $assignment = $db->query(
            'SELECT tfa.id
             FROM table_form_assignments tfa
             JOIN restaurant_tables rt ON rt.id = tfa.table_id
             WHERE tfa.id = ? AND rt.restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$assignment) {
            flash_set('error', 'Assignment not found.');
            redirect(url('/customer/form-assignments'));
        }

        $db->query('DELETE FROM table_form_assignments WHERE id = ?', [(int) $id]);

        log_activity(Auth::id(), $rId, 'form_assignment_deleted', 'Removed form-table assignment');
        flash_set('success', 'Form assignment removed successfully.');
        redirect(url('/customer/form-assignments'));
    }
}
