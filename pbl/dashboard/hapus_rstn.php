<?php
include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM restoran WHERE id='$id'");

if($hapus){
    echo "<script>
        alert('Data Berhasil Dihapus');
        window.location.href = 'restoran.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal Menghapus Data');
        window.location.href = 'restoran.php';
    </script>";
}
?>
