<?php
//Memulai session
session_start();
//Memanggil file function
include "php/function.php";

// Cek apakah form perubahan password sudah disubmit
try {
    if (isset($_POST["login"])) {
        // Ambil old password, new password, dan konfirmasi password dari form
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        // Query untuk mencari user berdasarkan username
        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

        // Jika ditemukan tepat 1 hasil
        if (mysqli_num_rows($result) === 1) {
            // Ambil data user dari hasil query
            $row = mysqli_fetch_assoc($result);
            // Verifikasi password dengan password yang terenkripsi di database
            if (password_verify($password, $row["password"])) {
                // Set session untuk login yang berhasil
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["role"] = $row["role"];

                // Tandai bahwa login berhasil
                $_SESSION['login_success'] = true;
            } else {
                // Jika password tidak cocok, set error
                throw new Exception('Password salah');
            }
        } else {
            // Jika tidak ditemukan user dengan username tersebut, set error
            throw new Exception('Username tidak ditemukan');
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css" type="text/css">
    <title>Login Page</title>
</head>

<body>
    <div class="login-container" id="loginform">
        <img src="assets/icon/selamat-datang.png" alt="Jogfood">
        <form method="post" action="" class="form">
            <div class="flex-column align-items-center">
                <label for="username">Username</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-user"></i>
                <input placeholder="Enter your Username" class="input" type="text" name="username" id="username">
            </div>
            <div class="flex-column">
                <label for="password">Password</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input placeholder="Enter your Password" class="input" type="password" name="password" id="password">
                <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>
            <div class="flex-row">
                <div>
                    <input type="checkbox" id="remember-me" name="remember">
                    <label for="remember-me">Remember me</label>
                </div>
            </div>
            <button class="button-submit" name="login">Sign In</button>
            Tidak Punya Akun?<span class="span"><a href='registrasiform.php' style="text-decoration: none;">Daftar
                    Disini!</a></span>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Username/Password Salah',
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>
    <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: 'Selamat Datang!',
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                <?php if ($_SESSION['role'] == "admin"): ?>
                    window.location.href = "pbl/dashboard/dashboard.php";
                <?php else: ?>
                    window.location.href = "index.php";
                <?php endif; ?>
            });
        </script>
        <?php
        unset($_SESSION['login_success']);
        ?>
    <?php endif; ?>
</body>

</html>