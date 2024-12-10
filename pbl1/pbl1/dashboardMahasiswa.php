<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'config.php';

// Ambil data mahasiswa berdasarkan user_id yang ada di session
$user_id = $_SESSION['user_id'];
$query = "SELECT m.nim, m.nama, m.prodi FROM mahasiswa m WHERE m.user_id = ?";
$params = array($user_id);
$stmt = sqlsrv_prepare($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (!sqlsrv_execute($stmt)) {
    die(print_r(sqlsrv_errors(), true));
}

$userData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($userData) {
    $_SESSION['nim'] = $userData['nim'];
    $_SESSION['nama'] = $userData['nama'];
    $_SESSION['prodi'] = $userData['prodi'];
} else {
    $_SESSION['nim'] = "Tidak ditemukan";
    $_SESSION['nama'] = "Tidak ditemukan";
    $_SESSION['prodi'] = "Tidak ditemukan";
}

// Hitung poin dan jumlah prestasi yang terverifikasi
$prestasiVerifiedQuery = "SELECT * FROM prestasi WHERE user_id = ? AND status = 'verified'";
$prestasiVerifiedStmt = sqlsrv_prepare($conn, $prestasiVerifiedQuery, array($user_id));

if (!sqlsrv_execute($prestasiVerifiedStmt)) {
    die(print_r(sqlsrv_errors(), true));
}

$poinTingkat = [
    "internasional" => 5,
    "nasional" => 4,
    "provinsi" => 3,
    "kabupaten/kota" => 2,
    "internal" => 1
];
$poinJuara = [
    "juara 1" => 6,
    "juara 2" => 5,
    "juara 3" => 4,
    "harapan 1" => 3,
    "harapan 2" => 2,
    "harapan 3" => 1
];

$totalPoin = 0;
$jumlahPrestasi = 0;

while ($prestasi = sqlsrv_fetch_array($prestasiVerifiedStmt, SQLSRV_FETCH_ASSOC)) {
    $tingkat = strtolower($prestasi['tingkat_kompetisi']);
    $juara = strtolower($prestasi['juara']);

    $poinTingkatValue = $poinTingkat[$tingkat] ?? 0;
    $poinJuaraValue = $poinJuara[$juara] ?? 0;

    $totalPoin += $poinTingkatValue + $poinJuaraValue;
    $jumlahPrestasi++;
}

sqlsrv_execute($prestasiVerifiedStmt);

// Prestasi pending
$prestasiPendingQuery = "SELECT * FROM prestasi WHERE user_id = ? AND status = 'proses'";
$prestasiPendingStmt = sqlsrv_prepare($conn, $prestasiPendingQuery, array($user_id));

if (!sqlsrv_execute($prestasiPendingStmt)) {
    die(print_r(sqlsrv_errors(), true));
}

// Peringkat
$peringkatQuery = "SELECT user_id, SUM(
    CASE 
        WHEN tingkat_kompetisi = 'internasional' THEN 5
        WHEN tingkat_kompetisi = 'nasional' THEN 4
        WHEN tingkat_kompetisi = 'provinsi' THEN 3
        WHEN tingkat_kompetisi = 'kabupaten/kota' THEN 2
        WHEN tingkat_kompetisi = 'internal' THEN 1
        ELSE 0
    END + 
    CASE 
        WHEN juara = 'juara 1' THEN 6
        WHEN juara = 'juara 2' THEN 5
        WHEN juara = 'juara 3' THEN 4
        WHEN juara = 'harapan 1' THEN 3
        WHEN juara = 'harapan 2' THEN 2
        WHEN juara = 'harapan 3' THEN 1
        ELSE 0
    END
) AS total_poin
FROM prestasi WHERE status = 'verified'
GROUP BY user_id
ORDER BY total_poin DESC";

$peringkatStmt = sqlsrv_query($conn, $peringkatQuery);
if ($peringkatStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$peringkat = 1;
while ($row = sqlsrv_fetch_array($peringkatStmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['user_id'] == $user_id) {
        break;
    }
    $peringkat++;
}
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
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3><?php echo htmlspecialchars($_SESSION['nama']); ?></h3>
        <p>NIM <br><?php echo htmlspecialchars($_SESSION['nim']); ?></p>
        <p>Program Studi<br><?php echo htmlspecialchars($_SESSION['prodi']); ?></p>

        <a href="#" class="menu-link">Beranda</a>
        <a href="TambahData.html" class="menu-link">Tambah Data</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.php" class="menu-link">Keluar</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Beranda</h1>
            <img src="gambar/jti.png" alt="Logo JTI">
        </div>
        <div class="main">
            <div class="status-background">
                <div class="status-boxes">
                <div class="status-box">
                        <h2><?php echo $peringkat; ?></h2>
                        <h3>Peringkat</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $jumlahPrestasi; ?></h2>
                        <h3>Jumlah Prestasi</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $totalPoin; ?></h2>
                        <h3>Poin</h3>
                    </div>
                </div>
            </div>

            <div class="prestasi-section">
                <h2>Prestasi Pending</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kompetisi</th>
                            <th>Tingkat</th>
                            <th>Juara</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($prestasi = sqlsrv_fetch_array($prestasiPendingStmt, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['juara']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="prestasi-section">
                <h2>Prestasi Terverifikasi</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kompetisi</th>
                            <th>Tingkat</th>
                            <th>Juara</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($prestasi = sqlsrv_fetch_array($prestasiVerifiedStmt, SQLSRV_FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['juara']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

            
