<?php
session_start();

// Check user type to determine the correct dashboard link
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'mahasiswa'; // Default to mahasiswa if not set
$dashboard_link = '';

if ($user_type === 'mahasiswa') {
    $dashboard_link = 'dashboardMahasiswa.php';
} elseif ($user_type === 'dosen') {
    $dashboard_link = 'berandaDosen.php';
} elseif ($user_type === 'superadmin') {
    $dashboard_link = 'dashboardSuperadmin.php';
} else {
    $dashboard_link = 'login.html'; // Redirect to login if user type is unknown
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tersimpan</title>
    <link rel="stylesheet" href="CSS/success.css"> <!-- Link to the new success CSS file -->
</head>
<body>
    <div class="container">
        <h1>Data Prestasi Berhasil Disimpan!</h1>
        <p>Data prestasi Anda telah berhasil disimpan ke dalam sistem.</p>
        <a href="<?php echo $dashboard_link; ?>" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>