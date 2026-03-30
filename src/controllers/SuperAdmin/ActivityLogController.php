<?php

class ActivityLogController
{
    public function index(): void
    {
        Auth::requireRole('super_admin');

        $db = Database::getInstance();

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $totalRows = (int) ($db->query(
            'SELECT COUNT(*) AS cnt FROM activity_logs'
        )->fetch()['cnt'] ?? 0);

        $totalPages = max(1, (int) ceil($totalRows / $perPage));

        $logs = $db->query(
            'SELECT al.id, al.action, al.description, al.ip_address, al.created_at,
                    u.name AS user_name
             FROM activity_logs al
             JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT ? OFFSET ?',
            [$perPage, $offset]
        )->fetchAll();

        view('super_admin.activity_logs', [
            'logs'       => $logs,
            'page'       => $page,
            'totalPages' => $totalPages,
            'totalRows'  => $totalRows,
        ]);
    }
}
