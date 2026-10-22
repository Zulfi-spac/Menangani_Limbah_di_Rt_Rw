<?php
require_once __DIR__ . '/../config/koneksi.php';
require_login();

$enum = static fn(array $values): array => ['type' => 'select', 'options' => $values];
$modules = [
 'warga' => ['title'=>'Data Warga','table'=>'warga','id'=>'id_warga','cap'=>'warga','desc'=>'Data pribadi hanya untuk pengurus berwenang.','search'=>['nama','alamat','nomor_rumah','rt','rw'],'list'=>['nama'=>'Nama','alamat'=>'Alamat','nomor_rumah'=>'No. rumah','rt'=>'RT','rw'=>'RW','nomor_telepon'=>'Telepon'],'fields'=>[
  'nomor_kk'=>['label'=>'Nomor KK','required'=>true,'max'=>32], 'nik'=>['label'=>'NIK','required'=>true,'max'=>32], 'nama'=>['label'=>'Nama lengkap','required'=>true,'max'=>120], 'alamat'=>['label'=>'Alamat','required'=>true,'max'=>220], 'nomor_rumah'=>['label'=>'Nomor rumah','max'=>20], 'nomor_telepon'=>['label'=>'Nomor telepon','max'=>30], 'rt'=>['label'=>'RT','required'=>true,'max'=>5], 'rw'=>['label'=>'RW','required'=>true,'max'=>5]
 ]],
 'sampah' => ['title'=>'Laporan Sampah','table'=>'laporan_sampah','id'=>'id_laporan','cap'=>'sampah','desc'=>'Pantau laporan dan catatan penanganan. Status Ditolak wajib disertai alasan.','search'=>['judul_laporan','lokasi','deskripsi'],'filter'=>['status'=>'Status','jenis_sampah'=>'Jenis sampah'],'date'=>'tanggal_laporan','list'=>['judul_laporan'=>'Judul laporan','jenis_sampah'=>'Jenis','lokasi'=>'Lokasi','tanggal_laporan'=>'Tanggal','status'=>'Status'],'fields'=>[
  'id_warga'=>['label'=>'Pelapor (opsional)','type'=>'relation','table'=>'warga','key'=>'id_warga','value'=>'nama'], 'judul_laporan'=>['label'=>'Judul laporan','required'=>true,'max'=>180], 'jenis_sampah'=>array_merge(['label'=>'Jenis sampah','required'=>true],$enum(['Organik','Anorganik','Sampah B3 rumah tangga','Sampah elektronik','Sampah besar','Limbah cair','Lainnya'])), 'deskripsi'=>['label'=>'Deskripsi','type'=>'textarea','required'=>true], 'lokasi'=>['label'=>'Lokasi','required'=>true,'max'=>220], 'tanggal_laporan'=>['label'=>'Tanggal laporan','type'=>'date','required'=>true], 'foto'=>['label'=>'Foto kondisi (maks. 5 MB)','type'=>'upload','dir'=>'laporan_sampah'], 'status'=>array_merge(['label'=>'Status','required'=>true],$enum(['Menunggu','Diverifikasi','Dalam Penanganan','Selesai','Ditolak'])), 'catatan_petugas'=>['label'=>'Catatan penanganan / alasan penolakan','type'=>'textarea']
 ]],
 'pengaduan' => ['title'=>'Pengaduan Warga','table'=>'pengaduan','id'=>'id_pengaduan','cap'=>'pengaduan','desc'=>'Identitas pelapor hanya dapat dilihat pengurus berwenang. Pengaduan bukan bukti pelanggaran.','search'=>['judul','lokasi','deskripsi'],'filter'=>['status'=>'Status','kategori'=>'Kategori'],'date'=>'tanggal_pengaduan','list'=>['judul'=>'Judul','kategori'=>'Kategori','lokasi'=>'Lokasi','tanggal_pengaduan'=>'Tanggal','status'=>'Status'],'fields'=>[
  'id_warga'=>['label'=>'Pelapor (opsional)','type'=>'relation','table'=>'warga','key'=>'id_warga','value'=>'nama'], 'judul'=>['label'=>'Judul pengaduan','required'=>true,'max'=>180], 'kategori'=>array_merge(['label'=>'Kategori','required'=>true],$enum(['Pembuangan sampah sembarangan','Pembakaran sampah','Saluran air tersumbat','Pencemaran lingkungan','Kebisingan','Ketertiban umum','Lainnya'])), 'deskripsi'=>['label'=>'Deskripsi','type'=>'textarea','required'=>true], 'lokasi'=>['label'=>'Lokasi','required'=>true,'max'=>220], 'tanggal_pengaduan'=>['label'=>'Tanggal pengaduan','type'=>'date','required'=>true], 'foto_bukti'=>['label'=>'Foto bukti (maks. 5 MB)','type'=>'upload','dir'=>'bukti_pengaduan'], 'status'=>array_merge(['label'=>'Status','required'=>true],$enum(['Menunggu','Diverifikasi','Dalam Penanganan','Selesai','Ditolak'])), 'tanggapan_pengurus'=>['label'=>'Tanggapan / tindak lanjut','type'=>'textarea']
 ]],
 'pelanggaran' => ['title'=>'Dugaan Pelanggaran','table'=>'pelanggaran','id'=>'id_pelanggaran','cap'=>'pelanggaran','desc'=>'Catatan bersifat terbatas. Gunakan bahasa netral; status verifikasi harus berdasarkan pemeriksaan.','search'=>['lokasi_kejadian','deskripsi_kejadian','catatan_pengurus'],'filter'=>['status_verifikasi'=>'Status verifikasi'],'date'=>'tanggal_kejadian','list'=>['tanggal_kejadian'=>'Tanggal','lokasi_kejadian'=>'Lokasi kejadian','status_verifikasi'=>'Verifikasi','id_peraturan'=>'ID peraturan'],'fields'=>[
  'id_warga'=>['label'=>'Warga terkait (terbatas)','type'=>'relation','table'=>'warga','key'=>'id_warga','value'=>'nama'], 'id_peraturan'=>['label'=>'Peraturan terkait','type'=>'relation','table'=>'peraturan','key'=>'id_peraturan','value'=>'judul_peraturan'], 'tanggal_kejadian'=>['label'=>'Tanggal kejadian','type'=>'date','required'=>true], 'lokasi_kejadian'=>['label'=>'Lokasi kejadian','required'=>true,'max'=>220], 'deskripsi_kejadian'=>['label'=>'Deskripsi dugaan kejadian','type'=>'textarea','required'=>true], 'bukti'=>['label'=>'Bukti pendukung (maks. 5 MB)','type'=>'upload','dir'=>'bukti_pelanggaran'], 'status_verifikasi'=>array_merge(['label'=>'Status verifikasi','required'=>true],$enum(['Menunggu Verifikasi','Terverifikasi','Tidak Terbukti','Selesai Ditindaklanjuti'])), 'tindak_lanjut'=>['label'=>'Tindak lanjut','type'=>'textarea'], 'catatan_pengurus'=>['label'=>'Catatan pengurus','type'=>'textarea']
 ]],
 'peraturan' => ['title'=>'Data Peraturan','table'=>'peraturan','id'=>'id_peraturan','cap'=>'peraturan','desc'=>'Cantumkan dasar keputusan. Sanksi hanya dapat dicantumkan bila memiliki dasar yang sah.','search'=>['judul_peraturan','isi_peraturan','dasar_hukum'],'filter'=>['status'=>'Status','kategori'=>'Kategori'],'list'=>['judul_peraturan'=>'Judul','kategori'=>'Kategori','tanggal_berlaku'=>'Berlaku sejak','status'=>'Status'],'fields'=>[
  'judul_peraturan'=>['label'=>'Judul peraturan','required'=>true,'max'=>180], 'kategori'=>array_merge(['label'=>'Kategori','required'=>true],$enum(['Kebersihan Lingkungan','Pengelolaan Sampah','Ketertiban Kampung','Keamanan Lingkungan','Kegiatan Kemasyarakatan'])), 'isi_peraturan'=>['label'=>'Isi peraturan','type'=>'textarea','required'=>true], 'dasar_hukum'=>['label'=>'Dasar hukum / keputusan musyawarah','max'=>255], 'jenis_sanksi'=>['label'=>'Ketentuan sanksi (jika memiliki dasar yang sah)','max'=>255], 'tanggal_berlaku'=>['label'=>'Tanggal berlaku','type'=>'date'], 'status'=>array_merge(['label'=>'Status','required'=>true],$enum(['Aktif','Nonaktif']))
 ]],
 'berita' => ['title'=>'Berita Lingkungan','table'=>'berita','id'=>'id_berita','cap'=>'berita','desc'=>'Berita terbit akan tampil pada halaman publik.','search'=>['judul','isi'],'filter'=>['status'=>'Status'],'date'=>'tanggal_publikasi','list'=>['judul'=>'Judul','tanggal_publikasi'=>'Tanggal publikasi','status'=>'Status'],'fields'=>[
  'judul'=>['label'=>'Judul berita','required'=>true,'max'=>180], 'isi'=>['label'=>'Isi berita','type'=>'textarea','required'=>true], 'gambar'=>['label'=>'Gambar berita (maks. 5 MB)','type'=>'upload','dir'=>'berita'], 'tanggal_publikasi'=>['label'=>'Tanggal publikasi','type'=>'datetime-local','required'=>true], 'status'=>array_merge(['label'=>'Status','required'=>true],$enum(['Draft','Terbit']))
 ]],
 'aktivitas' => ['title'=>'Aktivitas Sistem','table'=>'aktivitas','id'=>'id_aktivitas','cap'=>'aktivitas','readonly'=>true,'desc'=>'Catatan audit aktivitas pengurus. Riwayat tidak dapat diubah dari halaman ini.','search'=>['aktivitas','alamat_ip'],'list'=>['waktu'=>'Waktu','nama'=>'Pengurus','aktivitas'=>'Aktivitas','alamat_ip'=>'Alamat IP'],'fields'=>[]],
];

$moduleKey = (string) ($_GET['modul'] ?? '');
if (!isset($modules[$moduleKey])) { http_response_code(404); exit('Modul tidak ditemukan.'); }
$module = $modules[$moduleKey];
require_role($module['cap']);
$isReadOnly = $module['readonly'] ?? false;
$table = $module['table']; $primary = $module['id'];
$editingId = (int) ($_GET['id'] ?? 0);
$action = (string) ($_GET['aksi'] ?? '');
$errorMessage = '';

function upload_file(array $file, string $directory): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) throw new RuntimeException('Unggahan gagal atau ukurannya melebihi 5 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Format berkas harus JPG, PNG, atau WebP.');
    $folder = __DIR__ . '/../uploads/' . $directory;
    if (!is_dir($folder) && !mkdir($folder, 0750, true) && !is_dir($folder)) throw new RuntimeException('Folder unggahan tidak dapat dibuat.');
    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $folder . '/' . $filename)) throw new RuntimeException('Berkas tidak dapat disimpan.');
    return $filename;
}

function valid_date_value(string $value, string $format): bool
{
    $date = DateTime::createFromFormat($format, $value);
    return $date !== false && $date->format($format) === $value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $postAction = (string) ($_POST['aksi'] ?? '');
    if ($postAction === 'hapus') {
        if ($isReadOnly || !in_array(current_user()['role'], ['Admin','Ketua RW'], true)) { http_response_code(403); exit('Penghapusan hanya dapat dilakukan Admin atau Ketua RW.'); }
        $id = (int) ($_POST['id'] ?? 0);
        $statement = $pdo->prepare("DELETE FROM `$table` WHERE `$primary` = ?");
        $statement->execute([$id]);
        log_activity($pdo, 'Menghapus data ' . $moduleKey . ' #' . $id);
        set_flash('success', 'Data berhasil dihapus.');
        redirect('admin/modul.php?modul=' . $moduleKey);
    }
    if ($postAction === 'simpan' && !$isReadOnly) {
        $id = (int) ($_POST['id'] ?? 0);
        $existing = null;
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$primary` = ?"); $stmt->execute([$id]); $existing = $stmt->fetch();
            if (!$existing) { http_response_code(404); exit('Data tidak ditemukan.'); }
        }
        $values = [];
        try {
            foreach ($module['fields'] as $name => $field) {
                $type = $field['type'] ?? 'text';
                if ($type === 'upload') {
                    $uploaded = upload_file($_FILES[$name] ?? [], $field['dir']);
                    if ($uploaded !== null) $values[$name] = $uploaded;
                    elseif (!$existing) $values[$name] = null;
                    continue;
                }
                $rawValue = $_POST[$name] ?? '';
                if (!is_string($rawValue) && !is_numeric($rawValue)) throw new RuntimeException('Format ' . $field['label'] . ' tidak valid.');
                $value = trim((string) $rawValue);
                if (($field['required'] ?? false) && $value === '') throw new RuntimeException($field['label'] . ' wajib diisi.');
                if ($value === '') { $values[$name] = null; continue; }
                if (isset($field['max']) && mb_strlen($value) > $field['max']) throw new RuntimeException($field['label'] . ' terlalu panjang.');
                if ($type === 'date' && !valid_date_value($value, 'Y-m-d')) throw new RuntimeException('Format tanggal tidak valid.');
                if ($type === 'datetime-local') {
                    if (!valid_date_value($value, 'Y-m-d\\TH:i')) throw new RuntimeException('Format tanggal publikasi tidak valid.');
                    $value = str_replace('T', ' ', $value) . ':00';
                }
                if ($type === 'select' && !in_array($value, $field['options'], true)) throw new RuntimeException('Pilihan ' . $field['label'] . ' tidak valid.');
                if ($type === 'relation') {
                    if (!ctype_digit($value)) throw new RuntimeException('Pilihan ' . $field['label'] . ' tidak valid.');
                    $check = $pdo->prepare('SELECT COUNT(*) FROM `' . $field['table'] . '` WHERE `' . $field['key'] . '` = ?'); $check->execute([(int) $value]);
                    if ((int) $check->fetchColumn() === 0) throw new RuntimeException('Data relasi ' . $field['label'] . ' tidak ditemukan.');
                    $value = (int) $value;
                }
                $values[$name] = $value;
            }
            if ($moduleKey === 'sampah' && ($values['status'] ?? '') === 'Ditolak' && trim((string) ($values['catatan_petugas'] ?? '')) === '') throw new RuntimeException('Alasan penolakan wajib diisi.');
            if ($moduleKey === 'pengaduan' && ($values['status'] ?? '') === 'Ditolak' && trim((string) ($values['tanggapan_pengurus'] ?? '')) === '') throw new RuntimeException('Alasan penolakan pengaduan wajib dicatat.');
            $transitions = [
                'sampah' => ['Menunggu' => ['Diverifikasi', 'Ditolak'], 'Diverifikasi' => ['Dalam Penanganan', 'Ditolak'], 'Dalam Penanganan' => ['Selesai', 'Ditolak'], 'Selesai' => [], 'Ditolak' => []],
                'pengaduan' => ['Menunggu' => ['Diverifikasi', 'Ditolak'], 'Diverifikasi' => ['Dalam Penanganan', 'Ditolak'], 'Dalam Penanganan' => ['Selesai', 'Ditolak'], 'Selesai' => [], 'Ditolak' => []],
                'pelanggaran' => ['Menunggu Verifikasi' => ['Terverifikasi', 'Tidak Terbukti'], 'Terverifikasi' => ['Selesai Ditindaklanjuti', 'Tidak Terbukti'], 'Tidak Terbukti' => [], 'Selesai Ditindaklanjuti' => []],
            ];
            if ($existing && isset($transitions[$moduleKey])) {
                $statusField = $moduleKey === 'pelanggaran' ? 'status_verifikasi' : 'status';
                $oldStatus = $existing[$statusField];
                $newStatus = $values[$statusField] ?? $oldStatus;
                if ($newStatus !== $oldStatus && !in_array($newStatus, $transitions[$moduleKey][$oldStatus] ?? [], true)) throw new RuntimeException('Perubahan status tidak mengikuti alur penanganan yang ditetapkan.');
            }
            if ($moduleKey === 'berita') {
                $title = (string) ($values['judul'] ?? $existing['judul'] ?? '');
                $slug = $existing['slug'] ?? trim(preg_replace('/[^a-z0-9]+/i', '-', strtolower($title)), '-');
                if (!$existing) $slug .= '-' . substr(hash('sha256', $title . microtime()), 0, 6);
                $values['slug'] = $slug;
                if (!$existing) $values['id_admin'] = (int) current_user()['id_admin'];
            }
            if (in_array($moduleKey, ['sampah','pengaduan','pelanggaran'], true)) $values['id_petugas'] = (int) current_user()['id_admin'];
            if (!$values) throw new RuntimeException('Tidak ada data yang dapat disimpan.');
            if ($existing) {
                $assignments = array_map(static fn($column) => "`$column` = ?", array_keys($values));
                $statement = $pdo->prepare('UPDATE `' . $table . '` SET ' . implode(', ', $assignments) . ' WHERE `' . $primary . '` = ?');
                $statement->execute([...array_values($values), $id]);
                log_activity($pdo, 'Memperbarui data ' . $moduleKey . ' #' . $id);
                set_flash('success', 'Perubahan berhasil disimpan.');
            } else {
                $columns = array_keys($values);
                $statement = $pdo->prepare('INSERT INTO `' . $table . '` (`' . implode('`,`', $columns) . '`) VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')');
                $statement->execute(array_values($values));
                $newId = (int) $pdo->lastInsertId();
                log_activity($pdo, 'Menambahkan data ' . $moduleKey . ' #' . $newId);
                set_flash('success', 'Data berhasil ditambahkan.');
            }
            redirect('admin/modul.php?modul=' . $moduleKey);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $errorMessage = $exception instanceof RuntimeException ? $exception->getMessage() : 'Data gagal disimpan. Periksa kemungkinan duplikasi atau relasi yang masih digunakan.';
        }
    }
}

$record = null;
if ($editingId > 0 && in_array($action, ['edit','detail'], true)) {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$primary` = ?"); $stmt->execute([$editingId]); $record = $stmt->fetch();
    if (!$record) { http_response_code(404); exit('Data tidak ditemukan.'); }
}
$isForm = !$isReadOnly && in_array($action, ['tambah','edit'], true);
$canDelete = !$isReadOnly && in_array(current_user()['role'], ['Admin','Ketua RW'], true);
$pageTitle = $module['title']; $activeModule = $moduleKey;
require __DIR__ . '/template/header.php'; require __DIR__ . '/template/sidebar.php';
?>
<div class="module-heading"><div><span class="eyebrow eyebrow-dark">PENGELOLAAN LINGKUNGAN</span><h1><?= e($module['title']) ?></h1><p><?= e($module['desc']) ?></p></div><?php if (!$isReadOnly && !$isForm && $action !== 'detail'): ?><a class="btn btn-admin-primary" href="<?= e(base_url('admin/modul.php?modul=' . $moduleKey . '&aksi=tambah')) ?>"><i class="fa-solid fa-plus me-2"></i>Tambah data</a><?php endif; ?></div>
<?php if ($errorMessage): ?><div class="alert alert-danger"><?= e($errorMessage) ?></div><?php endif; ?>
<?php if ($isForm): ?>
<form class="module-form" method="post" enctype="multipart/form-data" action="<?= e(base_url('admin/modul.php?modul=' . $moduleKey)) ?>"><?= csrf_field() ?><input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id" value="<?= (int) ($record[$primary] ?? 0) ?>"><div class="form-grid">
<?php foreach ($module['fields'] as $name => $field): $type = $field['type'] ?? 'text'; $value = $record[$name] ?? ''; if ($type === 'datetime-local' && $value) $value = date('Y-m-d\\TH:i', strtotime($value)); $full = in_array($type, ['textarea'], true); ?>
<div class="field-group <?= $full ? 'full' : '' ?>"><label for="f_<?= e($name) ?>"><?= e($field['label']) ?><?= ($field['required'] ?? false) ? ' *' : '' ?></label>
<?php if ($type === 'textarea'): ?><textarea class="form-control" id="f_<?= e($name) ?>" name="<?= e($name) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?>><?= e($value) ?></textarea>
<?php elseif ($type === 'select'): ?><select class="form-select" id="f_<?= e($name) ?>" name="<?= e($name) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?>><option value="">Pilih <?= e(mb_strtolower($field['label'])) ?></option><?php foreach ($field['options'] as $option): ?><option value="<?= e($option) ?>" <?= (string) $value === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select>
<?php elseif ($type === 'relation'): $options = $pdo->query('SELECT `' . $field['key'] . '` id, `' . $field['value'] . '` label FROM `' . $field['table'] . '` ORDER BY `' . $field['value'] . '`')->fetchAll(); ?><select class="form-select" id="f_<?= e($name) ?>" name="<?= e($name) ?>"><option value="">Tidak dipilih</option><?php foreach ($options as $option): ?><option value="<?= (int) $option['id'] ?>" <?= (string) $value === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option><?php endforeach; ?></select>
<?php elseif ($type === 'upload'): ?><input class="form-control" id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="file" accept="image/jpeg,image/png,image/webp"><div class="field-help"><?= $value ? '<a href="' . e(base_url('admin/lihat_bukti.php?jenis=' . urlencode($field['dir']) . '&file=' . urlencode((string) $value))) . '" target="_blank">Lihat file saat ini</a>. Kosongkan jika tidak diganti.' : 'Jenis yang diterima: JPG, PNG, WebP. Maksimal 5 MB.' ?></div>
<?php else: ?><input class="form-control" id="f_<?= e($name) ?>" name="<?= e($name) ?>" type="<?= e($type) ?>" value="<?= e($value) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?> <?= isset($field['max']) ? 'maxlength="' . (int) $field['max'] . '"' : '' ?>><?php endif; ?></div>
<?php endforeach; ?></div><div class="form-actions"><button class="btn btn-admin-primary" type="submit"><i class="fa-solid fa-floppy-disk me-2"></i>Simpan</button><a class="btn btn-admin-light" href="<?= e(base_url('admin/modul.php?modul=' . $moduleKey)) ?>">Batal</a></div></form>
<?php elseif ($action === 'detail' && $record): ?><section class="admin-panel p-4"><dl class="detail-list"><?php foreach ($module['fields'] as $name=>$field): ?><dt><?= e($field['label']) ?></dt><dd><?= nl2br(e($record[$name] ?? '-')) ?></dd><?php endforeach; ?></dl><div class="form-actions"><a class="btn btn-admin-primary" href="<?= e(base_url('admin/modul.php?modul=' . $moduleKey . '&aksi=edit&id=' . $editingId)) ?>">Edit data</a><a class="btn btn-admin-light" href="<?= e(base_url('admin/modul.php?modul=' . $moduleKey)) ?>">Kembali</a></div></section>
<?php else:
  $conditions=[]; $params=[]; $search=trim((string)($_GET['q']??''));
  if ($search !== '' && !empty($module['search'])) { $conditions[]='(' . implode(' OR ', array_map(static fn($column)=>"`$column` LIKE ?",$module['search'])) . ')'; foreach ($module['search'] as $_) $params[]='%'.$search.'%'; }
  foreach (($module['filter']??[]) as $column=>$label) { $selected=(string)($_GET['filter_'.$column]??''); if ($selected!=='') { $conditions[]="`$column` = ?"; $params[]=$selected; } }
  if (!empty($module['date'])) { $dateCol=$module['date']; $from=(string)($_GET['dari']??'');$to=(string)($_GET['sampai']??''); if ($from!=='' && valid_date_value($from,'Y-m-d')){$conditions[]="DATE(`$dateCol`) >= ?";$params[]=$from;}if($to!==''&&valid_date_value($to,'Y-m-d')){$conditions[]="DATE(`$dateCol`) <= ?";$params[]=$to;} }
  if ($moduleKey==='warga') { foreach (['rt','rw'] as $column) { $selected=trim((string)($_GET[$column]??''));if($selected!==''){$conditions[]="`$column` = ?";$params[]=$selected;} } }
  $where=$conditions?' WHERE '.implode(' AND ',$conditions):'';
  $countStmt=$pdo->prepare("SELECT COUNT(*) FROM `$table`".$where);$countStmt->execute($params);$total=(int)$countStmt->fetchColumn();$perPage=10;$page=max(1,(int)($_GET['halaman']??1));$pages=max(1,(int)ceil($total/$perPage));$page=min($page,$pages);$offset=($page-1)*$perPage;
  if ($moduleKey==='aktivitas') {$query="SELECT a.*, ad.nama FROM aktivitas a LEFT JOIN admin ad ON ad.id_admin=a.id_admin$where ORDER BY a.waktu DESC LIMIT $perPage OFFSET $offset";} else {$query="SELECT * FROM `$table`$where ORDER BY `$primary` DESC LIMIT $perPage OFFSET $offset";}
  $stmt=$pdo->prepare($query);$stmt->execute($params);$rows=$stmt->fetchAll();
?>
<form class="module-toolbar" method="get"><input type="hidden" name="modul" value="<?= e($moduleKey) ?>"><input class="form-control search-box" name="q" value="<?= e($search) ?>" placeholder="Cari data..."><?php foreach (($module['filter']??[]) as $column=>$label): ?><select class="form-select" name="filter_<?= e($column) ?>"><option value="">Semua <?= e(mb_strtolower($label)) ?></option><?php $field=$module['fields'][$column]??null;foreach (($field['options']??[]) as $option): ?><option value="<?= e($option) ?>" <?= (($_GET['filter_'.$column]??'')===$option)?'selected':'' ?>><?= e($option) ?></option><?php endforeach; ?></select><?php endforeach; ?><?php if (!empty($module['date'])): ?><input class="form-control" type="date" name="dari" aria-label="Tanggal mulai" value="<?= e($_GET['dari']??'') ?>"><input class="form-control" type="date" name="sampai" aria-label="Tanggal akhir" value="<?= e($_GET['sampai']??'') ?>"><?php endif; ?><?php if($moduleKey==='warga'): ?><input class="form-control" name="rt" placeholder="RT" value="<?= e($_GET['rt']??'') ?>" maxlength="5"><input class="form-control" name="rw" placeholder="RW" value="<?= e($_GET['rw']??'') ?>" maxlength="5"><?php endif; ?><button class="btn btn-admin-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button><a class="btn btn-admin-light" href="<?= e(base_url('admin/modul.php?modul='.$moduleKey)) ?>">Reset</a></form>
<section class="admin-panel"><div class="table-responsive"><table class="table admin-table"><thead><tr><?php foreach($module['list'] as $label): ?><th><?= e(mb_strtoupper($label)) ?></th><?php endforeach; ?><th>AKSI</th></tr></thead><tbody><?php foreach($rows as $row): ?><tr><?php foreach($module['list'] as $column=>$label): ?><td><?php $value=$row[$column]??'-';if(str_starts_with($column,'tanggal')||$column==='waktu')$value=format_tanggal((string)$value);if($column==='status'||$column==='status_verifikasi') echo '<span class="status-badge status-'.e(strtolower(str_replace(' ','-',(string)$value))).'">'.e($value).'</span>';else echo e($value); ?></td><?php endforeach; ?><td><div class="row-actions"><?php if(!$isReadOnly): ?><a title="Edit" href="<?= e(base_url('admin/modul.php?modul='.$moduleKey.'&aksi=edit&id='.(int)$row[$primary])) ?>"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?><?php if($canDelete): ?><form method="post" action="<?= e(base_url('admin/modul.php?modul='.$moduleKey)) ?>" data-confirm="Hapus data ini? Tindakan ini akan dicatat."><?= csrf_field() ?><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= (int)$row[$primary] ?>"><button class="delete-button" title="Hapus" type="submit"><i class="fa-regular fa-trash-can"></i></button></form><?php endif; ?></div></td></tr><?php endforeach; ?><?php if(!$rows): ?><tr><td class="table-empty" colspan="<?= count($module['list'])+1 ?>">Tidak ada data yang cocok.</td></tr><?php endif; ?></tbody></table></div><div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-3"><span class="record-count">Menampilkan <?= $total ? $offset+1 : 0 ?>–<?= min($offset+$perPage,$total) ?> dari <?= $total ?> data</span><?php if($pages>1): ?><nav><ul class="pagination pagination-sm mb-0"><?php for($n=1;$n<=$pages;$n++): $queryParams=$_GET;$queryParams['halaman']=$n; ?><li class="page-item <?= $n===$page?'active':'' ?>"><a class="page-link" href="?<?= e(http_build_query($queryParams)) ?>"><?= $n ?></a></li><?php endfor; ?></ul></nav><?php endif; ?></div></section>
<?php endif; require __DIR__ . '/template/footer.php'; ?>