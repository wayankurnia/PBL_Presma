<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk memeriksa pengguna
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if ($password === $user['password']) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];

            // Ambil informasi mahasiswa jika user_type adalah 'mahasiswa'
            if ($user['user_type'] === 'mahasiswa') {
                $mahasiswaQuery = "SELECT * FROM mahasiswa WHERE user_id = ?";
                $mahasiswaStmt = $conn->prepare($mahasiswaQuery);
                $mahasiswaStmt->bind_param("i", $user['id']);
                $mahasiswaStmt->execute();
                $mahasiswaResult = $mahasiswaStmt->get_result();

                if ($mahasiswaResult->num_rows > 0) {
                    $mahasiswa = $mahasiswaResult->fetch_assoc();
                    $_SESSION['nim'] = $mahasiswa['nim'];
                    $_SESSION['prodi'] = $mahasiswa['prodi'];
                }
            }

            // Redirect berdasarkan user_type
            switch ($user['user_type']) {
                case 'admin':
                    header("Location: berandaAdmin.php");
                    break;
                case 'superadmin':
                    header("Location: dashboardSuperadmin.php");
                    break;
                case 'dosen':
                        header("Location: berandaDosen.html");
                        break;
                case 'mahasiswa':
                    header("Location: dashboardMahasiswa.php"); // Pastikan ini adalah file dashboard mahasiswa
                    break;
                default:
                    echo "User  type tidak dikenali.";
                    exit();
            }
            exit();
        } else {
            echo "Username atau password salah.";
        }
    } else {
        echo "Username atau password salah.";
    }
}
?>