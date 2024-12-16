<?php
session_start();

// Pastikan hanya pengguna yang sudah login yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kelas untuk mengelola koneksi dan operasi database
class Database {
    private $server;
    private $connection;
    private $database;

    public function __construct($server, $database) {
        $this->server = $server;
        $this->database = $database;
        $this->connect();
    }

    // Fungsi untuk membuka koneksi ke database
    private function connect() {
        $connectionInfo = array("Database" => $this->database, "UID" => "username", "PWD" => "password");
        $this->connection = sqlsrv_connect($this->server, $connectionInfo);
        if (!$this->connection) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Mengembalikan objek koneksi untuk digunakan oleh kelas lain
    public function getConnection() {
        return $this->connection;
    }

    // Fungsi untuk menutup koneksi
    public function closeConnection() {
        sqlsrv_close($this->connection);
    }
}

// Kelas untuk menangani proses penggantian password
class PasswordChange {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Fungsi untuk memverifikasi password lama
    public function verifyOldPassword($userId, $oldPassword) {
        $query = "SELECT password FROM users WHERE user_id = ?";
        $params = array($userId);
        $stmt = sqlsrv_prepare($this->db, $query, $params);

        if (!$stmt || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return password_verify($oldPassword, $user['password']);
    }

    // Fungsi untuk mengganti password
    public function changePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE user_id = ?";
        $params = array($hashedPassword, $userId);
        $stmt = sqlsrv_prepare($this->db, $query, $params);

        if (!$stmt || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        return true;
    }
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database("LAPTOP-NR8UTQ4Q", "prestasi_mahasiswa");
    $passwordChange = new PasswordChange($db->getConnection());

    $userId = $_SESSION['user_id']; // Mengambil ID pengguna dari session
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];

    // Memverifikasi password lama
    if (!$passwordChange->verifyOldPassword($userId, $oldPassword)) {
        $error = "Password lama tidak cocok!";
    } else {
        // Mengubah password jika valid
        if ($passwordChange->changePassword($userId, $newPassword)) {
            $success = "Password berhasil diubah!";
        }
    }

    $db->closeConnection();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link rel="stylesheet" href="CSS/gantipw.css">
</head>
<body>
    <div class="container">
        <img src="gambar/jti.png" class="logo" alt="Logo JTI">
        <h1>Ganti Password Anda</h1>

        <!-- Menampilkan pesan error atau sukses -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form id="password-form" method="POST" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="old-password">Password Lama</label>
                <input type="password" id="old-password" name="old-password" placeholder="Masukkan Password Lama" required>
            </div>
            <div class="form-group">
                <label for="new-password">Password Baru</label>
                <input type="password" id="new-password" name="new-password" placeholder="Masukkan Password Baru" required>
            </div>
            <div class="form-group">
                <label for="repeat-password">Ulangi Password Anda</label>
                <input type="password" id="repeat-password" name="repeat-password" placeholder="Masukkan Ulang Password" required>
                <p id="error-message" class="error" style="display: none;">Password tidak cocok!</p>
            </div>

            <div class="buttons">
                <button type="submit">SIMPAN</button>
                <button type="button" onclick="history.back()">KEMBALI</button>
            </div>
        </form>
    </div>

    <script>
        // Validasi password di sisi klien
        function validatePassword() {
            const newPassword = document.getElementById("new-password").value;
            const repeatPassword = document.getElementById("repeat-password").value;
            const errorMessage = document.getElementById("error-message");

            if (newPassword !== repeatPassword) {
                errorMessage.style.display = "block";
                return false; // Menghentikan pengiriman form
            } else {
                errorMessage.style.display = "none";
                return true; // Mengizinkan pengiriman form
            }
        }
    </script>
</body>
</html>