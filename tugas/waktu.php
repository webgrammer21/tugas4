<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Tambah Waktu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waktu_awal = $_POST['waktu_awal'];
    $waktu_akhir = $_POST['waktu_akhir'];
    $query = "INSERT INTO waktu (waktu_awal, waktu_akhir) VALUES ('$waktu_awal', '$waktu_akhir')";
    mysqli_query($koneksi, $query);
}

// Tampil dan Hapus Waktu
$waktu_list = mysqli_query($koneksi, "SELECT * FROM waktu");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM waktu WHERE id=$id");
    header("Location: waktu.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Waktu</title>
</head>
<body>
    <form method="POST" action="waktu.php">
        <input type="time" name="waktu_awal" required>
        <input type="time" name="waktu_akhir" required>
        <button type="submit">Tambah Waktu</button>
    </form>

    <ul>
        <?php while ($waktu = mysqli_fetch_assoc($waktu_list)) { ?>
            <li>
                <?= $waktu['waktu_awal'] ?> - <?= $waktu['waktu_akhir'] ?>
                <a href="waktu.php?delete=<?= $waktu['id'] ?>">Hapus</a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
