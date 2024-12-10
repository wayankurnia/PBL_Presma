<?php
require_once 'config.php';
session_start();


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

class Prestasi {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    // Ambil data prestasi berdasarkan ID
    public function getPrestasiById($id) {
        $query = "SELECT * FROM prestasi WHERE id = ?";
        $params = array($id);
        $stmt = sqlsrv_query($this->conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
}

// Inisialisasi objek database dan prestasi
$db = new Database();
$prestasi = new Prestasi($db);

// Cek apakah ID tersedia dalam URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data prestasi berdasarkan ID
    $data = $prestasi->getPrestasiById($id);

    if ($data === false) {
        echo "Data tidak ditemukan.";
        exit();
    }
} else {
    echo "ID tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Data Prestasi</title>
    <link rel="stylesheet" href="CSS/pratinjau.css">
</head>
<body>
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3>Vit Zuraida, S.Kom., M.Kom.</h3>
        <p>SuperAdmin</p>
        <button class="btn btn-primary" onclick="window.location.href='dashboardSuperadmin.php';">Dashboard</button>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.html" class="menu-link">Keluar</a>
    </div>

    <div class="container">
        <main class="content">
            <h1>Pratinjau Data Prestasi</h1>

            <!-- Data Mahasiswa -->
            <fieldset>
                <legend>Data Mahasiswa</legend>
                <div class="data-row">
                    <span class="data-label">Nama:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['nama']); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Program Studi:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['program_studi']); ?></span>
                </div>
            </fieldset>

            <!-- Data Pembimbing -->
            <fieldset>
                <legend>Data Dosen Pembimbing</legend>
                <div class="data-row">
                    <span class="data-label">Nama:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['dosen']); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Peran:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['peran_pembimbing']); ?></span>
                </div>
            </fieldset>

            <!-- Data Prestasi -->
            <fieldset>
                <legend>Data Prestasi</legend>
                <div class="data-row">
                    <span class="data-label">Nama Kompetisi:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['nama_kompetisi']); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Jenis Kompetisi:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['jenis_kompetisi']); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Tingkat Kompetisi:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['tingkat_kompetisi']); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Tanggal Mulai:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['tanggal_mulai']->format('d-m-Y')); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Tanggal Selesai:</span>
                    <span class="data-value"><?php echo htmlspecialchars($data['tanggal_selesai']->format('d-m-Y')); ?></span>
                </div>
            </fieldset>

            <!-- Tombol Verifikasi dan Tolak -->
            <div class="action-buttons">
                <form action="verifikasi.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <button type="submit" class="btn verify-button">Verifikasi</button>
                </form>
                <form action="tolak.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <button type="submit" class="btn reject-button">Tolak</button>
                </form>
                <button type="button" class="btn back-button" onclick="window.location.href='dashboardSuperadmin.php';">Kembali</button>
            </div>
        </main>
    </div>
</body>
</html>
