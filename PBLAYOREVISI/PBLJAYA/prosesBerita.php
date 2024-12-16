<?php
require_once 'config.php'; // Include the database connection

session_start();

// Ensure the connection is available
if (!$conn) {
    die("Koneksi gagal: " . sqlsrv_errors()); // Check for connection error
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $judul = $_POST['judul'];
    $link = $_POST['link'];
    $isi = $_POST['isi'];

    // Handle image upload
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

    // Prepare the SQL query with placeholders
    $sql = "INSERT INTO berita (judul, gambar, link, isi) VALUES (?, ?, ?, ?)";
    $params = array($judul, $gambar, $link, $isi);

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error: " . sqlsrv_errors()); // Check if the query executed successfully
    } else {
        // Success - notify user and redirect
        echo "<script>
                alert('Berita berhasil disimpan!');
                window.location.href = 'dashboardSuperadmin.php'; // Redirect to the dashboard
              </script>";
    }

    // Free the statement and close the connection
    sqlsrv_free_stmt($stmt);
    $database->close(); // Close the database connection
}

// Menampilkan berita yang sudah dikirim
$sql = "SELECT * FROM berita ORDER BY tanggal_upload DESC"; // Query untuk mengambil berita
$query = sqlsrv_query($conn, $sql);

// Debugging: Menampilkan error jika ada masalah pada query
if ($query === false) {
    die(print_r(sqlsrv_errors(), true)); // Menampilkan error jika query gagal
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Form Input Berita</h1>
        <form action="prosesBerita.php" method="POST" enctype="multipart/form-data" class="form">
            <label for="judul">Judul Berita</label>
            <input type="text" id="judul" name="judul" class="form-control" required>

            <label for="link">Link Berita</label>
            <input type="url" id="link" name="link" class="form-control" required>

            <label for="gambar">Gambar Berita</label>
            <input type="file" id="gambar" name="gambar" class="form-control" required>

            <label for="isi">Isi Berita</label>
            <textarea id="isi" name="isi" class="form-control" rows="4" required></textarea>

            <div class="form-buttons">
                <button type="submit" class="btn-submit">Simpan</button>
                <a href="dashboardSuperadmin.php" class="back-button">Kembali</a>
            </div>
        </form>

        <!-- Menampilkan Berita yang Sudah Dikirim -->
        <h2>Berita yang Telah Dikirim</h2>
        <div class="berita-preview">
            <?php while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)): ?>
                <div class="berita-item">
                    <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    <p><?= htmlspecialchars($row['isi']) ?></p>
                    <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">Baca Selengkapnya</a>
                    <br>
                    <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Berita" width="150px">
                    <br>
                    <a href="hapusBerita.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus Berita</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
