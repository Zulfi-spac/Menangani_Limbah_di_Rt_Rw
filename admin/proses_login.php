<?php
require_once __DIR__ . '/../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin/login.php'); }
verify_csrf();
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
if ($username === '' || $password === '' || strlen($username) > 60) { redirect('admin/login.php?gagal=1'); }
$statement = $pdo->prepare('SELECT id_admin, nama, username, password, role FROM admin WHERE username = ? LIMIT 1');
$statement->execute([$username]);
$account = $statement->fetch();
if (!$account || !password_verify($password, $account['password'])) { redirect('admin/login.php?gagal=1'); }
session_regenerate_id(true);
unset($account['password']);
$_SESSION['user'] = $account;
log_activity($pdo, 'Masuk ke sistem');
redirect('admin/dashboard.php');