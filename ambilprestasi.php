<?php
function fetchPrestasi($conn) {
    $query = "SELECT * FROM prestasi";
    return $conn->query($query);
}
?>