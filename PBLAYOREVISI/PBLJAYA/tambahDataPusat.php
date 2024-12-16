<?php
require_once 'config.php'; // Koneksi ke database
session_start();

class DataPusat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    // Fungsi untuk menyimpan data dosen
    public function saveDosen($nama_dosen, $nidn) {
        $sql_dosen = "INSERT INTO dosen (nidn, nama) VALUES (?, ?)";
        $params_dosen = array($nidn, $nama_dosen);
        $stmt_dosen = sqlsrv_query($this->conn, $sql_dosen, $params_dosen);

        return $stmt_dosen !== false;
    }

    // Fungsi untuk menyimpan data mahasiswa
    public function saveMahasiswa($nama_mahasiswa, $nim, $prodi, $user_id) {
        $sql_mahasiswa = "INSERT INTO mahasiswa (user_id, nim, nama, prodi) VALUES (?, ?, ?, ?)";
        $params_mahasiswa = array($user_id, $nim, $nama_mahasiswa, $prodi);
        $stmt_mahasiswa = sqlsrv_query($this->conn, $sql_mahasiswa, $params_mahasiswa);

        return $stmt_mahasiswa !== false;
    }

    // Fungsi untuk memeriksa apakah data berhasil disimpan
    public function saveData($postData) {
        $success = false;

        // Cek jika data dosen dipilih
        if (isset($postData['data_type']) && in_array('dosen', $postData['data_type'])) {
            $nama_dosen = $postData['nama_dosen'];
            $nidn = $postData['nidn'];
            if ($this->saveDosen($nama_dosen, $nidn)) {
                $success = true;
            }
        }

        // Cek jika data mahasiswa dipilih
        if (isset($postData['data_type']) && in_array('mahasiswa', $postData['data_type'])) {
            $nama_mahasiswa = $postData['nama_mahasiswa'];
            $nim = $postData['nim'];
            $prodi = $postData['program_studi'];
            $user_id = $_SESSION['user_id']; // Ambil user_id dari session
            if ($this->saveMahasiswa($nama_mahasiswa, $nim, $prodi, $user_id)) {
                $success = true;
            }
        }

        return $success;
    }
}

// Inisialisasi Database dan DataPusat
$db = new Database();
$dataPusat = new DataPusat($db);


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
    header("Location: proses_login.php");
    exit();
}

// Cek apakah ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($dataPusat->saveData($_POST)) {
        header("Location: dashboardSuperadmin.php?message=Data berhasil ditambahkan.");
        exit();
    } else {
        echo "Terjadi kesalahan saat menambahkan data.";
    }
}
?>
