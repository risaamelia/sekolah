<?php
include 'koneksi.php';

// Periksa apakah ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID siswa tidak ditemukan.");
}

$id_siswa = $_GET['id'];

// Query hapus siswa
$query = "DELETE FROM siswa WHERE id_siswa = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);

if (mysqli_stmt_execute($stmt)) {
    // Redirect kembali ke index.php setelah berhasil
    header("Location: index.php?pesan=Siswa berhasil dihapus!");
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus siswa: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>
