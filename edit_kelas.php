<?php
include 'koneksi.php';

// Periksa apakah ID kelas tersedia di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID kelas tidak ditemukan.");
}

$id_kelas = $_GET['id'];
$pesan = "";

// Ambil data kelas berdasarkan ID
$query = "SELECT * FROM kelas WHERE id_kelas = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_kelas);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kelas = mysqli_fetch_assoc($result);

if (!$kelas) {
    die("Kelas tidak ditemukan.");
}

// Proses form jika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));

    if (empty($nama_kelas)) {
        $pesan = '<div class="alert alert-danger">Nama kelas tidak boleh kosong.</div>';
    } else {
        // Update data kelas
        $update_query = "UPDATE kelas SET nama_kelas = ? WHERE id_kelas = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $nama_kelas, $id_kelas);

        if (mysqli_stmt_execute($update_stmt)) {
            $pesan = '<div class="alert alert-success">Kelas berhasil diperbarui!</div>';
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
    <title>Edit Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Kelas</h2>

        <!-- Menampilkan pesan notifikasi -->
        <?php if ($pesan) echo $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="<?php echo htmlspecialchars($kelas['nama_kelas']); ?>" required>
            </div>

            <div class="mb-3">
                <a href="kelas.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
