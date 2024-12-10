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

    // Menolak prestasi berdasarkan ID
    public function tolakPrestasi($id) {
        $query = "UPDATE prestasi SET status = 'rejected' WHERE id = ?";
        // Menyiapkan query dan bind parameter
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id); // Bind parameter ID (integer)

        // Eksekusi query dan cek apakah berhasil
        return $stmt->execute();
    }
}

// Inisialisasi objek database dan prestasi
$db = new Database();
$prestasi = new Prestasi($db);

// Cek apakah ID tersedia dalam POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Pastikan ID adalah angka untuk keamanan

    // Tolak prestasi berdasarkan ID
    $isRejected = $prestasi->tolakPrestasi($id);

    if ($isRejected) {
        // Redirect ke dashboard superadmin setelah berhasil
        header("Location: dashboardSuperadmin.php?message=Data berhasil ditolak.");
        exit();
    } else {
        echo "Terjadi kesalahan saat menolak data.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
