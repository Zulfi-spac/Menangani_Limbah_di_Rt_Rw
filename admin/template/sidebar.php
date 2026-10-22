<aside class="admin-sidebar" id="adminSidebar"><a class="admin-brand" href="<?= e(base_url('admin/dashboard.php')) ?>"><span class="brand-mark"><i class="fa-solid fa-leaf"></i></span><span><strong>RT/RW</strong><small>Peduli Lingkungan</small></span></a><div class="sidebar-label">MENU UTAMA</div><nav class="sidebar-nav">
<?php $menu = [
  ['dashboard','Dashboard','fa-chart-pie','admin/dashboard.php',''],
  ['warga','Data Warga','fa-people-roof','admin/modul.php?modul=warga','warga'],
  ['sampah','Laporan Sampah','fa-recycle','admin/modul.php?modul=sampah','sampah'],
  ['pengaduan','Pengaduan Warga','fa-message','admin/modul.php?modul=pengaduan','pengaduan'],
  ['pelanggaran','Dugaan Pelanggaran','fa-clipboard-check','admin/modul.php?modul=pelanggaran','pelanggaran'],
  ['peraturan','Data Peraturan','fa-book-open','admin/modul.php?modul=peraturan','peraturan'],
  ['berita','Berita Lingkungan','fa-newspaper','admin/modul.php?modul=berita','berita'],
  ['laporan','Laporan & Rekapitulasi','fa-chart-column','admin/laporan/index.php','laporan'],
  ['aktivitas','Aktivitas Sistem','fa-clock-rotate-left','admin/modul.php?modul=aktivitas','aktivitas'],
]; foreach ($menu as [$key,$label,$icon,$href,$capability]): if ($capability !== '' && !user_can($capability)) continue; ?>
<a class="sidebar-link <?= $activeModule === $key ? 'active' : '' ?>" href="<?= e(base_url($href)) ?>"><i class="fa-solid <?= e($icon) ?>"></i><span><?= e($label) ?></span><?php if ($key === 'sampah'): ?><i class="fa-solid fa-chevron-right side-arrow"></i><?php endif; ?></a>
<?php endforeach; ?></nav><div class="sidebar-bottom"><div class="sidebar-user"><span class="user-avatar"><?= e(mb_strtoupper(mb_substr($user['nama'], 0, 1))) ?></span><span><strong><?= e($user['nama']) ?></strong><small><?= e($user['role']) ?></small></span></div><form action="<?= e(base_url('admin/logout.php')) ?>" method="post"><?= csrf_field() ?><button class="sidebar-logout" type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</button></form></div></aside>
<div class="admin-main"><header class="admin-topbar"><button class="icon-button sidebar-toggle" type="button" aria-label="Buka menu" data-sidebar-toggle><i class="fa-solid fa-bars"></i></button><div class="topbar-title"><span>RUANG PENGELOLA</span><strong><?= e($pageTitle) ?></strong></div><a class="topbar-public" target="_blank" href="<?= e(base_url()) ?>"><i class="fa-solid fa-arrow-up-right-from-square"></i><span>Lihat situs</span></a></header><main class="admin-content"><?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert"><?= e($flash['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php endif; ?>