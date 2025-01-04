<?php

session_start();
require("php/function.php");

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['id'];
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM kuliner WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    $row = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (isset($user_id)) {
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $review = mysqli_real_escape_string($conn, $_POST['review']);

        if ($rating > 0 && $review !== "") {
            $insert_review_sql = "INSERT INTO kuliner_reviews (kuliner_id, user_id, rating, review) VALUES ($id, $user_id, $rating, '$review')";
            if (mysqli_query($conn, $insert_review_sql)) {
                echo "<script>Swal.fire('Success', 'Review berhasil ditambahkan!', 'success');</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Gagal menambahkan review: " . mysqli_error($conn) . "', 'error');</script>";
            }
        } else {
            $warningMessage = $rating <= 0 ? 'Rating harus dipilih.' : 'Review tidak boleh kosong.';
            echo "<script>Swal.fire('Warning', '$warningMessage', 'warning');</script>";
        }
    } else {
        echo "<script>Swal.fire('Warning', 'Anda harus login untuk memberikan review.', 'warning');</script>";
    }
}


$reviews_sql = "SELECT r.rating, r.review, r.created_at, u.username FROM kuliner_reviews r JOIN user u ON r.user_id = u.id WHERE r.kuliner_id = $id ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

$avg_rating_sql = "SELECT AVG(rating) AS avg_rating FROM kuliner_reviews WHERE kuliner_id = $id";
$avg_rating_result = mysqli_query($conn, $avg_rating_sql);
$avg_rating = 0;
if ($avg_rating_result && mysqli_num_rows($avg_rating_result) > 0) {
    $avg_row = mysqli_fetch_assoc($avg_rating_result);
    $avg_rating = $avg_row['avg_rating'] !== null ? round($avg_row['avg_rating'], 1) : 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jogfood</title>
    <link href="images/favicon.jpg" rel="icon">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body>
    <?php include "php/header.php"; ?>

    <div class="container content">
        <div class="row">
            <div class="col-lg-6">
                <div id="carouselExampleControls" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="../pbl/dashboard/gambar/<?php echo $row['gambar']; ?>" class="d-block w-100"
                                alt="Gambar Kuliner">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h2><?php echo $row['nama']; ?></h2>
                <p><?php echo $row['deskripsi']; ?></p>
                <h4>Bahan Masakan</h4>
                <ul>
                    <?php
                    $bahan_array = preg_split("/\,|\n|\s{2,}/", $row['bahan']);
                    foreach ($bahan_array as $bahan) {
                        if (trim($bahan) !== "") {
                            echo "<li>" . htmlspecialchars($bahan) . "</li>";
                        }
                    }
                    ?>

                    <p><strong>Rata-rata Rating: <?php echo $avg_rating; ?>/5</strong></p>
                </ul>

            </div>
            <h4>Cara Membuat</h4>
            <ul>
                <?php
                $cara_array = preg_split("/\,|\n|\s{2,}/", $row['cara_membuat']);
                foreach ($cara_array as $cara) {
                    if (trim($cara) !== "") {
                        echo "<li>" . htmlspecialchars($cara) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <hr>
        <div class="row mt-4">
            <div class="col-12">
                <button class="btn btn-primary review-button" onclick="toggleReviewForm()">Berikan Review</button>
                <div id="reviewForm" class="review-form mt-4">
                    <?php if (isset($user_id)): ?>
                        <form method="POST" onsubmit="return validateReviewForm()">
                            <div class="form-group mb-3">
                                <label for="rating">Rating:</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5"><label
                                        for="star5">&#9733;</label>
                                    <input type="radio" id="star4" name="rating" value="4"><label
                                        for="star4">&#9733;</label>
                                    <input type="radio" id="star3" name="rating" value="3"><label
                                        for="star3">&#9733;</label>
                                    <input type="radio" id="star2" name="rating" value="2"><label
                                        for="star2">&#9733;</label>
                                    <input type="radio" id="star1" name="rating" value="1"><label
                                        for="star1">&#9733;</label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="review">Review:</label>
                                <textarea id="review" name="review" rows="4" class="form-control" minlength="10"></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="btn btn-primary">Kirim Review</button>
                        </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- User Reviews -->
        <div class="review-list">
            <h3 class="mt-5">Review Pengguna</h3>
            <?php if (mysqli_num_rows($reviews_result) > 0): ?>
                <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                    <div class="card">
                        <div class="card-body">
                            <img src="https://picsum.photos/50" alt="Avatar" class="me-3">
                            <h5 class="card-title"> <?php echo htmlspecialchars($review['username']); ?></h5>
                            <p class="card-text"> <?php echo htmlspecialchars($review['review']); ?></p>
                            <small class="text-muted"> <?php echo $review['created_at']; ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada review.</p>
            <?php endif; ?>
            <?php if (isset($message)): ?>
                <div class="alert <?php echo $message_type; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("php/footer.php"); ?>
    <script>
        function toggleReviewForm() {
            <?php if (!isset($user_id)): ?>
                // SweetAlert untuk pengguna yang tidak login
                Swal.fire({
                    icon: 'warning',
                    title: 'You must login first',
                    text: 'Silakan login untuk memberikan review.',
                    confirmButtonText: 'Login',
                    showCancelButton: true,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'loginform.php';
                    }
                });
            <?php else: ?>
                // Tampilkan form jika user login
                const form = document.getElementById('reviewForm');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            <?php endif; ?>
        }
        function validateReviewForm() {
            // Validasi Rating
            const ratingInputs = document.getElementsByName('rating');
            let ratingSelected = false;
            for (const input of ratingInputs) {
                if (input.checked) {
                    ratingSelected = true;
                    break;
                }
            }

            if (!ratingSelected) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Mohon pilih rating sebelum mengirimkan review.',
                });
                return false;
            }

            // Validasi Review
            const reviewText = document.getElementById('review').value.trim();
            if (reviewText === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Review tidak boleh kosong.',
                });
                return false;
            }

            // Jika Valid
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/rating.js"></script>
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