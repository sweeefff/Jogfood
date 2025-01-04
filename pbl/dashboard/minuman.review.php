<?php
session_start();
include "../../php/function.php";
include "../../php/koneksi.php";

if (!isset($_SESSION["username"]) || $_SESSION["role"] != "admin") {
    header("Location: ../../loginform.php");
    exit();
}

// Hapus data jika ada parameter id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM minuman_reviews WHERE id='$id'");
    if ($hapus) {
        echo "<script>
            alert('Data berhasil dihapus');
            window.location.href = 'minuman.review.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
            window.location.href = 'minuman.review.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>ADMINISTRATOR</title>
    <style>
        .nav-link:hover {
            background-color: gold;
            color: white !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-info fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">SELAMAT DATANG,
                <?php echo htmlspecialchars($_SESSION['username']); ?>!</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="ms-auto d-flex align-items-center">
                    <div class="icon">
                        <i class="fas fa-envelope-square me-3"></i>
                        <i class="fas fa-bell-slash me-3"></i>
                        <i class="fas fa-user-circle me-3"></i>
                        <a href="logout.php"><i class="fas fa-sign-out-alt float-end mt-2"
                                style="color: white;"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="row g-0 mt-5">
        <div class="col-md-2 bg-info mt-2 pt-4">
            <ul class="nav flex-column ms-3 mb-5">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="dashboard.php"><i
                            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="kuliner.php"><i class="fas fa-mortar-pestle me-2"></i>Daftar
                        Kuliner</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="minuman.php"><i class="fa fa-wine-glass me-2"></i>Daftar
                        minuman</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="restoran.php"><i class="fas fa-utensils me-2"></i>Daftar
                        Restoran</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="user.php"><i class="fas fa-users me-2"></i>Daftar User</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="kuliner.review.php"><i
                            class="fas fa-magnifying-glass me-2"></i>Daftar Kuliner
                        Review</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="minuman.review.php"><i
                            class="fas fa-magnifying-glass me-2"></i>Daftar Minuman
                        Review</a>
                    <hr class="bg-secondary">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="resto.review.php"><i
                            class="fas fa-magnifying-glass me-2"></i>Daftar Restoran
                        Review</a>
                    <hr class="bg-secondary">
                </li>
            </ul>
        </div>
        <div class="col-md-10 p-5 pt-2">
            <h3><i class="fas fa-utensils me-2"></i> Daftar Review</h3>
            <hr>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">id</th>
                        <th scope="col">ID Kuliner</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Review</th>
                        <th scope="col">Waktu Dibuat</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query = mysqli_query($koneksi, "SELECT * FROM minuman_reviews");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['minuman_id']; ?></td>
                            <td><?php echo $data['user_id']; ?></td>
                            <td><?php echo $data['rating']; ?></td>
                            <td><?php echo $data['review']; ?></td>
                            <td><?php echo $data['created_at']; ?></td>
                            <td>
                                <a href="?id=<?php echo urlencode($data['id']); ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>