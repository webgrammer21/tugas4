<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Tambah Lab
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lab = $_POST['nama_lab'];
    $query = "INSERT INTO lab (nama_lab) VALUES ('$nama_lab')";
    mysqli_query($koneksi, $query);
}

// Tampil dan Hapus Lab
$lab_list = mysqli_query($koneksi, "SELECT * FROM lab");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM lab WHERE id=$id");
    header("Location: lab.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Lab</title>
</head>
<body>
    <form method="POST" action="lab.php">
        <input type="text" name="nama_lab" placeholder="Nama Lab" required>
        <button type="submit">Tambah Lab</button>
    </form>

    <ul>
        <?php while ($lab = mysqli_fetch_assoc($lab_list)) { ?>
            <li>
                <?= $lab['nama_lab'] ?>
                <a href="lab.php?delete=<?= $lab['id'] ?>">Hapus</a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
