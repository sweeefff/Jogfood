<?php
require 'php/function.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: loginform.php");
    exit;
}

$id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
$row = mysqli_fetch_assoc($result);

$isEditing = false;

if (isset($_POST['edit'])) {
    $isEditing = true;
}

if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $profilePicture = $row['profile_picture']; // Default existing picture

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                $profilePicture = $fileName; // Update picture name
            } else {
                $error = "Failed to upload the profile picture.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    $query = "UPDATE user SET username='$username', email='$email' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: profile.php");
        exit;
    } else {
        $error = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/responsive.css">

</head>

<body>
    <!--========= Start Header=========-->
    <header class="top-navbar">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img style="width: 70%; margin-left: 0%; margin-right: 20%;" src="images/jogfoods.png" alt="" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food"
                    aris-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbars-rs-food">
                    <!-- Navigation Menu bar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <!--Drop Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown-a"
                                data-toggle="dropdown">Makanan</a>
                            <!--Drop-->
                            <div class="dropdown-menu" aria-labelledby="dropdown-a">
                                <a class="dropdown-item" href="tradisional.php">Tradisional</a>
                                <a class="dropdown-item" href="nontradisional.php">Non Tradisional</a>
                            </div>
                        </li>
                        <!--Drop Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="minuman.php">Minuman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="restoran.php">Restoran</a>
                        </li>
                        <?php if (isset($_SESSION['username'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown-a" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i>
                                    <?php echo $_SESSION["username"]; ?></a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item"
                                        href="logout.php"><strong><?php echo "ID: " . $_SESSION["id"]; ?></strong></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <header>
            <h1>USER PROFILE</h1>
        </header>
        <div class="profile-card">
            <div class="profile-image">
                <?php if (!empty($profilePicture)): ?>
                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Image"
                        class="img-thumbnail" width="150">
                <?php endif; ?>
                <img src="https://picsum.photos/150" alt="Profile Image" class="img-thumbnail" width="150">
            </div>
            <form class="user-info" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="id" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-10">
                        <input type="text" id="id" class="form-control"
                            value="<?php echo htmlspecialchars($row['id']); ?>" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="username" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" id="username" name="username" class="form-control"
                            value="<?php echo htmlspecialchars($row['username']); ?>" <?php echo $isEditing ? '' : 'disabled'; ?> required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" id="email" name="email" class="form-control"
                            value="<?php echo htmlspecialchars($row['email']); ?>" <?php echo $isEditing ? '' : 'disabled'; ?> required>
                    </div>
                </div>
                <a class="change-password" href="changepass.php">Change Password</a>
                <?php if ($isEditing): ?>
                    <button type="submit" class="btn btn-primary" name="update">Update Profile</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary" name="edit">Edit Profile</button>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="text-danger mt-2"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="js/jquery.superslides.min.js"></script>
    <script src="js/images-loded.min.js"></script>
    <script src="js/isotope.min.js"></script>
    <script src="js/form-validator.min.js"></script>
    <script src="js/contact-form-script.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>