<?php
include 'koneksi.php';

// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pesan = ""; // Variabel untuk menampung pesan notifikasi

// Proses saat form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama_kelas'])) {
    $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));

    // Validasi input
    if (empty($nama_kelas)) {
        $pesan = '<div class="alert alert-danger">Nama kelas tidak boleh kosong.</div>';
    } elseif (strlen($nama_kelas) < 3) {
        $pesan = '<div class="alert alert-warning">Nama kelas minimal harus 3 karakter.</div>';
    } else {
        // Cek apakah nama kelas sudah ada
        $check_query = "SELECT nama_kelas FROM kelas WHERE nama_kelas = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $nama_kelas);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $pesan = '<div class="alert alert-warning">Nama kelas sudah ada!</div>';
        } else {
            // Query untuk menambahkan data kelas
            $query = "INSERT INTO kelas (nama_kelas) VALUES (?)";
            $stmt = mysqli_prepare($koneksi, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $nama_kelas);

                try {
                    // Eksekusi query
                    if (mysqli_stmt_execute($stmt)) {
                        $pesan = '<div class="alert alert-success">Kelas berhasil ditambahkan!</div>';
                        // Reset form setelah berhasil
                        $_POST['nama_kelas'] = '';
                    } else {
                        throw new Exception(mysqli_stmt_error($stmt));
                    }
                } catch (Exception $e) {
                    $pesan = '<div class="alert alert-danger">Terjadi kesalahan: ' . $e->getMessage() . '</div>';
                }

                mysqli_stmt_close($stmt);
            } else {
                $pesan = '<div class="alert alert-danger">Kesalahan dalam query: ' . mysqli_error($koneksi) . '</div>';
            }
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Kelas Baru</h2>

        <!-- Menampilkan pesan notifikasi -->
        <?php if ($pesan) echo $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" 
                       value="<?php echo isset($_POST['nama_kelas']) ? htmlspecialchars($_POST['nama_kelas']) : ''; ?>" 
                       required>
            </div>

            <div class="mb-3">
                <!-- Mengarahkan kembali ke kelas.php -->
                <a href="kelas.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
