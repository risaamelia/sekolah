<?php
include 'koneksi.php';

// Periksa apakah ID siswa tersedia di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID siswa tidak ditemukan.");
}

$id_siswa = intval($_GET['id']); // Pastikan ID siswa berupa integer
$pesan = "";

// Ambil data siswa berdasarkan ID
$query = "SELECT * FROM siswa WHERE id_siswa = ?";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Error dalam prepare statement: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

// Ambil data kelas untuk dropdown
$kelas_query = "SELECT * FROM kelas";
$kelas_result = mysqli_query($koneksi, $kelas_query);

// Ambil data wali murid untuk dropdown
$wali_query = "SELECT * FROM wali_murid";
$wali_result = mysqli_query($koneksi, $wali_query);

// Proses form jika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_siswa = htmlspecialchars(trim($_POST['nama_siswa']));
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = htmlspecialchars(trim($_POST['tempat_lahir']));
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $id_kelas = intval($_POST['id_kelas']);
    $id_wali = intval($_POST['id_wali']);

    if (empty($nama_siswa) || empty($jenis_kelamin) || empty($tempat_lahir) || empty($tanggal_lahir)) {
        $pesan = '<div class="alert alert-danger">Semua kolom harus diisi!</div>';
    } else {
        // Debugging: Pastikan query disiapkan dengan benar
        $update_query = "UPDATE siswa SET nama_siswa=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, id_kelas=?, id_wali=? WHERE id_siswa=?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);

        if (!$update_stmt) {
            die("Kesalahan saat menyiapkan statement: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param($update_stmt, "ssssiii", $nama_siswa, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $id_kelas, $id_wali, $id_siswa);

        if (mysqli_stmt_execute($update_stmt)) {
            header("Location: index.php?pesan=Siswa berhasil diperbarui!");
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
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Data Siswa</h2>

        <!-- Menampilkan pesan notifikasi -->
        <?php if ($pesan) echo $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input type="text" class="form-control" name="nama_siswa" value="<?php echo htmlspecialchars($siswa['nama_siswa']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-control" name="jenis_kelamin" required>
                    <option value="L" <?php echo ($siswa['jenis_kelamin'] == "Laki-laki") ? "selected" : ""; ?>>Laki-laki</option>
                    <option value="P" <?php echo ($siswa['jenis_kelamin'] == "Perempuan") ? "selected" : ""; ?>>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" name="tempat_lahir" value="<?php echo htmlspecialchars($siswa['tempat_lahir']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tanggal_lahir" value="<?php echo $siswa['tanggal_lahir']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select class="form-control" name="id_kelas" required>
                    <?php while ($kelas = mysqli_fetch_assoc($kelas_result)) : ?>
                        <option value="<?php echo $kelas['id_kelas']; ?>" <?php echo ($siswa['id_kelas'] == $kelas['id_kelas']) ? "selected" : ""; ?>><?php echo $kelas['nama_kelas']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Wali Murid</label>
                <select class="form-control" name="id_wali" required>
                    <?php while ($wali = mysqli_fetch_assoc($wali_result)) : ?>
                        <option value="<?php echo $wali['id_wali']; ?>" <?php echo ($siswa['id_wali'] == $wali['id_wali']) ? "selected" : ""; ?>><?php echo $wali['nama_wali']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
