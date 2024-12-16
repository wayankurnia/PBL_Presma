<?php
// Class untuk koneksi database SQL Server
class Database {
    private $host = "SYAQIRA\SYAQIRASQLSERVER"; // Host SQL Server 
    private $database = "prestasi_mahasiswa"; // Nama database 
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

// Class untuk mengelola data prestasi mahasiswa
class Prestasi {
    private $db;
    private $conn;

    public function __construct($db) {
        $this->db = $db;
        $this->conn = $this->db->getConnection();
    }

    // Fungsi untuk mengambil data prestasi yang sudah diverifikasi
    public function getPrestasiVerified() {
        $query = "SELECT * FROM prestasi WHERE status = 'verified'";
        $stmt = sqlsrv_query($this->conn, $query);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
        }

        $result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }
}

// Menginstansiasi objek Database dan Prestasi
$db = new Database();
$prestasi = new Prestasi($db);
$prestasiList = $prestasi->getPrestasiVerified();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Prestasi Terverifikasi</title>
    <link rel="stylesheet" href="CSS/beranda.css">  <!-- Menghubungkan CSS -->
    <style>
        .gold { background-color: #FFD700; color: black; }
        .silver { background-color: #C0C0C0; color: black; }
        .bronze { background-color: #CD7F32; color: black; }
        .normal { background-color: #f9f9f9; color: black; }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo-container">
                <img src="gambar/polinema.png" alt="Logo Polinema" class="logo">
                <img src="gambar/jti.png" alt="Logo JTI" class="logo">
            </div>
            <div class="header2-container">
                <h1>PRESTASI MAHASISWA 🎓</h1>
                <div class="login-button">
                    <button onclick="window.location.href='login.php';">LOGIN</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Daftar Prestasi Mahasiswa -->
        <section class="prestasi">
            <h2>Daftar Prestasi Mahasiswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kelas</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($prestasiList) {
                        $no = 1; // Untuk nomor urut
                        foreach ($prestasiList as $row) {
                            // Ambil nilai Score atau null jika tidak ada
                            $score = $row['Score'] ?? null;
                            $class = "normal";

                            // Tentukan kelas warna berdasarkan nilai Score
                            if ($score !== null) {
                                if ($score >= 90) {
                                    $class = "gold";
                                } elseif ($score >= 75) {
                                    $class = "silver";
                                } elseif ($score >= 60) {
                                    $class = "bronze";
                                }
                            }

                            echo "<tr class='$class'>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['nama'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['kelas'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($score !== null ? $score : 'N/A') . "</td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada data prestasi yang terverifikasi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Info Lomba -->
        <section class="info-lomba">
            <h2>SEPUTAR LOMBA</h2>
            <div class="lomba-container">
                <div class="lomba-item">Lomba 1</div>
                <div class="lomba-item">Lomba 2</div>
                <div class="lomba-item">Lomba 3</div>
                <div class="lomba-item">Lomba 4</div>
                <div class="lomba-item">Lomba 5</div>
                <div class="lomba-item">Lomba 6</div>
            </div>
        </section>
    </main>
</body>
</html>
