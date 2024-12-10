<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk memeriksa pengguna
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = sqlsrv_prepare($conn, $query, array($username));

    if (sqlsrv_execute($stmt)) {
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($result) {
            // Verifikasi password
            if ($password === $result['password']) {
                // Set session
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['user_type'] = $result['user_type'];

                // Ambil informasi mahasiswa jika user_type adalah 'mahasiswa'
                if ($result['user_type'] === 'mahasiswa') {
                    $mahasiswaQuery = "SELECT * FROM mahasiswa WHERE user_id = ?";
                    $mahasiswaStmt = sqlsrv_prepare($conn, $mahasiswaQuery, array($result['id']));

                    if (sqlsrv_execute($mahasiswaStmt)) {
                        $mahasiswaResult = sqlsrv_fetch_array($mahasiswaStmt, SQLSRV_FETCH_ASSOC);

                        if ($mahasiswaResult) {
                            $_SESSION['nim'] = $mahasiswaResult['nim'];
                            $_SESSION['prodi'] = $mahasiswaResult['prodi'];
                        }
                    }
                }

                // Redirect berdasarkan user_type
                switch ($result['user_type']) {
                    case 'admin':
                        header("Location: dashboardAdmin.php");
                        break;
                    case 'superadmin':
                        header("Location: dashboardSuperadmin.php");
                        break;
                    case 'mahasiswa':
                        header("Location: dashboardMahasiswa.php"); // Pastikan ini adalah file dashboard mahasiswa
                        break;
                    case 'dosen':
                        header("Location: berandaDosen.html");
                        break;
                    default:
                        echo "User type tidak dikenali.";
                        exit();
                }
                exit();
            } else {
                echo "Username atau password salah.";
            }
        } else {
            echo "Username atau password salah.";
        }
    } else {
        echo "Terjadi kesalahan saat melakukan query.";
    }
}
?>
