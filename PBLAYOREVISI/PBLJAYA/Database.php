<?php
// Database.php

// Class untuk koneksi database SQL Server
class Database {
    private $host = "SYAQIRA\SYAQIRASQLSERVER"; // Host SQL Server 
    private $database = "prestasi_mahasiswa"; // Nama database Anda
    private $conn;

    public function __construct() {
        $this->connect();
    }

    // Fungsi untuk menghubungkan ke database
    private function connect() {
        $connInfo = array("Database" => $this->database);
        $this->conn = sqlsrv_connect($this->host, $connInfo);

        // Cek apakah koneksi berhasil
        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika koneksi gagal
        }
    }

    // Fungsi untuk mendapatkan koneksi
    public function getConnection() {
        return $this->conn;
    }

    // Fungsi untuk menutup koneksi
    public function close() {
        sqlsrv_close($this->conn);
    }
}
?>

