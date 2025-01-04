<?php
$conn=mysqli_connect("localhost","root","","pbl");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query){
    global $conn;
    $result=mysqli_query($conn,$query);
    $rows=[];
    while($row=mysqli_fetch_assoc($result)){
        $rows[]=$row;
    }
    return $rows;
}

function upload(){
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

function registrasi($data){
    global $conn;
    $username= mysqli_real_escape_string($conn,stripslashes($data["username"]));
    $password=mysqli_real_escape_string($conn, $data["password"]);
    $password2=mysqli_real_escape_string($conn, $data["password2"]);
    $email=mysqli_real_escape_string($conn,$data["email"]);
    
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    //cek konfirmasi password
    if($password!=$password2){
        echo "<script>
        alert('Konfirmasi password salah');
        </script>";
        return false;
    }

    //cek username sudah ada atau belum
    $result=mysqli_query($conn,"SELECT username FROM user WHERE username='$username'");
    if(mysqli_fetch_assoc($result)){
        echo "<script>
        alert('Username sudah ada');
        </script>";
        return false;
    }

    //enkripsi password
    $password=password_hash($password,PASSWORD_DEFAULT);
    //registrasi user ke database
    $query="INSERT INTO users VALUES('','$username','$email','$password')";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}
function cari($keyword){
    $query="SELECT * FROM user WHERE
        nama_kuliner LIKE '%$keyword%'
        ";
    return query($query);
}
?>