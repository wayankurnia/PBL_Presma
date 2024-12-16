<?php
session_start();

// Cek apakah user sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'dosen') {
    header("Location: login.php");
    exit();
}

// Jika tombol logout diklik, lakukan proses logout
if (isset($_GET['logout'])) {
    // Hapus session dan redirect ke halaman login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="stylesheet" href="CSS/berandaDosen.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3>Vit Zuraida, S.Kom., M.Kom.</h3>
        <p>Dosen</p>
        <a href="#" class="menu-link">Dashboard</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <!-- Tombol Keluar -->
        <a href="berandaDosen.php?logout=true" class="menu-link">Keluar</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Beranda</h1>
            <img src="gambar/jti.png" alt="Logo JTI">
        </div>
        <div class="main">
            <a href="#">Riwayat Prestasi Mahasiswa Yang Dibimbing</a>
            <div class="info-box">
                <p>Selamat Datang! Vit Zuraida, S.Kom., M.Kom.<br>Mahasiswa Belum Mempunyai Riwayat Prestasi</p>
            </div>
        </div>
    </div>
</body>
</html>
