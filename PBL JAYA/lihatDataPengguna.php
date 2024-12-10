<?php
require_once 'config.php';
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

class Pengguna {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    // Ambil data mahasiswa
    public function getMahasiswaData() {
        $mahasiswaQuery = "SELECT * FROM mahasiswa";
        $mahasiswaStmt = sqlsrv_query($this->conn, $mahasiswaQuery);
        $mahasiswaData = [];

        while ($row = sqlsrv_fetch_array($mahasiswaStmt, SQLSRV_FETCH_ASSOC)) {
            $mahasiswaData[] = $row;
        }

        return $mahasiswaData;
    }

    // Ambil data dosen
    public function getDosenData() {
        $dosenQuery = "SELECT * FROM dosen";
        $dosenStmt = sqlsrv_query($this->conn, $dosenQuery);
        $dosenData = [];

        while ($row = sqlsrv_fetch_array($dosenStmt, SQLSRV_FETCH_ASSOC)) {
            $dosenData[] = $row;
        }

        return $dosenData;
    }
}

// Inisialisasi objek database dan pengguna
$db = new Database();
$pengguna = new Pengguna($db);

// Ambil data mahasiswa dan dosen
$mahasiswaData = $pengguna->getMahasiswaData();
$dosenData = $pengguna->getDosenData();
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
            if (count($mahasiswaData) > 0) {
                foreach ($mahasiswaData as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Tidak ada data mahasiswa.</td></tr>";
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
            if (count($dosenData) > 0) {
                foreach ($dosenData as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nidn']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Tidak ada data dosen.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
