<?php
session_start();
session_destroy();
header('Location: index.php'); // Alihkan ke halaman login setelah logout
exit();
?>
