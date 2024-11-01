<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat Datang di Sistem Praktikum</h1>
    <nav>
        <a href="lab.php">Kelola Lab</a>
        <a href="waktu.php">Kelola Waktu</a>
        <a href="jadwal.php">Jadwal Praktikum</a>
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>
