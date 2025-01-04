<?php
session_start(); // Memulai session

// Memeriksa apakah sessio  'nama' tersedia
if (isset($_SESSION['nama'])) {
    echo "Nama: " . $_SESSION['nama'] . "<br>";
    echo "Role: " . $_SESSION['role'] . "<br>";
} else {
    echo "Session belum diset. <a href='set_session.php'>Set Session</a>";
}
?>