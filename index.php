<?php
session_start();
include 'db.php';

$query = "SELECT * FROM books";
$result = $conn->query($query);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <title>LiterateSpace</title>
</head>
<body>

<!-- Nav -->
<?php include('includes/nav.php'); ?>
<!-- /Nav -->

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Hallo, <?php echo $username ? htmlspecialchars($username) : 'Rekan LiterateSpace'; ?></h1>
        <p>Temukan berbagai Pengetahuan di LiterateSpace yang menyediakan <br> segudang buku dan dapat diakses kapan pun. <br> "Bersama LiterateSpace, Menciptakan Peluang Literasi!"</p>
        <a href="pinjam.php" class="btn-hero" onclick="openLoginModal()">Selengkapnya ></a>
    </div>
</section>

<section class="daftar-buku">
    <h3>LiterateSpace Menyediakan Berbagai Jenis Buku yang Bisa kalian Pinjam Loh!</h3>
    <div class="grid-buku">
        <div class="buku-card" style="background-color: #f88f6d">
            <img src="assets/images/buku1.png" alt="buku1">
        </div>
        <div class="buku-card"  style="background-color: #a0f3a0">
            <img src="assets/images/buku2.png" alt="buku2">
        </div>
        <div class="buku-card" style="background-color: #ffe974">
            <img src="assets/images/buku3.png" alt="buku3">
        </div>
        <div class="buku-card" style="background-color: #89c0fc">
            <img src="assets/images/buku4.png" alt="buku4">
        </div>
        <div class="buku-card" style="background-color: #ff7d97">
            <img src="assets/images/buku5.png" alt="buku5">
        </div>
        <div class="buku-card" style="background-color: #eb96e9">
            <img src="assets/images/buku6.png" alt="buku6">
        </div>
    </div>
    <a href="pinjam.php" class="btn-pinjam">Pinjam Buku</a>
</section>

<!-- Daftar buku -->
<?php include('includes/daftarBuku.php'); ?>
<!-- /Daftar Buku -->

<!-- Footer -->
<?php include('includes/footer.php'); ?>
<!-- /Footer -->

</body>
</html>
