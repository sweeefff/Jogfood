<?php

session_start();
require("php/function.php");

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$dataPerPage = 6;
$totalData = count(query("SELECT * FROM restoran"));
$totalPages = ceil($totalData / $dataPerPage);

if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

$startData = ($currentPage - 1) * $dataPerPage;
$sql = "SELECT * FROM restoran LIMIT $startData, $dataPerPage";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $row = mysqli_fetch_assoc($result);
} else {
    $row = array();
}

$pagination = "";
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        $pagination .= "<li class='page-item active'><a class='page-link' href='?page=$i'>$i</a></li>";
    } else {
        $pagination .= "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
    }
}
$query = "SELECT k.id, k.nama, k.price, k.location, k.gambar,
		COALESCE(AVG(r.rating), 0) AS avg_rating
		FROM restoran k
		LEFT JOIN resto_reviews r ON k.id = r.resto_id
    GROUP BY k.id, k.nama, k.price, k.location, k.gambar
    ORDER BY
    CASE
        WHEN AVG(r.rating) IS NULL THEN 1
        ELSE 0
    END,
    avg_rating DESC,
    k.id ASC
	LIMIT $startData, $dataPerPage";

$data = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jogfood</title>
    <!-- favicon-->
    <link href="images/favicon.jpg" rel="icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Bootstrap4 link -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- css file and icons stylesheet-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/custom.css">
</head>

<body>
    <!-- Header -->
    <?php include("php/header.php"); ?>
    <!-- Header end-->
    <div class="category-section align-items-center">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 mt-5">
                    <h2 class="section-title"
                        style="margin-top: 80px; margin-left: 440px; font-size: 30px; font-weight: 600; text-center">
                        RESTORAN</h2>
                </div>
            </div>
            <?php
            while ($row = mysqli_fetch_array($data)) {
                ?>
                <div class="row">
                    <?php
                    foreach ($data as $key => $row):
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="../pbl/dashboard/gambar/<?php echo $row["gambar"]; ?>" class="card-img-top"
                                    alt="<?php echo $row["nama"]; ?>">
                                <h1><?php echo $row["nama"]; ?></h1>
                                <p><?php echo $row["price"]; ?></p>
                                <p><?php echo $row["location"]; ?></p>
                                <p>⭐ Rata-rata Rating: <?php echo number_format($row['avg_rating'], 1); ?>/5</p>
                                <a href='resto.php?id=<?php echo $row['id']; ?>&action=show'><button
                                        class="btn btn-success btn-sm">Tampilkan</button></a>

                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>

                <?php
            }
            ?>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php echo $pagination; ?>
                </ul>
            </nav>

        </div>
        <!--Footer-->
        <?php include("php/footer.php"); ?>
        <!--Footer end-->
        <!-- ALL JS FILES -->
        <script>
            document.getElementById('liveSearchInput').addEventListener('keyup', function () {
                const query = this.value.trim();
                const resultsContainer = document.getElementById('liveSearchResults');

                if (query.length > 0) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'php/pencarian_ajax.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onload = function () {
                        if (this.status === 200) {
                            resultsContainer.innerHTML = this.responseText;
                        }
                    };

                    xhr.send('query=' + encodeURIComponent(query));
                } else {
                    resultsContainer.innerHTML = ''; // Kosongkan hasil jika input kosong
                }
            });
        </script>
        <script src="js/rating.js"></script>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- ALL PLUGINS -->
        <script src="js/jquery.superslides.min.js"></script>
        <script src="js/images-loded.min.js"></script>
        <script src="js/isotope.min.js"></script>
        <script src="js/baguetteBox.min.js"></script>
        <script src="js/form-validator.min.js"></script>
        <script src="js/contact-form-script.js"></script>
        <script src="js/custom.js"></script>
</body>

</html>