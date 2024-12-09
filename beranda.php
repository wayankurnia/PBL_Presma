<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Prestasi Terverifikasi</title>
    <link rel="stylesheet" href="CSS/StyleDashboard.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="gambar/polinema.png" alt="Logo 1" class="logo">
            <img src="gambar/jti.png" alt="Logo 2" class="logo">
            <div class="header2-container">
                <h1>DAFTAR PRESTASI TERVERIFIKASI 🎓</h1>
                <div class="login-button" style="float: right;">
                    <button onclick="window.location.href='login.html';">LOGIN</button>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="prestasi">
            <h2>Daftar Prestasi Mahasiswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Kompetisi</th>
                        <th>Tahun</th>
                        <th>Peringkat</th>
                        <th>Tingkat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Koneksi ke database
                    include 'config.php';

                    // Query untuk mengambil data prestasi yang sudah diverifikasi
                    $query = "SELECT * FROM prestasi WHERE status = 'verified'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        $no = 1; // Untuk nomor urut
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['nama']) . "</td>
                                    <td>" . htmlspecialchars($row['nama_kompetisi']) . "</td>
                                    <td>" . htmlspecialchars($row['tahun']) . "</td>
                                    <td>" . htmlspecialchars($row['juara']) . "</td>
                                    <td>" . htmlspecialchars($row['tingkat_kompetisi']) . "</td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>Tidak ada data prestasi yang terverifikasi.</td></tr>";
                    }

                    // Tutup koneksi
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

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