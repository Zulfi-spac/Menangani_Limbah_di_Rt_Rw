CREATE DATABASE IF NOT EXISTS db_rt_rw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_rt_rw;

CREATE TABLE admin (
    id_admin INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    username VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin','Ketua RT','Ketua RW','Petugas Kebersihan') NOT NULL DEFAULT 'Admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE warga (
    id_warga INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_kk VARCHAR(32) NOT NULL,
    nik VARCHAR(32) NOT NULL UNIQUE,
    nama VARCHAR(120) NOT NULL,
    alamat VARCHAR(220) NOT NULL,
    nomor_rumah VARCHAR(20) DEFAULT NULL,
    nomor_telepon VARCHAR(30) DEFAULT NULL,
    rt VARCHAR(5) NOT NULL DEFAULT '001',
    rw VARCHAR(5) NOT NULL DEFAULT '001',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_warga_nama (nama), INDEX idx_warga_rt_rw (rt, rw)
) ENGINE=InnoDB;

CREATE TABLE peraturan (
    id_peraturan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul_peraturan VARCHAR(180) NOT NULL,
    kategori ENUM('Kebersihan Lingkungan','Pengelolaan Sampah','Ketertiban Kampung','Keamanan Lingkungan','Kegiatan Kemasyarakatan') NOT NULL,
    isi_peraturan TEXT NOT NULL,
    dasar_hukum VARCHAR(255) DEFAULT NULL,
    jenis_sanksi VARCHAR(255) DEFAULT NULL,
    tanggal_berlaku DATE DEFAULT NULL,
    status ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_peraturan_status (status)
) ENGINE=InnoDB;

CREATE TABLE laporan_sampah (
    id_laporan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_warga INT UNSIGNED DEFAULT NULL,
    judul_laporan VARCHAR(180) NOT NULL,
    jenis_sampah ENUM('Organik','Anorganik','Sampah B3 rumah tangga','Sampah elektronik','Sampah besar','Limbah cair','Lainnya') NOT NULL,
    deskripsi TEXT NOT NULL,
    lokasi VARCHAR(220) NOT NULL,
    tanggal_laporan DATE NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    status ENUM('Menunggu','Diverifikasi','Dalam Penanganan','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu',
    catatan_petugas TEXT DEFAULT NULL,
    kode_pelacakan_hash CHAR(64) DEFAULT NULL UNIQUE,
    id_petugas INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sampah_status_tanggal (status, tanggal_laporan),
    CONSTRAINT fk_sampah_warga FOREIGN KEY (id_warga) REFERENCES warga(id_warga) ON DELETE SET NULL,
    CONSTRAINT fk_sampah_petugas FOREIGN KEY (id_petugas) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pengaduan (
    id_pengaduan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_warga INT UNSIGNED DEFAULT NULL,
    judul VARCHAR(180) NOT NULL,
    kategori ENUM('Pembuangan sampah sembarangan','Pembakaran sampah','Saluran air tersumbat','Pencemaran lingkungan','Kebisingan','Ketertiban umum','Lainnya') NOT NULL,
    deskripsi TEXT NOT NULL,
    lokasi VARCHAR(220) NOT NULL,
    tanggal_pengaduan DATE NOT NULL,
    foto_bukti VARCHAR(255) DEFAULT NULL,
    status ENUM('Menunggu','Diverifikasi','Dalam Penanganan','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu',
    tanggapan_pengurus TEXT DEFAULT NULL,
    kode_pelacakan_hash CHAR(64) DEFAULT NULL UNIQUE,
    id_petugas INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pengaduan_status_tanggal (status, tanggal_pengaduan),
    CONSTRAINT fk_pengaduan_warga FOREIGN KEY (id_warga) REFERENCES warga(id_warga) ON DELETE SET NULL,
    CONSTRAINT fk_pengaduan_petugas FOREIGN KEY (id_petugas) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pelanggaran (
    id_pelanggaran INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_warga INT UNSIGNED DEFAULT NULL,
    id_peraturan INT UNSIGNED DEFAULT NULL,
    tanggal_kejadian DATE NOT NULL,
    lokasi_kejadian VARCHAR(220) NOT NULL,
    deskripsi_kejadian TEXT NOT NULL,
    bukti VARCHAR(255) DEFAULT NULL,
    status_verifikasi ENUM('Menunggu Verifikasi','Terverifikasi','Tidak Terbukti','Selesai Ditindaklanjuti') NOT NULL DEFAULT 'Menunggu Verifikasi',
    tindak_lanjut TEXT DEFAULT NULL,
    catatan_pengurus TEXT DEFAULT NULL,
    id_petugas INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pelanggaran_status (status_verifikasi),
    CONSTRAINT fk_pelanggaran_warga FOREIGN KEY (id_warga) REFERENCES warga(id_warga) ON DELETE SET NULL,
    CONSTRAINT fk_pelanggaran_peraturan FOREIGN KEY (id_peraturan) REFERENCES peraturan(id_peraturan) ON DELETE SET NULL,
    CONSTRAINT fk_pelanggaran_petugas FOREIGN KEY (id_petugas) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE berita (
    id_berita INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    isi MEDIUMTEXT NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    tanggal_publikasi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Draft','Terbit') NOT NULL DEFAULT 'Draft',
    id_admin INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_berita_status_tanggal (status, tanggal_publikasi),
    CONSTRAINT fk_berita_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE aktivitas (
    id_aktivitas BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_admin INT UNSIGNED DEFAULT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    waktu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    alamat_ip VARCHAR(45) DEFAULT NULL,
    INDEX idx_aktivitas_waktu (waktu),
    CONSTRAINT fk_aktivitas_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seluruh akun dan isi berikut adalah data dummy untuk pengujian lokal.
INSERT INTO admin (nama, username, password, role) VALUES
('Administrator Demo', 'admin', '$2y$10$SNlbgLziwNjd7j6VZfK0d.hp.KYPYeAs3qunRH.kk92Q814LqQWt2', 'Admin');

INSERT INTO warga (nomor_kk, nik, nama, alamat, nomor_rumah, nomor_telepon, rt, rw) VALUES
('DUMMY-KK-001', 'DUMMY-NIK-001', 'Warga Contoh Satu', 'Jalan Melati Blok A', 'A-01', '000000000001', '001', '001'),
('DUMMY-KK-002', 'DUMMY-NIK-002', 'Warga Contoh Dua', 'Jalan Kenanga Blok B', 'B-02', '000000000002', '001', '001');

INSERT INTO peraturan (judul_peraturan, kategori, isi_peraturan, dasar_hukum, jenis_sanksi, tanggal_berlaku, status) VALUES
('Pemilahan dan Penempatan Sampah', 'Pengelolaan Sampah', 'Warga bersama-sama memilah sampah dan menempatkannya di titik pengumpulan sesuai jadwal yang disepakati.', 'Contoh: hasil musyawarah warga (sesuaikan dengan keputusan setempat).', NULL, '2025-01-01', 'Aktif'),
('Menjaga Kebersihan Saluran Air', 'Kebersihan Lingkungan', 'Warga menjaga saluran air di sekitar rumah dan melaporkan penyumbatan kepada pengurus.', 'Contoh: hasil musyawarah warga (sesuaikan dengan keputusan setempat).', NULL, '2025-01-01', 'Aktif');

INSERT INTO laporan_sampah (id_warga, judul_laporan, jenis_sampah, deskripsi, lokasi, tanggal_laporan, status) VALUES
(1, 'Tumpukan daun di taman', 'Organik', 'Terdapat tumpukan daun yang perlu diangkut.', 'Taman blok A', CURRENT_DATE, 'Menunggu'),
(2, 'Sampah kemasan di selokan', 'Anorganik', 'Sampah kemasan menyumbat sebagian saluran.', 'Gang Kenanga', CURRENT_DATE, 'Dalam Penanganan');

INSERT INTO pengaduan (id_warga, judul, kategori, deskripsi, lokasi, tanggal_pengaduan, status) VALUES
(1, 'Saluran air perlu dibersihkan', 'Saluran air tersumbat', 'Aliran air melambat setelah hujan.', 'Jalan Melati', CURRENT_DATE, 'Menunggu');

INSERT INTO pelanggaran (id_warga, id_peraturan, tanggal_kejadian, lokasi_kejadian, deskripsi_kejadian, status_verifikasi) VALUES
(2, 1, CURRENT_DATE, 'Area titik pengumpulan', 'Contoh catatan dugaan, belum diverifikasi dan tidak ditujukan untuk publik.', 'Menunggu Verifikasi');

INSERT INTO berita (judul, slug, isi, tanggal_publikasi, status, id_admin) VALUES
('Jadwal Kerja Bakti Lingkungan', 'jadwal-kerja-bakti-lingkungan', 'Warga diundang mengikuti kegiatan kerja bakti sesuai jadwal yang akan ditetapkan pengurus. Informasi ini merupakan contoh data dummy.', NOW(), 'Terbit', 1);