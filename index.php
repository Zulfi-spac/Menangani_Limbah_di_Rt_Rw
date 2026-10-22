<?php
require_once __DIR__ . '/config/koneksi.php';
$stats = $pdo->query("SELECT
    (SELECT COUNT(*) FROM laporan_sampah) AS total_laporan,
    (SELECT COUNT(*) FROM laporan_sampah WHERE status = 'Selesai') AS laporan_selesai,
    (SELECT COUNT(*) FROM peraturan WHERE status = 'Aktif') AS peraturan_aktif,
    (SELECT COUNT(*) FROM berita WHERE status = 'Terbit') AS total_berita")->fetch();
$rules = $pdo->query("SELECT id_peraturan, judul_peraturan, kategori, isi_peraturan FROM peraturan WHERE status = 'Aktif' ORDER BY tanggal_berlaku DESC LIMIT 4")->fetchAll();
$news = $pdo->query("SELECT judul, slug, isi, gambar, tanggal_publikasi FROM berita WHERE status = 'Terbit' ORDER BY tanggal_publikasi DESC LIMIT 3")->fetchAll();
?><!doctype html>
<html lang="id"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Informasi RT/RW Peduli Lingkungan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="<?= e(base_url('assets/css/style.css')) ?>" rel="stylesheet">
</head><body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main>
  <section class="hero" id="tentang"><div class="hero-photo" aria-hidden="true"></div><div class="container hero-content"><div class="hero-copy reveal"><span class="eyebrow"><span></span> RUANG BERSAMA WARGA</span><h1>Bersama menjaga kebersihan dan ketertiban kampung.</h1><p>Platform informasi RT/RW untuk mendukung pengelolaan sampah, pelaporan masalah lingkungan, dan penyebaran tata tertib demi menciptakan kampung yang bersih, aman, dan nyaman.</p><div class="d-flex flex-wrap gap-3"><a class="btn btn-light btn-lg" href="#tata-tertib">Lihat Tata Tertib <i class="fa-solid fa-arrow-right ms-2"></i></a><a class="btn btn-outline-light btn-lg" href="#kebersihan">Informasi Kebersihan</a></div></div><div class="hero-note"><i class="fa-solid fa-people-group"></i><span>Lingkungan terawat<small>dimulai dari kepedulian bersama</small></span></div></div></section>
  <section class="stats-strip"><div class="container"><div class="row g-0">
    <div class="col-6 col-lg-3 stat-item"><span class="stat-icon"><i class="fa-solid fa-recycle"></i></span><div><strong><?= number_format((int) $stats['total_laporan']) ?></strong><small>Total laporan sampah</small></div></div>
    <div class="col-6 col-lg-3 stat-item"><span class="stat-icon stat-blue"><i class="fa-solid fa-circle-check"></i></span><div><strong><?= number_format((int) $stats['laporan_selesai']) ?></strong><small>Laporan terselesaikan</small></div></div>
    <div class="col-6 col-lg-3 stat-item"><span class="stat-icon stat-orange"><i class="fa-solid fa-book-open"></i></span><div><strong><?= number_format((int) $stats['peraturan_aktif']) ?></strong><small>Peraturan aktif</small></div></div>
    <div class="col-6 col-lg-3 stat-item"><span class="stat-icon stat-red"><i class="fa-regular fa-newspaper"></i></span><div><strong><?= number_format((int) $stats['total_berita']) ?></strong><small>Informasi lingkungan</small></div></div>
  </div></div></section>
  <section class="section-space rules-section" id="tata-tertib"><div class="container"><div class="section-head"><div><span class="eyebrow eyebrow-dark">HIDUP BERDAMPINGAN</span><h2>Tata tertib, untuk<br>kenyamanan bersama.</h2></div><p>Kesepakatan warga membantu kita menjaga lingkungan dan saling menghormati. Peraturan di sini mengikuti keputusan setempat dan ketentuan yang berlaku.</p></div><div class="row g-3">
    <?php foreach ($rules as $index => $rule): ?><div class="col-md-6 col-lg-3"><article class="rule-item"><span class="rule-number">0<?= $index + 1 ?></span><span class="rule-category"><?= e($rule['kategori']) ?></span><h3><?= e($rule['judul_peraturan']) ?></h3><p><?= e(mb_strimwidth($rule['isi_peraturan'], 0, 120, '...')) ?></p><a href="<?= e(base_url('detail_peraturan.php?id=' . (int) $rule['id_peraturan'])) ?>">Baca aturan <i class="fa-solid fa-arrow-right"></i></a></article></div><?php endforeach; ?>
    <?php if (!$rules): ?><div class="col-12"><p class="empty-state">Belum ada peraturan aktif yang dipublikasikan.</p></div><?php endif; ?>
  </div><p class="disclaimer"><i class="fa-solid fa-circle-info me-2"></i>Contoh informasi lingkungan perlu disesuaikan dengan kesepakatan warga dan peraturan perundang-undangan yang berlaku.</p></div></section>
  <section class="waste-section" id="kebersihan"><div class="container"><div class="waste-image"><div class="waste-overlay"><span class="eyebrow">PILAH DARI RUMAH</span><h2>Sampah yang terpilah<br>lebih mudah dikelola.</h2><p>Kebiasaan kecil memberi dampak besar saat dilakukan bersama.</p></div></div><div class="waste-guide row g-0">
    <div class="col-6 col-lg-3 guide-item"><i class="fa-solid fa-leaf"></i><h3>Organik</h3><p>Sisa makanan dan daun dapat dikomposkan.</p></div>
    <div class="col-6 col-lg-3 guide-item"><i class="fa-solid fa-box"></i><h3>Anorganik</h3><p>Bersihkan dan pilah kemasan untuk didaur ulang.</p></div>
    <div class="col-6 col-lg-3 guide-item"><i class="fa-solid fa-plug"></i><h3>Elektronik</h3><p>Pisahkan perangkat dan baterai dari sampah biasa.</p></div>
    <div class="col-6 col-lg-3 guide-item"><i class="fa-solid fa-triangle-exclamation"></i><h3>Limbah khusus</h3><p>Ikuti arahan petugas untuk bahan berbahaya rumah tangga.</p></div>
  </div></div></section>
  <section class="section-space news-section" id="berita"><div class="container"><div class="section-head"><div><span class="eyebrow eyebrow-dark">KABAR WARGA</span><h2>Yang sedang terjadi<br>di lingkungan kita.</h2></div><span class="news-count"><strong><?= count($news) ?></strong> informasi terbaru</span></div><div class="row g-4">
    <?php foreach ($news as $item): ?><div class="col-md-6 col-lg-4"><article class="news-card"><a class="news-image" href="<?= e(base_url('detail_berita.php?slug=' . urlencode($item['slug']))) ?>"><?php if ($item['gambar']): ?><img src="<?= e(base_url('uploads/berita/' . $item['gambar'])) ?>" alt=""><?php else: ?><span><i class="fa-solid fa-seedling"></i></span><?php endif; ?></a><div class="news-body"><small><?= e(format_tanggal($item['tanggal_publikasi'])) ?> <span>•</span> Lingkungan</small><h3><a href="<?= e(base_url('detail_berita.php?slug=' . urlencode($item['slug']))) ?>"><?= e($item['judul']) ?></a></h3><p><?= e(mb_strimwidth(strip_tags($item['isi']), 0, 125, '...')) ?></p><a class="read-link" href="<?= e(base_url('detail_berita.php?slug=' . urlencode($item['slug']))) ?>">Baca selengkapnya <i class="fa-solid fa-arrow-right"></i></a></div></article></div><?php endforeach; ?>
    <?php if (!$news): ?><div class="col-12"><p class="empty-state">Belum ada berita yang diterbitkan.</p></div><?php endif; ?>
  </div></div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="<?= e(base_url('assets/js/script.js')) ?>"></script>
</body></html>