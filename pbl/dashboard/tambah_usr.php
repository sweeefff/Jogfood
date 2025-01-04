<?php
include 'koneksi.php';
$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];
$input = mysqli_query($koneksi, "INSERT INTO user (id, username, password, role) VALUES('$id', '$username', '$password', '$role')") or die(mysqli_error($koneksi));

if($input){
    echo "<script>
        alert('Data Berhasil Disimpan');
        window.location.href = 'user.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal Menyimpan Data');
        window.location.href = 'user.php';
    </script>";
}
?>