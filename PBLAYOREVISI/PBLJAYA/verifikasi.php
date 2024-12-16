<?php
require_once 'config.php';
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

class Prestasi {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    // Verifikasi prestasi berdasarkan ID
    public function verifikasiPrestasi($id) {
        $query = "UPDATE prestasi SET status = 'verified' WHERE id = ?";
        $params = array($id);
        $stmt = sqlsrv_query($this->conn, $query, $params);

        return $stmt;
    }
}

// Inisialisasi objek database dan prestasi
$db = new Database();
$prestasi = new Prestasi($db);

// Cek apakah ID tersedia dalam POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Pastikan ID adalah angka untuk keamanan

    // Verifikasi prestasi
    $stmt = $prestasi->verifikasiPrestasi($id);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
    } else {
        // Redirect ke dashboard superadmin setelah berhasil
        header("Location: dashboardSuperadmin.php?message=Data berhasil diverifikasi.");
        exit();
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
