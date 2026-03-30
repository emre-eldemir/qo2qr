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

        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            flash_set('error', 'Please fill in all required fields.');
            flash_set('_old_input', $_POST);
            redirect(url('/contact'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Please enter a valid email address.');
            flash_set('_old_input', $_POST);
            redirect(url('/contact'));
        }

        flash_set('success', 'Thank you for your message! We will get back to you soon.');
        redirect(url('/contact'));
    }
}
