<?php
require_once __DIR__ . '/config/koneksi.php';
$code = preg_replace('/[^a-f0-9]/', '', strtolower((string) ($_GET['kode'] ?? $_POST['kode'] ?? '')));
$result = null;
$kind = '';
if (strlen($code) === 32) {
    $hash = hash('sha256', $code);
    $statement = $pdo->prepare('SELECT judul_laporan judul, status, tanggal_laporan tanggal FROM laporan_sampah WHERE kode_pelacakan_hash = ?');
    $statement->execute([$hash]);
    $result = $statement->fetch();
    if ($result) $kind = 'Laporan sampah';
    if (!$result) {
        $statement = $pdo->prepare('SELECT judul, status, tanggal_pengaduan tanggal FROM pengaduan WHERE kode_pelacakan_hash = ?');
        $statement->execute([$hash]);
        $result = $statement->fetch();
        if ($result) $kind = 'Pengaduan warga';
    }
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Status Laporan | RT/RW Peduli Lingkungan</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="<?=e(base_url('assets/css/style.css'))?>" rel="stylesheet"><link href="<?=e(base_url('assets/css/admin.css'))?>" rel="stylesheet"></head><body><?php require __DIR__.'/includes/navbar.php';?><main class="detail-wrap"><div class="container"><a class="back-link" href="<?=e(base_url())?>"><i class="fa-solid fa-arrow-left"></i> Kembali ke beranda</a><div class="module-heading"><div><span class="eyebrow eyebrow-dark">PEMANTAUAN</span><h1>Status laporan</h1><p>Masukkan kode pelacakan yang ditampilkan setelah pengiriman laporan atau pengaduan.</p></div></div><form class="module-form mb-4" method="post" action="<?=e(base_url('status_laporan.php'))?>"><div class="field-group"><label for="kode">Kode pelacakan</label><input class="form-control" id="kode" name="kode" maxlength="32" minlength="32" pattern="[a-fA-F0-9]{32}" required value="<?=e($code)?>"></div><div class="form-actions"><button class="btn btn-admin-primary" type="submit"><i class="fa-solid fa-magnifying-glass me-2"></i>Cek status</button></div></form><?php if($code && !$result):?><div class="alert alert-warning">Kode tidak ditemukan. Pastikan kode dimasukkan dengan benar.</div><?php endif;?><?php if($result):?><section class="admin-panel p-4"><span class="eyebrow eyebrow-dark"><?=e($kind)?></span><h2 class="mt-2" style="font:800 20px Manrope,sans-serif;color:#183d34"><?=e($result['judul'])?></h2><p class="detail-meta mb-3">Dikirim <?=e(format_tanggal($result['tanggal']))?></p><p class="mb-2">Status saat ini</p><span class="status-badge status-<?=e(strtolower(str_replace(' ','-',(string)$result['status'])))?>"><?=e($result['status'])?></span><p class="disclaimer mt-3 mb-0">Kode pelacakan ini bersifat pribadi. Simpan kode Anda dan jangan membagikannya. Tanggapan internal serta identitas pihak lain tidak ditampilkan di sini.</p></section><?php endif;?></div></main><?php require __DIR__.'/includes/footer.php';?></body></html>