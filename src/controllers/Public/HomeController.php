<?php

class HomeController
{
    public function index(): void
    {
        $db = Database::getInstance();
        $plans = $db->query(
            'SELECT * FROM plans WHERE status = ? ORDER BY price_monthly ASC',
            ['active']
        )->fetchAll();

        view('public.home', ['plans' => $plans]);
    }

    public function features(): void
    {
        view('public.features');
    }

    public function pricing(): void
    {
        $db = Database::getInstance();
        $plans = $db->query(
            'SELECT * FROM plans WHERE status = ? ORDER BY price_monthly ASC',
            ['active']
        )->fetchAll();

        view('public.pricing', ['plans' => $plans]);
    }

    public function about(): void
    {
        view('public.about');
    }

    public function contact(): void
    {
        view('public.contact');
    }

    public function contactSubmit(): void
    {
        CSRF::validateRequest();

        if (!rate_limit_check('contact_form', 5, 300)) {
            flash_set('error', 'Too many submissions. Please try again later.');
            redirect(url('/contact'));
        }

        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Sanitize old input – store only specific keys, not raw $_POST
        $oldInput = ['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message];

        if (empty($name) || empty($email) || empty($message)) {
            flash_set('error', 'Please fill in all required fields.');
            flash_set('_old_input', $oldInput);
            redirect(url('/contact'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Please enter a valid email address.');
            flash_set('_old_input', $oldInput);
            redirect(url('/contact'));
        }

        if (strlen($name) > 255 || strlen($subject) > 255 || strlen($message) > 5000) {
            flash_set('error', 'Input exceeds maximum length.');
            flash_set('_old_input', $oldInput);
            redirect(url('/contact'));
        }

        flash_set('success', 'Thank you for your message! We will get back to you soon.');
        redirect(url('/contact'));
    }
}
