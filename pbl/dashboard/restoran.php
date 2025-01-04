<?php
session_start();
session_start();
include "../../php/function.php";
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "admin") {
    header("Location: ../../loginform.php");
    exit();
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
            <h3><i class="fas fa-utensils me-2"></i> Daftar Restoran</h3>
            <hr>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                <i class="fas fa-plus-circle me-2"></i>TAMBAH DATA RESTORAN
            </button>
            <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahDataLabel">Tambah Data Restoran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="tambah_rstn.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="id" class="form-label">id</label>
                                    <input type="text" class="form-control" id="id" name="id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Restoran</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar Restoran</>
                                        <input type="file" class="form-control" id="gambar" name="gambar" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit Data -->
            <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDataLabel">Edit Data Kuliner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="ubah_rstn.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-nama" class="form-label">Nama Restoran</label>
                                    <input type="text" class="form-control" id="edit-nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="tradisional">Tradisional</option>
                                        <option value="non-tradisional">Non Tradisional</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-price" class="form-label">Range Harga</label>
                                    <input type="text" class="form-control" id="edit-price" name="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="edit-location" name="location" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-gambar" class="form-label">Gambar Restoran</label>
                                        <input type="file" class="form-control" id="edit-gambar" name="gambar">
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">id</th>
                        <th scope="col">NAMA RESTORAN</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">LOCATION</th>
                        <th scope="col">GAMBAR</th>
                        <th scope="col">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query = mysqli_query($koneksi, "SELECT * FROM restoran");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['price']; ?></td>
                            <td><?php echo $data['location']; ?></td>
                            <td><img src="gambar/<?php echo $data['gambar']; ?>" alt="gambar" width="100" height="100"></td>
                            <td>
                                <button class="btn btn-success btn-sm me-1 edit-button" data-bs-toggle="modal"
                                    data-bs-target="#editDataModal" data-id="<?php echo $data['id']; ?>"
                                    data-nama="<?php echo $data['nama']; ?>" data-price="<?php echo $data['price']; ?>"
                                    data-location="<?php echo $data['location']; ?>"
                                    data-gambar="<?php echo $data['gambar']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="hapus_rstn.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                    <i class="fas fa-trash-alt"></i>Hapus
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const price = this.getAttribute('data-price');
                    const location = this.getAttribute('data-location');
                    const gambar = this.getAttribute('data-gambar');

                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-nama').value = nama;
                    document.getElementById('edit-price').value = price;
                    document.getElementById('edit-location').value = location;
                    document.getElementById('edit-gambar').value = gambar;
                });
            });
        });
    </script>
</body>

</html>