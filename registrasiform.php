<?php
require 'php/function.php';
$message = "";
$message_type = "";
if (isset($_POST["register"])) {
    if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["password2"])) {
        $message = "Harap isi semua kolom.";
        $message_type = "error";
        $redirect_url = "registrasiform.php";
    } elseif (!strpos($_POST["email"], '@')) {
        $message = "Email harus mengandung @.";
        $message_type = "error";
        $redirect_url = "registrasiform.php#email";
    } elseif (strlen($_POST["password"]) < 6) {
        $message = "Panjang password minimal 6 karakter.";
        $message_type = "error";
        $redirect_url = "registrasiform.php#password";
    } elseif ($_POST["password"] !== $_POST["password2"]) {
        $message = "Password dan konfirmasi password tidak sama.";
        $message_type = "error";
        $redirect_url = "registrasiform.php#password2";
    } elseif (registrasi($_POST) > 0) {
        $message = "Anda berhasil mendaftar, silakan login.";
        $message_type = "success";
        $redirect_url = "loginform.php";
    } else {
        $message = "Terjadi kesalahan, silakan coba lagi.";
        $message_type = "error";
        $redirect_url = "registrasiform.php";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/register.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register Page</title>
</head>

<body>
    <div class="login-container">
        <img src="assets/icon/jogfood-shadow.png" alt="Jogfood">
        <form class="form" action="" method="post">
            <div class="flex-column align-items-center">
                <label for="username">Username <span style="color: red;">*</span></label>
                <div class="inputForm">
                    <i class="fa-solid fa-user"></i>
                    <input placeholder="Enter your Username" class="input" type="text" name="username" id="username"
                        required>
                </div>
                <div class="flex-column">
                    <label for="email">Email <span style="color: red;">*</span></label>
                </div>
                <div class="inputForm">
                    <i class="fa-solid fa-user"></i>
                    <input placeholder="Enter your Email" class="input" type="email" name="email" id="email"
                        required>
                </div>
                <div class="flex-column">
                    <label for="password">Password <span style="color: red;">*</span></label>
                </div>
                <div class="inputForm">
                    <i class="fa-solid fa-lock"></i>
                    <input placeholder="Enter your Password" class="input" type="password" name="password" id="password"
                        required minlength="6">
                </div>
                <div class="flex-column">
                    <label for="password2">Confirm Password <span style="color: red;">*</span></label>
                </div>
                <div class="inputForm">
                    <i class="fa-solid fa-lock"></i>
                    <input placeholder="Enter your Password" class="input" type="password" name="password2"
                        id="password2" required minlength="6">
                </div>
                <button class="button-submit" name="register">Sign Up</button>
                Sudah Punya Akun?<span class="span"><a href="loginform.php" style="text-decoration: none;">Masuk
                        Disini!</a></span>
            </div>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <?php if (!empty($message)): ?>
        <script>
            Swal.fire({
                title: '<?php echo $message_type === "success" ? "Registrasi Berhasil" : "Registrasi Gagal"; ?>',
                text: '<?php echo $message; ?>',
                icon: '<?php echo $message_type; ?>',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo $redirect_url; ?>';
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>

