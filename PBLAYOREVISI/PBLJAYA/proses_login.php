<?php
require_once 'config.php';

class Authentication {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function login($username, $password) {
        // Query untuk memeriksa pengguna
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = sqlsrv_prepare($this->conn, $query, array($username));

        if (sqlsrv_execute($stmt)) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($result) {
                // Verifikasi password
                if ($password === $result['password']) {
                    // Atur sesi pengguna
                    $this->setUserSession($result);
                    return $result['user_type'];
                }
            }
        }
        return false; // Login gagal
    }

    private function setUserSession($user) {
        session_start();  
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['user_type'];

        // Ambil informasi mahasiswa jika user_type adalah 'mahasiswa'
        if ($user['user_type'] === 'mahasiswa') {
            $this->setMahasiswaSession($user['id']);
        }
    }

    private function setMahasiswaSession($userId) {
        $query = "SELECT * FROM mahasiswa WHERE user_id = ?";
        $stmt = sqlsrv_prepare($this->conn, $query, array($userId));

        if (sqlsrv_execute($stmt)) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($result) {
                $_SESSION['nim'] = $result['nim'];
                $_SESSION['prodi'] = $result['prodi'];
            }
        }
    }
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Inisialisasi database dan autentikasi
    $db = new Database();
    $auth = new Authentication($db);

    // Coba login
    $userType = $auth->login($username, $password);
    if ($userType) {
        // Redirect berdasarkan user_type
        switch ($userType) {
            case 'admin':
                header("Location: dashboardAdmin.php");
                break;
            case 'superadmin':
                header("Location: dashboardSuperadmin.php");
                break;
            case 'mahasiswa':
                header("Location: dashboardMahasiswa.php");
                break;
            case 'dosen':
                header("Location: berandaDosen.php");
                break;
            default:
                echo "User type tidak dikenali.";
                exit();
        }
    } else {
        // Login gagal
        header("Location: login.php?error=invalid");
        exit();
    }
}
?>
