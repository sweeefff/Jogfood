<?php
include 'koneksi.php';

// Ambil data dari form
$id = $_POST['id'];
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi'];
$bahan = $_POST['bahan'];
$cara_membuat = $_POST['cara_membuat'];

// Fungsi untuk mengupload gambar
function upload()
{
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah ada file yang diupload
    if ($error === 4) {
        echo "<script>
                alert('Pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }

    // Validasi ekstensi file
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('Yang Anda upload bukan gambar!');
              </script>";
        return false;
    }

    // Cek ukuran file
    if ($ukuranFile > 10000000) { // Maksimal 10 MB
        echo "<script>
                alert('Ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // Buat nama file baru yang unik
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($tmpName, 'gambar/' . $namaFileBaru)) {
        return $namaFileBaru;
    } else {
        echo "<script>
                alert('Gagal mengupload gambar!');
              </script>";
        return false;
    }
}

// Proses upload gambar
$gambar = upload();
if (!$gambar) {
    exit; // Berhenti jika upload gagal
}

// Masukkan data ke database
$query = "INSERT INTO kuliner (id, nama, kategori, deskripsi, bahan, cara_membuat, gambar) 
          VALUES ('$id', '$nama', '$kategori', '$deskripsi', '$bahan', '$cara_membuat', '$gambar')";

$input = mysqli_query($koneksi, $query);

// Periksa apakah data berhasil disimpan
if ($input) {
    echo "<script>
            alert('Data Berhasil Disimpan');
            window.location.href = 'kuliner.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Menyimpan Data: " . mysqli_error($koneksi) . "');
            window.location.href = 'kuliner.php';
          </script>";
}
?>