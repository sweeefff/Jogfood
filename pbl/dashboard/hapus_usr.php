<?php
include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id='$id'");

if($hapus){
    echo "<script>
        alert('Data Berhasil Dihapus');
        window.location.href = 'user.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal Menghapus Data');
        window.location.href = 'user.php';
    </script>";
}
?>
