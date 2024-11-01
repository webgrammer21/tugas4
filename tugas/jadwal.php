<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan data lab dan waktu
$lab_list = mysqli_query($koneksi, "SELECT * FROM lab");
$waktu_list = mysqli_query($koneksi, "SELECT * FROM waktu");

// Menangani Tambah atau Update Jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lab_id = $_POST['lab_id'];
    $waktu_id = $_POST['waktu_id'];
    $matkul = $_POST['matkul'];
    $jurusan = $_POST['jurusan'];

    // Cek apakah ini adalah update atau insert baru
    if (isset($_POST['edit_id'])) {
        // Update jadwal yang sudah ada
        $edit_id = $_POST['edit_id'];
        $query = "UPDATE jadwal SET lab_id = '$lab_id', waktu_id = '$waktu_id', mk = '$matkul', jurusan = '$jurusan' WHERE id = $edit_id";
    } else {
        // Tambah jadwal baru
        $query = "INSERT INTO jadwal (lab_id, waktu_id, mk, jurusan) VALUES ('$lab_id', '$waktu_id', '$matkul', '$jurusan')";
    }
    mysqli_query($koneksi, $query);
    header("Location: jadwal.php");
}

// Mendapatkan data jadwal untuk ditampilkan
$jadwal_list = mysqli_query($koneksi, "
    SELECT mk, jurusan, jadwal.id, lab.nama_lab, waktu.waktu_awal, waktu.waktu_akhir 
    FROM jadwal 
    JOIN lab ON jadwal.lab_id = lab.id 
    JOIN waktu ON jadwal.waktu_id = waktu.id
");

// Menangani Edit: Mendapatkan data jadwal berdasarkan ID yang dipilih
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($result);
}

// Menangani Hapus Jadwal
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM jadwal WHERE id = $id");
    header("Location: jadwal.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Jadwal</title>
</head>
<body>
    <h2><?= isset($edit_data) ? 'Edit' : 'Tambah' ?> Jadwal</h2>
    <form method="POST" action="jadwal.php">
        <select name="lab_id" required>
            <?php while ($lab = mysqli_fetch_assoc($lab_list)) { ?>
                <option value="<?= $lab['id'] ?>" <?= isset($edit_data) && $edit_data['lab_id'] == $lab['id'] ? 'selected' : '' ?>>
                    <?= $lab['nama_lab'] ?>
                </option>
            <?php } ?>
        </select>
        <select name="waktu_id" required>
            <?php while ($waktu = mysqli_fetch_assoc($waktu_list)) { ?>
                <option value="<?= $waktu['id'] ?>" <?= isset($edit_data) && $edit_data['waktu_id'] == $waktu['id'] ? 'selected' : '' ?>>
                    <?= $waktu['waktu_awal'] ?> - <?= $waktu['waktu_akhir'] ?>
                </option>
            <?php } ?>
        </select>
        <br>
        <input type="text" placeholder="Matkul" name="matkul" value="<?= $edit_data['mk'] ?? '' ?>" required>
        <br>
        <input type="radio" name="jurusan" value="IF" <?= isset($edit_data) && $edit_data['jurusan'] == 'IF' ? 'checked' : '' ?>> IF
        <input type="radio" name="jurusan" value="SI" <?= isset($edit_data) && $edit_data['jurusan'] == 'SI' ? 'checked' : '' ?>> SI
        <br>
        
        <?php if (isset($edit_data)) { ?>
            <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
        <?php } ?>

        <button type="submit"><?= isset($edit_data) ? 'Update' : 'Tambah' ?> Jadwal</button>
    </form>

    <h2>Daftar Jadwal</h2>
    <ul>
        <?php while ($jadwal = mysqli_fetch_assoc($jadwal_list)) { ?>
            <li>
                <?= $jadwal['jurusan'] ?> : <?= $jadwal['mk'] ?> : <?= $jadwal['nama_lab'] ?> : <?= $jadwal['waktu_awal'] ?> - <?= $jadwal['waktu_akhir'] ?>
                <a href="jadwal.php?edit=<?= $jadwal['id'] ?>">Edit</a>
                <a href="jadwal.php?delete=<?= $jadwal['id'] ?>" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
