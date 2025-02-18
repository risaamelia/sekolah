<?php
include 'koneksi.php';

// Periksa apakah ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID kelas tidak ditemukan.");
}

$id_kelas = $_GET['id'];

// Query hapus kelas
$query = "DELETE FROM kelas WHERE id_kelas = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_kelas);

if (mysqli_stmt_execute($stmt)) {
    // Redirect kembali ke kelas.php setelah berhasil
    header("Location: kelas.php?pesan=Kelas berhasil dihapus");
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus kelas: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>
