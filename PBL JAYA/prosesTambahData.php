<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

require_once 'config.php';

class Prestasi {
    private $conn;
    private $user_id;

    public function __construct($db) {
        $this->conn = $db->getConnection();
        $this->user_id = $_SESSION['user_id'];  // Ambil user_id dari session
    }

    public function tambahPrestasi($data, $files) {
        // Ambil data form
        $nama = $data['nama'];
        $program_studi = $data['program_studi'];
        $dosen = $data['dosen'];
        $peran_pembimbing = $data['peran_pembimbing'];
        $peran = $data['peran'];
        $jenis_kompetisi = $data['jenis_kompetisi'];
        $tingkat_kompetisi = $data['tingkat_kompetisi'];
        $nama_kompetisi = $data['nama_kompetisi'];
        $juara = $data['juara'];
        $tanggal_mulai = $data['tanggal_mulai'];
        $tanggal_selesai = $data['tanggal_selesai'];

        // Proses upload file
        $foto_kegiatan = $files['foto_kegiatan']['name'];
        $file_sertifikat = $files['file_sertifikat']['name'];
        $file_poster = $files['file_poster']['name'];

        $target_dir = "uploads/";

        // Pastikan direktori uploads ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Pindahkan file yang diupload
        move_uploaded_file($files['foto_kegiatan']['tmp_name'], $target_dir . $foto_kegiatan);
        move_uploaded_file($files['file_sertifikat']['tmp_name'], $target_dir . $file_sertifikat);
        move_uploaded_file($files['file_poster']['tmp_name'], $target_dir . $file_poster);

        // Query untuk menyimpan data ke tabel prestasi dengan status 'proses'
        $sql = "INSERT INTO prestasi (user_id, nama, program_studi, dosen, peran_pembimbing, peran, jenis_kompetisi, tingkat_kompetisi, nama_kompetisi, juara, tanggal_mulai, tanggal_selesai, foto_kegiatan, file_sertifikat, file_poster, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'proses')";

        $stmt = sqlsrv_prepare($this->conn, $sql, array(
            $this->user_id, $nama, $program_studi, $dosen, $peran_pembimbing, $peran, $jenis_kompetisi, $tingkat_kompetisi, $nama_kompetisi, $juara, $tanggal_mulai, $tanggal_selesai, $foto_kegiatan, $file_sertifikat, $file_poster
        ));

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika prepare gagal
        }

        if (sqlsrv_execute($stmt)) {
            echo "<script>alert('Data berhasil dikirim!'); window.location.href='dashboardMahasiswa.php';</script>";
        } else {
            echo "Error: " . print_r(sqlsrv_errors(), true);
        }

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($this->conn);
    }
}

// Inisialisasi Database dan Prestasi
$db = new Database();
$prestasi = new Prestasi($db);

// Panggil method tambahPrestasi dengan data yang diambil dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestasi->tambahPrestasi($_POST, $_FILES);
}
?>
