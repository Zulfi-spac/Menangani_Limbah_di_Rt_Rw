<?php
require_once __DIR__ . '/../config/koneksi.php';
require_login();
$sources = [
    'laporan_sampah' => ['table' => 'laporan_sampah', 'column' => 'foto', 'folder' => 'laporan_sampah', 'role' => 'sampah'],
    'bukti_pengaduan' => ['table' => 'pengaduan', 'column' => 'foto_bukti', 'folder' => 'bukti_pengaduan', 'role' => 'pengaduan'],
    'bukti_pelanggaran' => ['table' => 'pelanggaran', 'column' => 'bukti', 'folder' => 'bukti_pelanggaran', 'role' => 'pelanggaran'],
];
$kind = (string) ($_GET['jenis'] ?? '');
$file = (string) ($_GET['file'] ?? '');
if (!isset($sources[$kind]) || basename($file) !== $file || !preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $file)) {
    http_response_code(404);
    exit('Bukti tidak ditemukan.');
}
require_role($sources[$kind]['role']);
$source = $sources[$kind];
$statement = $pdo->prepare('SELECT COUNT(*) FROM `' . $source['table'] . '` WHERE `' . $source['column'] . '` = ?');
$statement->execute([$file]);
$path = __DIR__ . '/../uploads/' . $source['folder'] . '/' . $file;
if ((int) $statement->fetchColumn() === 0 || !is_file($path)) {
    http_response_code(404);
    exit('Bukti tidak ditemukan.');
}
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
    http_response_code(415);
    exit('Jenis berkas tidak didukung.');
}
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
header('Content-Disposition: inline; filename="bukti-' . $kind . '.' . pathinfo($file, PATHINFO_EXTENSION) . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($path);