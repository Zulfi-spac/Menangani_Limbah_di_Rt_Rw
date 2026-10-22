<?php
require_once __DIR__ . '/../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (current_user()) { log_activity($pdo, 'Keluar dari sistem'); }
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
redirect('admin/login.php');