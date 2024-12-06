<?php
// Koneksi ke database
$serverName = "SYAQIRA\SYAQIRASQLSERVER";
$username = "SYAQIRA\me";
$password = "syaqira123";  // Ganti dengan password database Anda
$dbName = "Prestasi_Mahasiswa";

// Membuat koneksi
$conn = new mysqli($serverName, $username, $password, $dbName);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari form
$nama = $_POST['nama'];
$programStudi = $_POST['program_studi'];
$dosen = $_POST['dosen'];
$peranPembimbing = $_POST['peran_pembimbing'];
$jenisKompetisi = $_POST['jenis_kompetisi'];
$tanggalMulai = $_POST['tanggal_mulai'];
$tanggalSelesai = $_POST['tanggal_selesai'];
$fotoKegiatan = $_FILES['foto_kegiatan']['name'];

// Menyimpan foto ke server (jika ada)
if ($fotoKegiatan) {
    move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], "uploads/" . $fotoKegiatan);
}

// Query SQL untuk memasukkan data
$sql = "INSERT INTO Prestasi (NIM, Peran, Jenis, TingkatKompetisi, FotoKegiatan, TanggalMulai, TanggalSelesai, DosenPembimbing, PeranPembimbing)
        VALUES ('2341760123', 'Ketua', '$jenisKompetisi', 'Nasional', '$fotoKegiatan', '$tanggalMulai', '$tanggalSelesai', '$dosen', '$peranPembimbing')";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil disimpan!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Menutup koneksi
$conn->close();
?>
