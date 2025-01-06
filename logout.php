<?php
// Mulai sesi
session_start();
// Kosongkan semua data dalam session
$_SESSION = array();
session_unset();
// Hapus session
session_destroy();
// Redirect pengguna ke halaman utama (index.php)
header("Location: ../../index.php");

?>