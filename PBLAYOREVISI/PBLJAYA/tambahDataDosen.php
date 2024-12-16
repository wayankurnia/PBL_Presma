<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRESMA Dashboard</title>
    <link rel="stylesheet" href="CSS/StyleTambahData.css">
</head>
<body>
    <div class="container">
        <main class="content">
            <h1>Tambah Data Dosen</h1>
            <form class="form" method="POST" action="prosesTambahData.php" enctype="multipart/form-data">
                <!-- Data Mahasiswa -->
                <fieldset>
                    <legend>Data Dosen</legend>
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    <label for="nama">NIDN</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    <label for="nama">Tempat dan Tanggal Lahir</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    <label for="nama">Alamat</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    <label for="nama">No. Telp</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    <label for="nama">Email</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                </fieldset>
                <div class="form-buttons2">
                    <button type="submit" class="btn btn-simpan">Simpan</button>
                    <button type="submit" class="btn btn-kirim">Kirim</button>
                    <button type="reset" class="btn btn-kembali" onclick="window.location.href='dashboardSuperadmin.php';">Kembali</button>
                </div>                
            </form>
        </main>
    </div>
</body>
</html>
