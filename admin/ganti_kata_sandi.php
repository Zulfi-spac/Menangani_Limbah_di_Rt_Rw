<?php
require_once __DIR__ . '/../config/koneksi.php';
require_login();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $current = (string) ($_POST['kata_sandi_lama'] ?? '');
    $next = (string) ($_POST['kata_sandi_baru'] ?? '');
    $confirm = (string) ($_POST['konfirmasi'] ?? '');
    $statement = $pdo->prepare('SELECT password FROM admin WHERE id_admin = ?');
    $statement->execute([(int) current_user()['id_admin']]);
    $hash = (string) $statement->fetchColumn();
    if (!password_verify($current, $hash)) $error = 'Kata sandi saat ini tidak sesuai.';
    elseif (strlen($next) < 12) $error = 'Kata sandi baru harus minimal 12 karakter.';
    elseif ($next !== $confirm) $error = 'Konfirmasi kata sandi tidak sama.';
    else {
        $update = $pdo->prepare('UPDATE admin SET password = ? WHERE id_admin = ?');
        $update->execute([password_hash($next, PASSWORD_DEFAULT), (int) current_user()['id_admin']]);
        log_activity($pdo, 'Mengubah kata sandi akun');
        set_flash('success', 'Kata sandi berhasil diperbarui.');
        redirect('admin/ganti_kata_sandi.php');
    }
}
$pageTitle='Ganti Kata Sandi';$activeModule='';require __DIR__.'/template/header.php';require __DIR__.'/template/sidebar.php';
?><div class="module-heading"><div><span class="eyebrow eyebrow-dark">KEAMANAN AKUN</span><h1>Ganti kata sandi</h1><p>Gunakan kata sandi unik dan tidak dibagikan kepada orang lain.</p></div></div><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form class="module-form" method="post"><?=csrf_field()?><div class="form-grid"><div class="field-group full"><label for="kata_sandi_lama">Kata sandi saat ini</label><input class="form-control" id="kata_sandi_lama" name="kata_sandi_lama" type="password" required autocomplete="current-password"></div><div class="field-group"><label for="kata_sandi_baru">Kata sandi baru</label><input class="form-control" id="kata_sandi_baru" name="kata_sandi_baru" type="password" minlength="12" required autocomplete="new-password"><div class="field-help">Minimal 12 karakter.</div></div><div class="field-group"><label for="konfirmasi">Ulangi kata sandi baru</label><input class="form-control" id="konfirmasi" name="konfirmasi" type="password" minlength="12" required autocomplete="new-password"></div></div><div class="form-actions"><button class="btn btn-admin-primary" type="submit">Simpan kata sandi</button></div></form><?php require __DIR__.'/template/footer.php'; ?>