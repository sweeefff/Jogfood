<?php
session_start();
require("php/function.php");

if (!isset($_SESSION["username"])) {
    header("Location: loginform.php");
    exit;
}

$username = $_SESSION["username"];

if (isset($_POST["change"])) {
    $oldpass = $_POST["oldpass"];
    $newpass = $_POST["newpass"];
    $newpass2 = $_POST["confirmpass"];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($oldpass, $row["password"])) {
        if ($newpass === $newpass2) {
            $hashedPass = password_hash($newpass, PASSWORD_DEFAULT);
            $query = "UPDATE user SET password = '$hashedPass' WHERE username = '$username'";
            mysqli_query($conn, $query);

            $error = ""; // Tidak ada error
        } else {
            $error = "Password baru tidak sama.";
        }
    } else {
        $error = "Password lama salah.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jogfood</title>
    <link href="images/favicon.jpg" rel="icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/register.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container" id="loginform">
        <img src="assets/icon/selamat-datang.png" alt="Jogfood">
        <form method="post" action="" class="form">
            <div class="flex-column align-items-center">
                <label for="oldpass">Old Password</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input placeholder="Enter Old Password" class="input" type="password" name="oldpass" id="oldpass"
                    required>
                <i onclick="togglePassword('oldpass')" class="fa-solid fa-eye" id="toggleOldPassword"></i>
            </div>
            <div class="flex-column">
                <label for="newpass">New Password</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input placeholder="Enter New Password" class="input" type="password" name="newpass" id="newpass"
                    minlength="6" required>
                <i onclick="togglePassword('newpass')" class="fa-solid fa-eye" id="toggleNewPassword"></i>
            </div>
            <div class="flex-column">
                <label for="confirmpass">Confirm Password</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input placeholder="Enter Confirm Password" class="input" type="password" name="confirmpass"
                    id="confirmpass" minlength="6" required>
                <i onclick="togglePassword('confirmpass')" class="fa-solid fa-eye" id="toggleConfirmPassword"></i>
            </div>
            <button class="button-submit" name="change">Change Password</button>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php if (isset($_POST["change"])): ?>
    <script>
        Swal.fire({
            title: '<?php echo empty($error) ? "Success" : "Error"; ?>',
            text: '<?php echo empty($error) ? "Password berhasil diubah" : $error; ?>',
            icon: '<?php echo empty($error) ? "success" : "error"; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed && '<?php echo empty($error); ?>' === '1') {
                window.location.href = 'profile.php';
            }
        });
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById(`toggle${id}`);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        };
    </script>
<?php endif; ?>

</body>

</html>