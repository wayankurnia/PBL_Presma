<?php
include 'config.php'; // Koneksi ke database
include 'ambilprestasi.php'; // Fungsi untuk mengambil data prestasi
session_start();

// Fetch achievements
$result = fetchPrestasi($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Admin</title>
    <link rel="stylesheet" href="CSS/berandaAdmin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .sidebar {
            width: 250px;
            background-color: #3946a2;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid #ffffff;
        }

        .sidebar h3 {
            font-size: 1rem;
            margin: 5px 0;
            text-align: center;
        }

        .sidebar p {
            font-size: 0.9rem;
            margin: 0 0 20px 0;
            text-align: center;
        }

        .sidebar a {
            width: 100%;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            padding: 12px 0;
            margin: 10px 0;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2d3693;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .section-title {
            margin-top: 40px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #3946a2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3946a2;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SIPRESMA</h2>
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
        <h3>Dr. Eng. Rosa Andrie Asmara, S.T., M.T.</h3>
        <p>Admin</p>
        <a href="#" class="menu-link">Dashboard</a>
        <a href="gantipw.html" class="menu-link">Edit Password</a>
        <a href="login.html" class="menu-link">Keluar</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Beranda Admin</h1>
            <img src="gambar/jti.png" alt="Logo JTI">
        </div>

        <div class="section-title">DAFTAR PRESTASI MAHASISWA 🎓</div>
        <table id="resultTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Nama Kompetisi</th>
                    <th >Jenis Kompetisi</th>
                    <th>Tingkat Kompetisi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Juara</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['nama_kompetisi']}</td>
                                <td>{$row['jenis_kompetisi']}</td>
                                <td>{$row['tingkat_kompetisi']}</td>
                                <td>{$row['tanggal_mulai']}</td>
                                <td>{$row['tanggal_selesai']}</td>
                                <td>{$row['juara']}</td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data prestasi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>