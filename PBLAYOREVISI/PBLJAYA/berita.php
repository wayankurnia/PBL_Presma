<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Berita</title>
    <link rel="stylesheet" href="CSS/berita.css">
</head>
<body>
    <div class="container">
        <main class="content">
            <h1>Tambah Berita</h1>
            <form class="form" method="POST" action="prosesBerita.php" enctype="multipart/form-data">
                <!-- Input Judul -->
                <fieldset>

                    <label for="tanggal_upload">Tanggal Upload</label>
                    <input type="date" id="tanggal_upload" name="tanggal_upload" class="form-control" required>

                    <label for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" class="form-control" required>
                    
                    <label for="gambar">Gambar</label>
                    <input type="file" id="gambar" name="gambar" class="form-control" required>

                    <label for="link">Link Berita</label>
                    <input type="url" id="link" name="link" class="form-control" required>

                    <label for="isi">Isi Berita</label>
                    <textarea id="isi" name="isi" class="form-control" rows="5" required></textarea>
                </fieldset>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-submit">Kirim</button>
                    <button type="button" class="btn back-button" onclick="window.location.href='dashboardSuperadmin.php';">Kembali</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
