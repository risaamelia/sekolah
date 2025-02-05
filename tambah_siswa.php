<?php
include 'koneksi.php';

// Ambil data kelas untuk dropdown
$query_kelas = "SELECT * FROM kelas";
$result_kelas = mysqli_query($koneksi, $query_kelas);

// Ambil data wali murid untuk dropdown
$query_wali = "SELECT * FROM wali_murid";
$result_wali = mysqli_query($koneksi, $query_wali);

// Proses pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $id_kelas = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $id_wali = mysqli_real_escape_string($koneksi, $_POST['id_wali']);

    // Validasi NIS (misalnya, hanya angka)
    if (!preg_match("/^[0-9]+$/", $nis)) {
        echo "NIS hanya boleh berisi angka.";
    } else {
        // Siapkan query SQL
        $query = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, tempat_lahir, tanggal_lahir, id_kelas, id_wali) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Siapkan pernyataan untuk mencegah SQL injection
        if ($stmt = mysqli_prepare($koneksi, $query)) {
            // Ikat parameter
            mysqli_stmt_bind_param($stmt, "ssssssi", $nis, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $id_kelas, $id_wali);
            
            // Eksekusi query
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Siswa Baru</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" required>
            </div>

            <div class="mb-3">
                <label for="nama_siswa" class="form-label">Nama Siswa</label>
                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>

            <div class="mb-3">
                <label for="id_kelas" class="form-label">Kelas</label>
                <select class="form-select" id="id_kelas" name="id_kelas" required>
                    <option value="">Pilih Kelas</option>
                    <?php while ($kelas = mysqli_fetch_assoc($result_kelas)) : ?>
                        <option value="<?php echo $kelas['id_kelas']; ?>">
                            <?php echo $kelas['nama_kelas']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_wali" class="form-label">Wali Murid</label>
                <select class="form-select" id="id_wali" name="id_wali" required>
                    <option value="">Pilih Wali Murid</option>
                    <?php while ($wali = mysqli_fetch_assoc($result_wali)) : ?>
                        <option value="<?php echo $wali['id_wali']; ?>">
                            <?php echo $wali['nama_wali']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
