<?php
include 'koneksi.php';

// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pesan = ""; // Variabel untuk menyimpan pesan error atau sukses

// Proses pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama_wali'], $_POST['nomor_telepon'])) {
    $nama_wali = htmlspecialchars(trim($_POST['nama_wali']));
    $nomor_telepon = htmlspecialchars(trim($_POST['nomor_telepon']));

    // Validasi format nomor telepon (hanya angka, panjang 10-15)
    if (!preg_match("/^[0-9]{10,15}$/", $nomor_telepon)) {
        $pesan = '<div class="alert alert-danger">Nomor telepon tidak valid. Harap masukkan angka dengan panjang 10-15 digit.</div>';
    } else {
        $query = "INSERT INTO wali_murid (nama_wali, kontak) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $nama_wali, $nomor_telepon);
            
            // Eksekusi query
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($koneksi);
                header("Location: wali_murid.php");
                exit();
            } else {
                $pesan = '<div class="alert alert-danger">Gagal menyimpan data: ' . mysqli_stmt_error($stmt) . '</div>';
            }

            mysqli_stmt_close($stmt);
        } else {
            $pesan = '<div class="alert alert-danger">Kesalahan pada query: ' . mysqli_error($koneksi) . '</div>';
        }
    }
}

// Tutup koneksi database setelah semua proses selesai
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Wali Murid</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container mt-4">
       <h2>Tambah Wali Murid Baru</h2>

       <!-- Menampilkan pesan sukses atau error -->
       <?php if ($pesan) echo $pesan; ?>

       <form method="POST" action="">
           <div class="mb-3">
               <label for="nama_wali" class="form-label">Nama Wali</label>
               <input type="text" class="form-control" id="nama_wali" name="nama_wali" required>
           </div>

           <div class="mb-3">
               <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
               <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" pattern="[0-9]{10,15}" required>
               <small class="form-text text-muted">Nomor telepon hanya boleh berisi angka dengan panjang antara 10 hingga 15 digit.</small>
           </div>

           <div class="mb-3">
               <a href="wali_murid.php" class="btn btn-secondary">Kembali</a>
               <button type="submit" class="btn btn-primary">Simpan</button>
           </div>
       </form>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
