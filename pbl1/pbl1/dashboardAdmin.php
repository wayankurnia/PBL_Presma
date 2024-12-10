<?php
include 'config.php';
session_start();

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: proses_login.php");
    exit();
}

// Ambil data prestasi terverifikasi
$prestasiQuery = "SELECT * FROM prestasi WHERE status = 'verified'";
$prestasiStmt = sqlsrv_query($conn, $prestasiQuery);

// Pencarian
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggalMulai = $_POST['tanggal_mulai'] ?? '';
    $tingkatLomba = $_POST['tingkat_lomba'] ?? '';
    $programStudi = $_POST['program_studi'] ?? '';

    $searchQuery = "SELECT * FROM prestasi WHERE status = 'verified'";

    $conditions = [];
    if ($tanggalMulai) {
        $conditions[] = "tanggal_mulai >= ?";
    }
    if ($tingkatLomba) {
        $conditions[] = "tingkat_kompetisi = ?";
    }
    if ($programStudi) {
        $conditions[] = "program_studi = ?";
    }

    if (count($conditions) > 0) {
        $searchQuery .= " AND " . implode(" AND ", $conditions);
    }

    $params = [];
    if ($tanggalMulai) {
        $params[] = $tanggalMulai;
    }
    if ($tingkatLomba) {
        $params[] = $tingkatLomba;
    }
    if ($programStudi) {
        $params[] = $programStudi;
    }

    $searchStmt = sqlsrv_prepare($conn, $searchQuery, $params);
    if (sqlsrv_execute($searchStmt)) {
        while ($row = sqlsrv_fetch_array($searchStmt, SQLSRV_FETCH_ASSOC)) {
            $searchResults[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="CSS/dashboardAdmin.css">
</head>
<body>
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        <p>Admin</p>
        <a href="dashboardAdmin.php" class="menu-link">Dashboard</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.php" class="menu-link">Keluar</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Dashboard Admin</h1>
            <img src="gambar/jti.png" alt="Logo JTI">
        </div>

        <form method="POST" action="dashboardAdmin.php">
            <div class="search-container">
                <div class="search-box">
                    <label for="tanggal_mulai">Tanggal Mulai:</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai">
                </div>

                <div class="search-box">
                    <label for="tingkat_lomba">Tingkat Lomba:</label>
                    <select id="tingkat_lomba" name="tingkat_lomba">
                        <option value="">Semua</option>
                        <option value="internasional">Internasional</option>
                        <option value="nasional">Nasional</option>
                        <option value="provinsi">Provinsi</option>
                        <option value="kabupaten/kota">Kabupaten/Kota</option>
                    </select>
                </div>

                <div class="search-box">
                    <label for="program_studi">Program Studi:</label>
                    <select id="program_studi" name="program_studi">
                        <option value="">Semua</option>
                        <option value="TI">Teknik Informatika</option>
                        <option value="SIB">Sistem Informasi Bisnis</option>
                        <option value="PPLS">Pengembangan Piranti Lunak Situs</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <h2>Data Prest asi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nama Kompetisi</th>
                    <th>Tingkat Lomba</th>
                    <th>Program Studi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $prestasi): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prestasi['id']); ?></td>
                        <td><?php echo htmlspecialchars($prestasi['nama']); ?></td>
                        <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                        <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                        <td><?php echo htmlspecialchars($prestasi['program_studi']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>