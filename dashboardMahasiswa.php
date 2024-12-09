<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mahasiswa') {
    header("Location: login.html");
    exit();
}

// Koneksi ke database
include 'config.php';

// Ambil data prestasi untuk user yang sedang login
$query = "SELECT * FROM prestasi WHERE user_id = ?";
$stmt = sqlsrv_prepare($conn, $query, array($_SESSION['user_id']));

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika prepare gagal
}

if (!sqlsrv_execute($stmt)) {
    die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika execute gagal
}

// Inisialisasi variabel
$totalPrestasi = 0;
$totalPoin = 0;

// Hitung total prestasi dan poin
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $totalPrestasi++;

    // Hitung poin berdasarkan tingkat kompetisi
    switch ($row['tingkat_kompetisi']) {
        case 'internasional':
            $totalPoin += 5;
            break;
        case 'nasional':
            $totalPoin += 4;
            break;
        case 'provinsi':
            $totalPoin += 3;
            break;
        case 'kabupaten':
        case 'kota':
            $totalPoin += 2;
            break;
        case 'internal':
            $totalPoin += 1;
            break;
    }

    // Hitung poin berdasarkan juara
    switch ($row['juara']) {
        case 'Juara 1':
            $totalPoin += 6;
            break;
        case 'Juara 2':
            $totalPoin += 5;
            break;
        case 'Juara 3':
            $totalPoin += 4;
            break;
        case 'Harapan 1':
            $totalPoin += 3;
            break;
 case 'Harapan 2':
            $totalPoin += 2;
            break;
        case 'Harapan 3':
            $totalPoin += 1;
            break;
    }
}

// Set nilai ke dalam session
$_SESSION['jumlahPrestasi'] = $totalPrestasi;
$_SESSION['totalPoin'] = $totalPoin;

// Ambil jumlah data terverifikasi, pending, dan ditolak
$verifiedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE user_id = ? AND status = 'verified'";
$pendingQuery = "SELECT COUNT(*) as count FROM prestasi WHERE user_id = ? AND status = 'proses'";
$rejectedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE user_id = ? AND status = 'rejected'";

$verifiedStmt = sqlsrv_prepare($conn, $verifiedQuery, array($_SESSION['user_id']));
$pendingStmt = sqlsrv_prepare($conn, $pendingQuery, array($_SESSION['user_id']));
$rejectedStmt = sqlsrv_prepare($conn, $rejectedQuery, array($_SESSION['user_id']));

sqlsrv_execute($verifiedStmt);
sqlsrv_execute($pendingStmt);
sqlsrv_execute($rejectedStmt);

$verifiedResult = sqlsrv_fetch_array($verifiedStmt, SQLSRV_FETCH_ASSOC);
$pendingResult = sqlsrv_fetch_array($pendingStmt, SQLSRV_FETCH_ASSOC);
$rejectedResult = sqlsrv_fetch_array($rejectedStmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="CSS/dashboardMahasiswa.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3><?php echo $_SESSION['username']; ?></h3>
        <p>NIM: <?php echo $_SESSION['nim']; ?></p>
        <p>Program Studi: <?php echo $_SESSION['prodi']; ?></p>
        <a href="#" class="menu-link">Beranda</a>
        <a href="TambahData.html" class="menu-link">Tambah Data</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.html" class="menu-link">Keluar</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Dashboard Mahasiswa</h1>
            <img src="jti.png" alt="Logo JTI">
        </div>
        <div class="main">
            <div class="status-background">
                <div class="status-boxes">
                    <div class="status-box">
                        <h2><?php echo $verifiedResult['count']; ?></h2>
                        <h3>Terverifikasi</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $pendingResult['count']; ?></h2>
                        <h3>Pending</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $rejectedResult['count']; ?></h2>
                        <h3>Ditolak</h3>
                    </div>
                </div>
            </div>

            <!-- Riwayat Prestasi -->
            <div class="info-box">
                <h2>Riwayat Prestasi</h2>
                <table>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Prestasi</th>
                        <th>Tingkat Kompetisi</th>
                        <th>Juara</th>
                    </tr>
                    <?php
                    // Ambil data riwayat prestasi
                    $queryRiwayat = "SELECT * FROM prestasi WHERE user_id = ?";
                    $stmtRiwayat = sqlsrv_prepare($conn, $queryRiwayat, array($_SESSION['user_id']));
                    sqlsrv_execute($stmtRiwayat);

                    while ($rowRiwayat = sqlsrv_fetch_array($stmtRiwayat, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>
                                <td>" . date_format($rowRiwayat['tanggal'], 'd-m-Y') . "</td>
                                <td>" . $rowRiwayat['nama_prestasi'] . "</td>
                                  <td>" . htmlspecialchars($rowRiwayat['tingkat_kompetisi']) . "</td>
                                </td>
                                <td>" . $rowRiwayat['juara'] . "</td>
                              </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>