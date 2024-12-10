<?php
require_once 'config.php';
session_start();

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: proses_login.php");
    exit();
}

class Admin {
    private $db;

    public function __construct($db) {
        $this->db = $db->getConnection();
    }

    // Ambil semua prestasi terverifikasi
    public function getPrestasiVerified() {
        $query = "SELECT * FROM prestasi WHERE status = 'verified'";
        $stmt = sqlsrv_query($this->db, $query);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $prestasi = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $prestasi[] = $row;
        }
        return $prestasi;
    }

    // Pencarian berdasarkan parameter
    public function searchPrestasi($tanggalMulai = '', $tingkatLomba = '', $programStudi = '') {
        $query = "SELECT * FROM prestasi WHERE status = 'verified'";
        $params = [];

        if ($tanggalMulai) {
            $query .= " AND tanggal_mulai >= ?";
            $params[] = $tanggalMulai;
        }
        if ($tingkatLomba) {
            $query .= " AND tingkat_kompetisi = ?";
            $params[] = $tingkatLomba;
        }
        if ($programStudi) {
            $query .= " AND program_studi = ?";
            $params[] = $programStudi;
        }

        $stmt = sqlsrv_prepare($this->db, $query, $params);
        if (!$stmt || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }
}

// Inisialisasi objek Admin
$db = new Database();
$admin = new Admin($db);

// Ambil data prestasi terverifikasi
$prestasiVerified = $admin->getPrestasiVerified();

// Pencarian
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggalMulai = $_POST['tanggal_mulai'] ?? '';
    $tingkatLomba = $_POST['tingkat_lomba'] ?? '';
    $programStudi = $_POST['program_studi'] ?? '';

    $searchResults = $admin->searchPrestasi($tanggalMulai, $tingkatLomba, $programStudi);
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

        <h2>Data Prestasi</h2>
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
