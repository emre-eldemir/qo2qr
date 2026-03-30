<?php

class SettingsController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        view('super_admin.settings');
    }

    public function update(): void
    {
        Auth::requireRole('super_admin');
        CSRF::validateRequest();

        log_activity(
            Auth::id(),
            null,
            'update_settings',
            'System settings updated'
        );

        flash_set('success', 'Settings saved successfully.');
        redirect(url('/super-admin/settings'));
    }
}
