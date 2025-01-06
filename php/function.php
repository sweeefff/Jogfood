<?php
$conn = mysqli_connect("localhost", "root", "", "jogfood");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function registrasi($data)
{
    global $conn;
    $username = mysqli_real_escape_string($conn, stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);

    //cek konfirmasi password
    if ($password != $password2) {
        echo "<script>
        alert('Konfirmasi password salah');
        </script>";
        return false;
    }

    //cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username='$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Username sudah ada');
        </script>";
        return false;
    }

    //enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);
    //registrasi user ke database
    $query = "INSERT INTO user (username, email, password, role) VALUES(?, ?, ?, 'user')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
    return $stmt->affected_rows;
}
function upload()
{
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar_kuliner']['error'];
    $tmpName = $_FILES['gambar_kuliner']['tmp_name'];

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
    if (in_array($ekstensiGambar, $ekstensiGambarValid)) {
        if ($ukuranFile < 1000000) {
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
function search_data(string $keyword)
{
    global $conn; // Pastikan koneksi database tersedia.

    // Escape input untuk mencegah SQL Injection
    $safe_query = mysqli_real_escape_string($conn, $keyword);

    // Query pencarian pada beberapa kolom
    $sql = "SELECT * FROM kuliner, restoran, minuman WHERE
            nama LIKE '%$safe_query%' OR
            deskripsi LIKE '%$safe_query%' OR
            kategori LIKE '%$safe_query%' OR
            name LIKE '%$safe_query%' OR
            price LIKE '%$safe_query%'";

    $result = mysqli_query($conn, $sql);

    $search_results = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    }

    return $search_results;
}
?>