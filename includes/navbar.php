<nav class="navbar navbar-expand-lg site-nav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(base_url()) ?>">
      <span class="brand-mark"><i class="fa-solid fa-leaf"></i></span>
      <span><strong>RT/RW</strong><small>Peduli Lingkungan</small></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Buka navigasi"><i class="fa-solid fa-bars"></i></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <a class="nav-link" href="<?= e(base_url()) ?>">Beranda</a>
        <a class="nav-link" href="<?= e(base_url()) ?>#tentang">Tentang</a>
        <a class="nav-link" href="<?= e(base_url()) ?>#tata-tertib">Tata Tertib</a>
        <a class="nav-link" href="<?= e(base_url()) ?>#kebersihan">Informasi Kebersihan</a>
        <a class="nav-link" href="<?= e(base_url()) ?>#berita">Berita</a>
        <a class="nav-link" href="<?= e(base_url('buat_laporan.php')) ?>">Kirim Laporan</a>
        <a class="nav-link" href="<?= e(base_url('buat_pengaduan.php')) ?>">Pengaduan</a>
        <a class="nav-link" href="<?= e(base_url('status_laporan.php')) ?>">Cek Status</a>
        <a class="btn btn-nav ms-lg-2" href="<?= e(base_url('admin/login.php')) ?>"><i class="fa-solid fa-right-to-bracket me-2"></i>Login Pengurus</a>
      </div>
    </div>
  </div>
</nav>