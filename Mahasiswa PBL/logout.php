<?php
session_start();
session_destroy();
header('Location: index.html'); // Redirect ke halaman login
exit;
?>
