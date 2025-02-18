<?php
include 'koneksi.php';

// Periksa apakah ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Wali tidak ditemukan.");
}

$id_wali = $_GET['id'];

// Query hapus wali murid
$query = "DELETE FROM wali_murid WHERE id_wali = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_wali);

if (mysqli_stmt_execute($stmt)) {
    // Redirect kembali ke wali_murid.php setelah berhasil
    header("Location: wali_murid.php?pesan=Wali berhasil dihapus!");
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus wali: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>
