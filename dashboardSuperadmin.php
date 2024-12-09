<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin</title>
    <link rel="stylesheet" href="CSS/berandaSuperadmin.css">
</head>
<body>
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3>Vit Zuraida, S.Kom., M.Kom.</h3>
        <p>Superadmin</p>
        <a href="dashboardSuperadmin.php" class="menu-link">Dashboard</a>
        <a href="tambahDataPusat.html" class="menu-link">Tambah Data</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.html" class="menu-link">Keluar</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Data Prestasi yang Perlu Diverifikasi</h1>
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo">
        </div>

        <div class="main">
            <div class="status-background">
                <div class="status-boxes">
                    <?php
                    include 'config.php';
                    session_start();

                    // Pastikan hanya superadmin yang dapat mengakses halaman ini
                    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'superadmin') {
                        header("Location: proses_login.php");
                        exit();
                    }

                    // Ambil jumlah data terverifikasi, pending, dan ditolak
                    $verifiedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'verified'";
                    $pendingQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'proses'";
                    $rejectedQuery = "SELECT COUNT(*) as count FROM prestasi WHERE status = 'rejected'";

                    $verifiedResult = $conn->query($verifiedQuery)->fetch_assoc();
                    $pendingResult = $conn->query($pendingQuery)->fetch_assoc();
                    $rejectedResult = $conn->query($rejectedQuery)->fetch_assoc();
                    ?>
                    <div class="status-box">
                        <h2><?php echo $verifiedResult['count']; ?></h2>
                        <h3>Terverifikasi</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $pendingResult['count']; ?></h2>
                        <h3>Pending</h3>
                    </div>
                    <div class="status-box">
                        <h2><?php echo $rejectedResult['count']; ?></h2>
                        <h3>Ditolak</h3>
                    </div>
                </div>
            </div>

            <div class="pending-data">
    <h2>Data Prestasi Pending</h2>
    <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>Nama Kompetisi</th>
            <th>Tanggal Mulai</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Ambil data prestasi pending
        $pendingDataQuery = "SELECT * FROM prestasi WHERE status = 'proses'"; 
        $pendingDataResult = $conn->query($pendingDataQuery);

        if ($pendingDataResult === false) {
            echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>"; // Tampilkan error jika query gagal
        } elseif ($pendingDataResult->num_rows > 0) {
            while ($data = $pendingDataResult->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($data['nama']) . "</td>
                        <td>" . htmlspecialchars($data['program_studi']) . "</td>
                        <td>" . htmlspecialchars($data['nama_kompetisi']) . "</td>
                        <td>" . htmlspecialchars($data['tanggal_mulai']) . "</td>
                        <td>
                            <a href='pratinjau.php?id=" . urlencode($data['id']) . "' class='btn-preview'>Pratinjau</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data prestasi pending.</td></tr>";
        }
        ?>
    </table>
</div>

        </div>
    </div>
</body>
</html>