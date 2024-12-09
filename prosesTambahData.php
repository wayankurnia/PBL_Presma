<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memulai session
session_start();

// Koneksi ke database
$host = 'localhost'; 
$db = 'prestasi_mahasiswa';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

// Ambil data dari form
$nama = $_POST['nama'];
$program_studi = $_POST['program_studi'];
$dosen = $_POST['dosen'];
$peran_pembimbing = $_POST['peran_pembimbing'];
$peran = $_POST['peran'];
$jenis_kompetisi = $_POST['jenis_kompetisi'];
$tingkat_kompetisi = $_POST['tingkat_kompetisi'];
$nama_kompetisi = $_POST['nama_kompetisi'];
$juara = $_POST['juara'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];

// Ambil user_id dari session
$user_id = $_SESSION['user_id']; // atau $_SESSION['nim'] jika Anda menggunakan NIM

// Proses upload file
$foto_kegiatan = $_FILES['foto_kegiatan']['name'];
$file_sertifikat = $_FILES['file_sertifikat']['name'];
$file_poster = $_FILES['file_poster']['name'];

$target_dir = "uploads/";

// Pastikan direktori uploads ada
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Pindahkan file yang diupload
move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], $target_dir . $foto_kegiatan);
move_uploaded_file($_FILES['file_sertifikat']['tmp_name'], $target_dir . $file_sertifikat);
move_uploaded_file($_FILES['file_poster']['tmp_name'], $target_dir . $file_poster);

// Query untuk menyimpan data ke tabel prestasi dengan status 'proses'
$sql = "INSERT INTO prestasi (user_id, nama, program_studi, dosen, peran_pembimbing, peran, jenis_kompetisi, tingkat_kompetisi, nama_kompetisi, juara, tanggal_mulai, tanggal_selesai, foto_kegiatan, file_sertifikat, file_poster, status) 
VALUES ('$user_id', '$nama', '$program_studi', '$dosen', '$peran_pembimbing', '$peran', '$jenis_kompetisi', '$tingkat_kompetisi', '$nama_kompetisi', '$juara', '$tanggal_mulai', '$tanggal_selesai', '$foto_kegiatan', '$file_sertifikat', '$file_poster', 'proses')";

// Debugging: Tampilkan query
echo "Query: " . $sql . "<br>"; // Hapus atau komentar baris ini setelah debugging

if ($conn->query($sql) === TRUE) {
    // Redirect to success page
    header("Location: success.php");
    exit(); // Make sure to exit after the redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>