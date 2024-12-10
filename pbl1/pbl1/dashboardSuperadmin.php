<?php
include 'config.php';
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

// Ambil jumlah data terverifikasi, pending, dan ditolak
$verifiedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'verified'";
$pendingQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'proses'";
$rejectedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'rejected'";

// Eksekusi query menggunakan sqlsrv
$verifiedStmt = sqlsrv_query($conn, $verifiedQuery);
$pendingStmt = sqlsrv_query($conn, $pendingQuery);
$rejectedStmt = sqlsrv_query($conn, $rejectedQuery);

// Periksa apakah query berhasil
if ($verifiedStmt === false || $pendingStmt === false || $rejectedStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Ambil hasil query
$verifiedResult = sqlsrv_fetch_array($verifiedStmt, SQLSRV_FETCH_ASSOC);
$pendingResult = sqlsrv_fetch_array($pendingStmt, SQLSRV_FETCH_ASSOC);
$rejectedResult = sqlsrv_fetch_array($rejectedStmt, SQLSRV_FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin</title>
    <link rel="stylesheet" href="CSS/berandaSuperadmin.css">
</head>
<body>
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3>Vit Zuraida, S.Kom., M.Kom.</h3>
        <p>Superadmin</p>
        <a href="dashboardSuperadmin.php" class="menu-link">Dashboard</a>
        <a href="tambahDataPusat.html" class="menu-link">Tambah Data</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="lihatDataPengguna.php" class="menu-link">Lihat Data Pengguna</a>
        <a href="login.php" class="menu-link">Keluar</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Data Prestasi yang Perlu Diverifikasi</h1>
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo">
        </div>

        <div class="main">
            <div class="status-background">
                <div class="status-boxes">
                    <div class="status-box">
                        <h2><?php echo htmlspecialchars($verifiedResult['count']); ?></h2>
                        <h3>Terverifikasi</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo htmlspecialchars($pendingResult['count']); ?></h2>
                        <h3>Pending</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo htmlspecialchars($rejectedResult['count']); ?></h2>
                        <h3>Ditolak</h3>
                    </div>
                </div>
            </div>

            <!-- Contoh bagian dari dashboardSuperadmin.php -->
<div class="pending-data">
    <h2>Data Prestasi Pending</h2>
    <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>Nama Kompetisi</th>
            <th>Tanggal Mulai</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Ambil data prestasi pending 
        $pendingDataQuery = "SELECT * FROM prestasi WHERE status = 'proses'";
        $pendingDataStmt = sqlsrv_query($conn, $pendingDataQuery);

        if ($pendingDataStmt === false) {
            echo "<tr><td colspan='5'>Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "</td></tr>";
        } else {
            $hasData = false;
            while ($data = sqlsrv_fetch_array($pendingDataStmt, SQLSRV_FETCH_ASSOC)) {
                $hasData = true;
                echo "<tr>
                        <td>" . htmlspecialchars($data['nama']) . "</td>
                        <td>" . htmlspecialchars($data['program_studi']) . "</td>
                        <td>" . htmlspecialchars($data['nama_kompetisi']) . "</td>
                        <td>" . ($data['tanggal_mulai'] instanceof DateTime ? $data['tanggal_mulai']->format('d-m-Y') : 'Tidak Tersedia') . "</td>
                        <td>
                            <a href='pratinjau.php?id=" . urlencode($data['id']) . "' class='btn-preview'>Pratinjau</a>
                        </td>
                      </tr>";
            }
            if (!$hasData) {
                echo "<tr><td colspan='5'>Tidak ada data prestasi pending.</td></tr>";
            }
        }
        ?>
    </table>
</div>
        </div>
    </div>
</body>
</html>
