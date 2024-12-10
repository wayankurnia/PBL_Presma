<?php
$host = "SYAQIRA\SYAQIRASQLSERVER"; 
$connInfo = array("Database" => "prestasi_mahasiswa");
$conn = sqlsrv_connect($host, $connInfo);
if ($conn) {
echo "";
} else {
echo "Koneksi Gagal";
die (print_r(sqlsrv_errors(), true));
}
?>