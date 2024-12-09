<?php
include 'config.php'; // Koneksi ke database
session_start();

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

// Cek apakah ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = false; // Variabel untuk menandakan apakah data berhasil disimpan

    // Cek jika data dosen dipilih
    if (isset($_POST['data_type']) && in_array('dosen', $_POST['data_type'])) {
        $nama_dosen = $_POST['nama_dosen'];
        $nidn = $_POST['nidn'];

        // Query untuk menyimpan data dosen
        $sql_dosen = "INSERT INTO dosen (nidn, nama) VALUES (?, ?)";
        $stmt_dosen = $conn->prepare($sql_dosen);
        $stmt_dosen->bind_param("ss", $nidn, $nama_dosen);
        if ($stmt_dosen->execute()) {
            $success = true; // Tandai bahwa data dosen berhasil disimpan
        }
    }

    // Cek jika data mahasiswa dipilih
    if (isset($_POST['data_type']) && in_array('mahasiswa', $_POST['data_type'])) {
        $nama_mahasiswa = $_POST['nama_mahasiswa'];
        $nim = $_POST['nim'];
        $prodi = $_POST['program_studi'];
        $user_id = $_SESSION['user_id']; // Ambil user_id dari session

        // Query untuk menyimpan data mahasiswa
        $sql_mahasiswa = "INSERT INTO mahasiswa (user_id, nama, nim, prodi) VALUES (?, ?, ?, ?)";
        $stmt_mahasiswa = $conn->prepare($sql_mahasiswa);
        $stmt_mahasiswa->bind_param("isss", $user_id, $nama_mahasiswa, $nim, $prodi);
        if ($stmt_mahasiswa->execute()) {
            $success = true; // Tandai bahwa data mahasiswa berhasil disimpan
        }
    }

    // Redirect ke dashboard setelah berhasil
    if ($success) {
        header("Location: dashboardSuperadmin.php?message=Data berhasil ditambahkan.");
        exit();
    } else {
        echo "Terjadi kesalahan saat menambahkan data.";
    }
}
?>