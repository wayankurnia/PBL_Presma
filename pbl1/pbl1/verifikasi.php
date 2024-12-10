<?php
include 'config.php';
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

// Cek apakah ID tersedia dalam POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Pastikan ID adalah angka untuk keamanan

    // Update status menjadi 'verified'
    $query = "UPDATE prestasi SET status = 'verified' WHERE id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $query, $params);

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