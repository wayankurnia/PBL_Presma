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

// Hapus data prestasi
$prestasi->hapusPrestasi($id_prestasi);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Prestasi</title>
</head>
<body>
    <p>Data prestasi berhasil dihapus.</p>
    <a href="dashboardMahasiswa.php">Kembali ke Dashboard</a>
</body>
</html>
