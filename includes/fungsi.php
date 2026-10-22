<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    foreach (['/admin/template', '/admin/laporan', '/admin'] as $suffix) {
        if (str_ends_with($script, $suffix)) {
            $script = substr($script, 0, -strlen($suffix));
            break;
        }
    }
    $base = rtrim($script, '/');
    return $base . ($path !== '' ? '/' . ltrim($path, '/') : '/');
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(419);
        exit('Sesi formulir tidak valid atau telah kedaluwarsa. Muat ulang halaman lalu coba lagi.');
    }
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}

function current_user(): ?array
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
}

function require_login(): void
{
    if (current_user() === null) {
        set_flash('warning', 'Silakan masuk untuk melanjutkan.');
        redirect('admin/login.php');
    }
}

function user_can(string $capability): bool
{
    $role = current_user()['role'] ?? '';
    $permissions = [
        'warga' => ['Admin', 'Ketua RT', 'Ketua RW'],
        'sampah' => ['Admin', 'Ketua RT', 'Ketua RW', 'Petugas Kebersihan'],
        'pengaduan' => ['Admin', 'Ketua RT', 'Ketua RW', 'Petugas Kebersihan'],
        'pelanggaran' => ['Admin', 'Ketua RT', 'Ketua RW'],
        'peraturan' => ['Admin', 'Ketua RT', 'Ketua RW'],
        'berita' => ['Admin', 'Ketua RT', 'Ketua RW'],
        'laporan' => ['Admin', 'Ketua RT', 'Ketua RW', 'Petugas Kebersihan'],
        'aktivitas' => ['Admin', 'Ketua RW'],
    ];
    return in_array($role, $permissions[$capability] ?? [], true);
}

function require_role(string $capability): void
{
    if (!user_can($capability)) {
        http_response_code(403);
        exit('Anda tidak memiliki kewenangan untuk membuka halaman ini.');
    }
}

function log_activity(PDO $pdo, string $description): void
{
    $user = current_user();
    if ($user === null) {
        return;
    }
    $statement = $pdo->prepare('INSERT INTO aktivitas (id_admin, aktivitas, alamat_ip) VALUES (?, ?, ?)');
    $statement->execute([
        $user['id_admin'],
        mb_substr($description, 0, 255),
        mb_substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
    ]);
}

function format_tanggal(?string $date): string
{
    if (!$date) {
        return '-';
    }
    $timestamp = strtotime($date);
    return $timestamp ? date('d/m/Y', $timestamp) : '-';
}

function format_tanggal_panjang(?string $date): string
{
    if (!$date) {
        return '-';
    }
    $timestamp = strtotime($date);
    if (!$timestamp) {
        return '-';
    }
    $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}