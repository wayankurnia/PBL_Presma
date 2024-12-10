<?php
include 'config.php';
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

// Ambil data mahasiswa
$mahasiswaQuery = "SELECT * FROM mahasiswa";
$mahasiswaStmt = sqlsrv_query($conn, $mahasiswaQuery);

// Ambil data dosen
$dosenQuery = "SELECT * FROM dosen";
$dosenStmt = sqlsrv_query($conn, $dosenQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Pengguna</title>
    <link rel="stylesheet" href="CSS/berandaSuperadmin.css">
</head>
<body>
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <a href="dashboardSuperadmin.php" class="menu-link">Dashboard</a>
        <a href="tambahDataPusat.html" class="menu-link">Tambah Data</a>
        <a href="lihatDataPengguna.php" class="menu-link">Lihat Data Pengguna</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.php" class="menu-link">Keluar</a>
    </div>

    <div class="content">
        <h1>Data Pengguna</h1>

        <h2>Data Mahasiswa</h2>
        <table>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
            </tr>
            <?php
            while ($row = sqlsrv_fetch_array($mahasiswaStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <h2>Data Dosen</h2>
        <table>
            <tr>
                <th>NIDN</th>
                <th>Nama</th>
            </tr>
            <?php
            while ($row = sqlsrv_fetch_array($dosenStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nidn']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>