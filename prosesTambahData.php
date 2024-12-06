<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $programStudi = $_POST['program-studi'];
    $dosenPembimbing = $_POST['dosen'];
    $peranPembimbing = $_POST['peran-pembimbing'];
    $peran = $_POST['peran'];
    $jenisKompetisi = $_POST['jenis-kompetisi'];
    $tingkatKompetisi = $_POST['program-studi'];
    $namaKompetisi = $_POST['nama-kompetisi'];
    $tanggalMulai = $_POST['tanggal-mulai'];
    $tanggalSelesai = $_POST['tanggal-selesai'];

    // Handle file uploads
    $fotoKegiatan = file_get_contents($_FILES['foto-kegiatan']['tmp_name']);
    $filePoster = file_get_contents($_FILES['file-poster']['tmp_name']);

    $query = "INSERT INTO PrestasiMahasiswa 
        (nama_lengkap, program_studi, dosen_pembimbing, peran_pembimbing, peran, jenis_kompetisi, tingkat_kompetisi, nama_kompetisi, tanggal_mulai, tanggal_selesai, foto_kegiatan, file_poster)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $nama, $programStudi, $dosenPembimbing, $peranPembimbing, $peran, $jenisKompetisi, $tingkatKompetisi, 
        $namaKompetisi, $tanggalMulai, $tanggalSelesai, $fotoKegiatan, $filePoster
    ];

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Data berhasil ditambahkan!";
    }
}
?>
