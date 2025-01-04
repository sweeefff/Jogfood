<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jogfood"; //Nama Database
// melakukan koneksi ke db
$koneksi = mysqli_connect($host, $user, $pass, $db); if(!$koneksi){
echo "Gagal menghubungkan: " . die(mysqli_error($koneksi));
}
?>
