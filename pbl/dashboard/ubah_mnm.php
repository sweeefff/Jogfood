<?php
// include database connection file
include 'koneksi.php';
    $id=$_POST['id'];
    $nama=$_POST['nama'];
    $kategori=$_POST['kategori'];
    $deskripsi=$_POST['deskripsi'];
    $bahan=$_POST['bahan'];
    $cara_membuat=$_POST['cara_membuat'];

    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    $result = mysqli_query($koneksi, "UPDATE minuman SET nama='$nama',kategori='$kategori',deskripsi='$deskripsi',bahan= '$bahan',cara_membuat='$cara_membuat',gambar='$gambar' WHERE id='$id'");
    
    
    // Redirect to homepage to display updated user in list
    header("Location: minuman.php");
    function upload(){
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];
        
        //cek apakah tidak ada gambar yang diupload
        if ($error === 4) {
            echo "<script>
                alert('Pilih gambar terlebih dahulu');
            </script>";
            return false;
        }
        //cek apakah yang diupload adalah gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        //cek ukuran gambar jika terlalu besar
        if (in_array($ekstensiGambar, $ekstensiGambarValid)) {
            if ($ukuranFile < 10000000) {
                $namaFileBaru = uniqid();
                $namaFileBaru .= '.';
                $namaFileBaru .= $ekstensiGambar;
                move_uploaded_file($tmpName, 'gambar/' . $namaFileBaru);
                return $namaFileBaru;
            } else {
                echo "<script>
                    alert('Ukuran gambar terlalu besar');
                </script>";
                return false;
            }
        } else {
            echo "<script>
                alert('Yang anda upload bukan gambar');
            </script>";
            return false;
        }
    }
?>
