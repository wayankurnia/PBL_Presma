<?php
require_once 'config.php';
session_start();

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

require_once 'Prestasi.php';

// Inisialisasi Database dan Prestasi
$db = new Database();
$prestasi = new Prestasi($db);

// Ambil ID Prestasi dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID Prestasi tidak valid.");
}
$id_prestasi = intval($_GET['id']);

// Proses form jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestasi->updatePrestasi($id_prestasi, $_POST, $_FILES);
}

// Ambil data prestasi untuk diisi di form
$data = $prestasi->getPrestasiById($id_prestasi);
if (!$data) {
    die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Prestasi</title>
</head>
<body>
    <h1>Edit Prestasi</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kompetisi:</label>
        <input type="text" name="nama_kompetisi" value="<?= htmlspecialchars($data['nama_kompetisi']) ?>" required><br>

        <label>Tingkat Kompetisi:</label>
        <input type="text" name="tingkat_kompetisi" value="<?= htmlspecialchars($data['tingkat_kompetisi']) ?>" required><br>

        <label>Juara:</label>
        <input type="text" name="juara" value="<?= htmlspecialchars($data['juara']) ?>"><br>

        <label>Tanggal Mulai:</label>
        <input type="date" name="tanggal_mulai" value="<?= $data['tanggal_mulai']->format('Y-m-d') ?>" required><br>

        <label>Tanggal Selesai:</label>
        <input type="date" name="tanggal_selesai" value="<?= $data['tanggal_selesai']->format('Y-m-d') ?>" required><br>

        <label>Upload Sertifikat (opsional):</label>
        <input type="file" name="file_sertifikat"><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
