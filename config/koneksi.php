<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isSecure,
        'samesite' => 'Lax',
    ]);
    session_start();
}

const DB_HOST = '127.0.0.1';
const DB_NAME = 'db_rt_rw';
const DB_USER = 'root';
const DB_PASS = '';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Koneksi database gagal. Periksa konfigurasi dan pastikan database db_rt_rw sudah diimpor.');
}

require_once __DIR__ . '/../includes/fungsi.php';