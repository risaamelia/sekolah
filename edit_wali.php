<?php
include 'koneksi.php';

// Periksa apakah ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Wali tidak ditemukan.");
}

$id_wali = $_GET['id'];
$pesan = "";

// Ambil data wali berdasarkan ID
$query = "SELECT * FROM wali_murid WHERE id_wali = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_wali);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$wali = mysqli_fetch_assoc($result);

if (!$wali) {
    die("Data wali murid tidak ditemukan.");
}

// Proses form jika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_wali = htmlspecialchars(trim($_POST['nama_wali']));
    $kontak = htmlspecialchars(trim($_POST['kontak']));

    // Validasi input
    if (empty($nama_wali) || empty($kontak)) {
        $pesan = '<div class="alert alert-danger">Semua kolom harus diisi!</div>';
    } elseif (!preg_match("/^[0-9]{10,15}$/", $kontak)) {
        $pesan = '<div class="alert alert-warning">Nomor telepon harus berisi 10-15 angka.</div>';
    } else {
        // Update data wali
        $update_query = "UPDATE wali_murid SET nama_wali = ?, kontak = ? WHERE id_wali = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssi", $nama_wali, $kontak, $id_wali);

        if (mysqli_stmt_execute($update_stmt)) {
            header("Location: wali_murid.php?pesan=Wali berhasil diperbarui!");
            exit();
        } else {
            $pesan = '<div class="alert alert-danger">Terjadi kesalahan: ' . mysqli_stmt_error($update_stmt) . '</div>';
        }

        mysqli_stmt_close($update_stmt);
    }
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wali Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Wali Murid</h2>

        <!-- Menampilkan pesan notifikasi -->
        <?php if ($pesan) echo $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama Wali</label>
                <input type="text" class="form-control" name="nama_wali" value="<?php echo htmlspecialchars($wali['nama_wali']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" name="kontak" value="<?php echo htmlspecialchars($wali['kontak']); ?>" required>
            </div>

            <div class="mb-3">
                <a href="wali_murid.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
