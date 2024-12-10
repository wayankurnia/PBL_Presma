<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Prestasi Terverifikasi</title>
    <link rel="stylesheet" href="CSS/beranda.css">  <!-- Menghubungkan CSS -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo-container">
                <img src="gambar/polinema.png" alt="Logo Polinema" class="logo">
                <img src="gambar/jti.png" alt="Logo JTI" class="logo">
            </div>
            <div class="header2-container">
                <h1>PRESTASI MAHASISWA 🎓</h1>
                <div class="login-button">
                    <button onclick="window.location.href='login.php';">LOGIN</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Daftar Prestasi Mahasiswa -->
        <section class="prestasi">
            <h2>Daftar Prestasi Mahasiswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Kompetisi</th>
                        <th>Tahun</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Koneksi ke database
                    include 'config.php';

                    // Query untuk mengambil data prestasi yang sudah diverifikasi
                    $query = "SELECT * FROM prestasi WHERE status = 'verified'";
                    $result = sqlsrv_query($conn, $query);

                    if ($result === false) {
                        die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
                    }

                    if (sqlsrv_has_rows($result)) {
                        $no = 1; // Untuk nomor urut
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['nama'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['nama_kompetisi'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['tahun'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['juara'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['tingkat_kompetisi'] ?? 'N/A') . "</td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>Tidak ada data prestasi yang terverifikasi.</td></tr>";
                    }

                    // Tutup koneksi
                    sqlsrv_close($conn);
                    ?>
                </tbody>
            </table>
        </section>

       <!-- Info Lomba -->
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