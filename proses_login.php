<?php
session_start();

// Ambil data dari form
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$user_type = $_POST['user_type'];

// Data user dummy
$users = [
    'admin' => ['username' => 'admin', 'password' => 'admin123'],
    'mahasiswa' => ['username' => 'mahasiswa', 'password' => 'mahasiswa123'],
    'dosen' => ['username' => 'dosen', 'password' => 'dosen123'],
    'superadmin' => ['username' => 'superadmin', 'password' => 'superadmin123'],
];

// Validasi login
if (isset($users[$user_type]) && 
    $users[$user_type]['username'] === $username && 
    $users[$user_type]['password'] === $password) {

    // Set session
    $_SESSION['username'] = $username;
    $_SESSION['user_type'] = $user_type;

    // Redirect sesuai jenis pengguna
    switch ($user_type) {
        case 'admin':
            header('Location: berandaAdmin.html');
            exit;
        case 'mahasiswa':
            header('Location: dashboardMahasiswa.html');
            exit;
        case 'dosen':
            header('Location: berandaDosen.html');
            exit;
        case 'superadmin':
            header('Location: berandaSuperadmin.html');
            exit;
        default:
            echo "Jenis pengguna tidak valid.";
            exit;
    }
} else {
    // Pesan error jika login gagal
    echo "Username atau password salah.";
}
?>
