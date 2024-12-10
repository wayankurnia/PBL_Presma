<?php
require_once 'config.php';
session_start();

class DashboardSuperadmin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function checkSuperadmin() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
            header("Location: proses_login.php");
            exit();
        }
    }

    public function getPrestasiCount() {
        $counts = ['verified' => 0, 'pending' => 0, 'rejected' => 0];

        // Query untuk menghitung jumlah prestasi
        $queries = [
            'verified' => "SELECT COUNT(*) as count FROM prestasi WHERE status = 'verified'",
            'pending' => "SELECT COUNT(*) as count FROM prestasi WHERE status = 'proses'",
            'rejected' => "SELECT COUNT(*) as count FROM prestasi WHERE status = 'rejected'"
        ];

        foreach ($queries as $status => $query) {
            $stmt = sqlsrv_query($this->conn, $query);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $counts[$status] = $result['count'];
        }

        return $counts;
    }

    public function getPrestasiRejected() {
        $query = "SELECT * FROM prestasi WHERE status = 'rejected'";
        $stmt = sqlsrv_query($this->conn, $query);
        $results = [];

        if ($stmt === false) {
            echo "Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true));
            return $results;
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getPrestasiPending() {
        $query = "SELECT * FROM prestasi WHERE status = 'proses'";
        $stmt = sqlsrv_query($this->conn, $query);
        $results = [];

        if ($stmt === false) {
            echo "Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true));
            return $results;
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }
}

// Inisialisasi Database dan Dashboard
$db = new Database();
$dashboard = new DashboardSuperadmin($db);

// Pastikan hanya superadmin yang mengakses
$dashboard->checkSuperadmin();

// Ambil data jumlah prestasi
$counts = $dashboard->getPrestasiCount();
$rejectedData = $dashboard->getPrestasiRejected();
$pendingData = $dashboard->getPrestasiPending();
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
        <a href="gantipw.php" class="menu-link">Edit Password</a>
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
                        <h2><?php echo htmlspecialchars($counts['verified']); ?></h2>
                        <h3>Terverifikasi</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo htmlspecialchars($counts['pending']); ?></h2>
                        <h3>Pending</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo htmlspecialchars($counts['rejected']); ?></h2>
                        <h3>Ditolak</h3>
                    </div>
                </div>
            </div>

            <!-- Data Pending -->
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
                    <?php if (empty($pendingData)): ?>
                        <tr><td colspan="5">Tidak ada data prestasi pending.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pendingData as $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                <td><?php echo htmlspecialchars($data['program_studi']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_kompetisi']); ?></td>
                                <td><?php echo ($data['tanggal_mulai'] instanceof DateTime ? $data['tanggal_mulai']->format('d-m-Y') : 'Tidak Tersedia'); ?></td>
                                <td>
                                    <a href='pratinjau.php?id=<?php echo urlencode($data['id']); ?>' class='btn-preview'>Pratinjau</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Data Ditolak -->
            <div class="rejected-data">
                <h2>Data Prestasi Ditolak</h2>
                <table>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Nama Kompetisi</th>
                        <th>Tanggal Mulai</th>
                    </tr>
                    <?php if (empty($rejectedData)): ?>
                        <tr><td colspan="4">Tidak ada data prestasi ditolak.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rejectedData as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['program_studi']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_kompetisi']); ?></td>
                                <td><?php echo ($row['tanggal_mulai'] instanceof DateTime ? $row['tanggal_mulai']->format('d-m-Y') : 'Tidak Tersedia'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
