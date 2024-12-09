<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mahasiswa') {
    header("Location: login.html");
    exit();
}

// Koneksi ke database
include 'config.php';

// Ambil data prestasi untuk user yang sedang login
$query = "SELECT * FROM prestasi WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Inisialisasi variabel
$totalPrestasi = 0;
$totalPoin = 0;

// Hitung total prestasi dan poin
while ($row = $result->fetch_assoc()) {
    $totalPrestasi++;

    // Hitung poin berdasarkan tingkat kompetisi
    switch ($row['tingkat_kompetisi']) {
        case 'internasional':
            $totalPoin += 5;
            break;
        case 'nasional':
            $totalPoin += 4;
            break;
        case 'provinsi':
            $totalPoin += 3;
            break;
        case 'kabupaten':
        case 'kota':
            $totalPoin += 2;
            break;
        case 'internal':
            $totalPoin += 1;
            break;
    }

    // Hitung poin berdasarkan juara
    switch ($row['juara']) {
        case 'Juara 1':
            $totalPoin += 6;
            break;
        case 'Juara 2':
            $totalPoin += 5;
            break;
        case 'Juara 3':
            $totalPoin += 4;
            break;
        case 'Harapan 1':
            $totalPoin += 3;
            break;
        case 'Harapan 2':
            $totalPoin += 2;
            break;
        case 'Harapan 3':
            $totalPoin += 1;
            break;
    }
}

// Set nilai ke dalam session
$_SESSION['jumlahPrestasi'] = $totalPrestasi;
$_SESSION['totalPoin'] = $totalPoin;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="CSS/dashboardMahasiswa.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3><?php echo $_SESSION['username']; ?></h3>
        <p>NIM: <?php echo $_SESSION['nim']; ?></p>
        <p>Program Studi: <?php echo $_SESSION['prodi']; ?></p>
        <a href="#" class="menu-link">Beranda</a>
        <a href="TambahData.html" class="menu-link">Tambah Data</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.html" class="menu-link">Keluar</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Beranda</h1>
            <img src="jti.png" alt="Logo JTI">
        </div>
        <div class="main">
            <!-- Info Box -->
            <div class="info-box">
                <div class="cards-container">
                    <div class="card">
                        <i class="fas fa-trophy"></i>
                        <h4 id="peringkat">1</h4> <!-- Placeholder untuk peringkat -->
                        <p>Peringkat</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-graduation-cap"></i>
                        <h4 id="jumlahPrestasi"><?php echo $_SESSION['jumlahPrestasi']; ?></h4>
                        <p>Jumlah Prestasi</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-shield-alt"></i>
                        <h4 id="poin"><?php echo $_SESSION['totalPoin']; ?></h4>
                        <p>Poin</p>
                    </div>
                </div>
            </div>

            <!-- Riwayat Prestasi -->
            <div class="info-box">
                <h2>Riwayat Prestasi Mahasiswa</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Kompetisi</th>
                            <th>Jenis Kompetisi</th>
                            <th>Tanggal Mulai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil ulang data prestasi untuk riwayat
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['nama_kompetisi']; ?></td>
                                    <td><?php echo $row['jenis_kompetisi']; ?></td>
                                    <td><?php echo $row['tanggal_mulai']; ?></td>
                                    <td><label class="badge badge-success"><?php echo $row['status']; ?></label></td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="4">Tidak ada data yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
