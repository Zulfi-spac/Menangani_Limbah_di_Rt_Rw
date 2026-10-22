<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_login();
$pageTitle = $pageTitle ?? 'Dashboard';
$activeModule = $activeModule ?? 'dashboard';
$user = current_user();
$flash = get_flash();
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($pageTitle) ?> | RT/RW Peduli Lingkungan</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="<?= e(base_url('assets/css/admin.css')) ?>" rel="stylesheet"></head><body class="admin-body"><div class="admin-shell">