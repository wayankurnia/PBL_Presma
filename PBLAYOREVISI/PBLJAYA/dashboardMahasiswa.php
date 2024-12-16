<?php
session_start();
require_once 'config.php'; 

// Kelas untuk menangani logika mahasiswa dan prestasi
class Mahasiswa {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    // Ambil data mahasiswa berdasarkan user_id
    public function getData() {
        $query = "SELECT m.nim, m.nama, m.prodi FROM mahasiswa m WHERE m.user_id = ?";
        $params = array($this->user_id);
        $stmt = sqlsrv_prepare($this->db->getConnection(), $query, $params);

        if ($stmt === false || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    // Ambil prestasi berdasarkan status
    public function getPrestasi($status) {
        $query = "SELECT * FROM prestasi WHERE user_id = ? AND status = ?";
        $params = array($this->user_id, $status);
        $stmt = sqlsrv_prepare($this->db->getConnection(), $query, $params);

        if (!$stmt || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    // Hitung poin dan jumlah prestasi terverifikasi
    public function calculatePrestasiPoints() {
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

        $prestasiVerified = $this->getPrestasi('verified');
        $totalPoin = 0;
        $jumlahPrestasi = 0;

        foreach ($prestasiVerified as $prestasi) {
            $tingkat = strtolower($prestasi['tingkat_kompetisi']);
            $juara = strtolower($prestasi['juara']);

            $poinTingkatValue = $poinTingkat[$tingkat] ?? 0;
            $poinJuaraValue = $poinJuara[$juara] ?? 0;

            $totalPoin += $poinTingkatValue + $poinJuaraValue;
            $jumlahPrestasi++;
        }

        return ['totalPoin' => $totalPoin, 'jumlahPrestasi' => $jumlahPrestasi];
    }

    // Hitung peringkat berdasarkan total poin
    public function getPeringkat() {
        $query = "SELECT user_id, SUM(
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

        $stmt = sqlsrv_query($this->db->getConnection(), $query);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $peringkat = 1;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($row['user_id'] == $this->user_id) {
                break;
            }
            $peringkat++;
        }

        return $peringkat;
    }
}

// Memeriksa jika pengguna sudah login dan memiliki akses
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Inisialisasi Database dan Mahasiswa
$db = new Database(); 
$mahasiswa = new Mahasiswa($db, $_SESSION['user_id']);

// Ambil data mahasiswa
$userData = $mahasiswa->getData();
$_SESSION['nim'] = $userData['nim'];
$_SESSION['nama'] = $userData['nama'];
$_SESSION['prodi'] = $userData['prodi'];

// Hitung poin dan prestasi terverifikasi
$poinData = $mahasiswa->calculatePrestasiPoints();
$totalPoin = $poinData['totalPoin'];
$jumlahPrestasi = $poinData['jumlahPrestasi'];

// Hitung peringkat
$peringkat = $mahasiswa->getPeringkat();

// Ambil data prestasi
$prestasiVerified = $mahasiswa->getPrestasi('verified');
$prestasiPending = $mahasiswa->getPrestasi('proses');
$prestasiRejected = $mahasiswa->getPrestasi('rejected');
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
        <a href="TambahData.html" class="menu-link">Tambah Data Prestasi</a>
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
                        <?php foreach ($prestasiPending as $prestasi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['juara']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h2>Prestasi Ditolak</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kompetisi</th>
                            <th>Tingkat</th>
                            <th>Juara</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestasiRejected as $prestasi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['juara']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

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
                        <?php foreach ($prestasiVerified as $prestasi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestasi['nama_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['tingkat_kompetisi']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['juara']); ?></td>
                                <td><?php echo htmlspecialchars($prestasi['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
