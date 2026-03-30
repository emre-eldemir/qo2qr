<?php

class LocationController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $restaurant = $db->query(
            'SELECT name, address, latitude, longitude, allowed_order_radius_km, location_restriction_enabled
             FROM restaurants WHERE id = ?',
            [$rId]
        )->fetch();

        view('customer_admin.location', ['restaurant' => $restaurant]);
    }

    public function update(): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $latitude  = $_POST['latitude'] !== '' ? (float) $_POST['latitude'] : null;
        $longitude = $_POST['longitude'] !== '' ? (float) $_POST['longitude'] : null;
        $radius    = max(0.1, (float) ($_POST['allowed_order_radius_km'] ?? 2.00));
        $enabled   = isset($_POST['location_restriction_enabled']) ? 1 : 0;
        $address   = trim($_POST['address'] ?? '');

        $db->query(
            'UPDATE restaurants SET latitude = ?, longitude = ?, allowed_order_radius_km = ?, location_restriction_enabled = ?, address = ? WHERE id = ?',
            [$latitude, $longitude, $radius, $enabled, $address, $rId]
        );

        log_activity(Auth::id(), $rId, 'location_updated', 'Updated location settings');
        flash_set('success', 'Location settings updated successfully.');
        redirect(url('/customer/location'));
    }
}
