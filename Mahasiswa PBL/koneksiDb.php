<?php
$host = "SYAQIRA\SYAQIRASQLSERVER"; 
$connInfo = array("Database" => "Prestasi_Mahasiswa");
$conn = sqlsrv_connect($host, $connInfo);
if ($conn) {
echo "Koneksi berhasil.<br />";
} else {
echo "Koneksi Gagal";
die (print_r(sqlsrv_errors(), true));

//Debug Data yang diterima 
var_dump($_POST);
var_dump($_FILES);
die();
}