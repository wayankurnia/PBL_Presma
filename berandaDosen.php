<?php
include 'config.php';
include 'ambilprestasi.php';
session_start();

// Fetch achievements
$result = fetchPrestasi($conn);
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
        <a href="login.html" class="menu-link">Keluar</a>
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

        <div class="section-title">DAFTAR PRESTASI MAHASISWA 🎓</div>
        <table id="resultTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Nama Kompetisi</th>
                    <th>Jenis Kompetisi</th>
                    <th>Tingkat Kompetisi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Juara</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nama_kompetisi']}</td>
                                <td>{$row['jenis_kompetisi']}</td>
                                <td>{$row['tingkat_kompetisi']}</td>
                                <td>{$row['tanggal_mulai']}</td>
                                <td>{$row['tanggal_selesai']}</td>
                                <td>{$row['juara']}</td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data prestasi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>