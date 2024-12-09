<?php
$host = "LAPTOP-NR8UTQ4Q"; 
$connInfo = array("Database" => "prestasi_mahasiswa");
$conn = sqlsrv_connect($host, $connInfo);
if ($conn) {
echo "Koneksi berhasil.<br />";
} else {
echo "Koneksi Gagal";
die (print_r(sqlsrv_errors(), true));
}
?>