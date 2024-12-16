<?php
require_once 'config.php'; // Include the database connection

// Pastikan ID berita dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus berita berdasarkan ID
    $sql = "DELETE FROM berita WHERE id = ?";
    $params = array($id);

    // Eksekusi query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error: " . sqlsrv_errors());
    } else {
        // Redirect kembali ke halaman dashboard
        header("Location: dashboardSuperadmin.php");
        exit();
    }

    sqlsrv_free_stmt($stmt);
    $database->close(); // Menutup koneksi database
} else {
    echo "ID berita tidak ditemukan.";
}
?>
