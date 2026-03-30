<?php

class QRController
{
    public function index(): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $tables = $db->query(
            'SELECT rt.*, qr.id AS qr_id, qr.qr_url, qr.qr_image_path
             FROM restaurant_tables rt
             LEFT JOIN qr_codes qr ON qr.table_id = rt.id AND qr.restaurant_id = rt.restaurant_id
             WHERE rt.restaurant_id = ?
             ORDER BY rt.name',
            [$rId]
        )->fetchAll();

        view('customer_admin.qr', ['tables' => $tables]);
    }

    public function generate($id): void
    {
        Auth::requireRole('customer_admin');
        CSRF::validateRequest();

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $table = $db->query(
            'SELECT rt.*, r.slug AS restaurant_slug
             FROM restaurant_tables rt
             JOIN restaurants r ON r.id = rt.restaurant_id
             WHERE rt.id = ? AND rt.restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$table) {
            flash_set('error', 'Table not found.');
            redirect(url('/customer/qr'));
        }

        $config  = require BASE_PATH . '/config/app.php';
        $appUrl  = rtrim($config['url'] ?? 'http://localhost:8080', '/');
        $qrUrl   = $appUrl . '/table/' . $table['restaurant_slug'] . '/' . $table['token'];

        $qrDir = PUBLIC_PATH . '/qr';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        $filename    = 'qr_' . $table['restaurant_slug'] . '_' . $table['token'] . '.png';
        $filePath    = $qrDir . '/' . $filename;
        $relativePath = '/qr/' . $filename;

        $generated = false;

        // Try endroid/qr-code if available
        if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
            require_once BASE_PATH . '/vendor/autoload.php';
            if (class_exists('Endroid\QrCode\QrCode')) {
                try {
                    $qrCode = new \Endroid\QrCode\QrCode($qrUrl);
                    $writer = new \Endroid\QrCode\Writer\PngWriter();
                    $result = $writer->write($qrCode);
                    file_put_contents($filePath, $result->getString());
                    $generated = true;
                } catch (Throwable $e) {
                    // Fall through to fallback
                }
            }
        }

        // Fallback: generate a simple PNG with GD
        if (!$generated && extension_loaded('gd')) {
            $size = 300;
            $img  = imagecreatetruecolor($size, $size);
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            $gray  = imagecolorallocate($img, 100, 100, 100);
            imagefill($img, 0, 0, $white);

            imagerectangle($img, 10, 10, $size - 10, $size - 10, $black);
            imagerectangle($img, 15, 15, $size - 15, $size - 15, $black);

            $text = 'QR: ' . $table['name'];
            $font = 5;
            $tw   = imagefontwidth($font) * strlen($text);
            imagestring($img, $font, (int)(($size - $tw) / 2), (int)($size / 2 - 20), $text, $black);

            $urlShort = strlen($qrUrl) > 40 ? substr($qrUrl, 0, 40) . '...' : $qrUrl;
            $tw2      = imagefontwidth(2) * strlen($urlShort);
            imagestring($img, 2, (int)(($size - $tw2) / 2), (int)($size / 2 + 10), $urlShort, $gray);

            imagepng($img, $filePath);
            imagedestroy($img);
            $generated = true;
        }

        if (!$generated) {
            flash_set('error', 'Unable to generate QR code. GD extension not available.');
            redirect(url('/customer/qr'));
        }

        // Upsert qr_codes record
        $existing = $db->query(
            'SELECT id FROM qr_codes WHERE table_id = ? AND restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if ($existing) {
            $db->query(
                'UPDATE qr_codes SET qr_url = ?, qr_image_path = ? WHERE id = ?',
                [$qrUrl, $relativePath, (int) $existing['id']]
            );
        } else {
            $db->query(
                'INSERT INTO qr_codes (table_id, restaurant_id, qr_url, qr_image_path) VALUES (?, ?, ?, ?)',
                [(int) $id, $rId, $qrUrl, $relativePath]
            );
        }

        log_activity(Auth::id(), $rId, 'qr_generated', "Generated QR for table: {$table['name']}");
        flash_set('success', 'QR code generated successfully.');
        redirect(url('/customer/qr'));
    }

    public function download($id): void
    {
        Auth::requireRole('customer_admin');

        $db  = Database::getInstance();
        $rId = Auth::restaurantId();

        $qr = $db->query(
            'SELECT qr.*, rt.name AS table_name
             FROM qr_codes qr
             JOIN restaurant_tables rt ON rt.id = qr.table_id
             WHERE qr.table_id = ? AND qr.restaurant_id = ?',
            [(int) $id, $rId]
        )->fetch();

        if (!$qr || !$qr['qr_image_path']) {
            flash_set('error', 'QR code not found. Generate it first.');
            redirect(url('/customer/qr'));
        }

        $filePath = PUBLIC_PATH . $qr['qr_image_path'];

        if (!file_exists($filePath)) {
            flash_set('error', 'QR code file not found. Please regenerate.');
            redirect(url('/customer/qr'));
        }

        $downloadName = 'qr_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $qr['table_name']) . '.png';

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
