<?php
include 'koneksi.php';

// Ambil data siswa
$query = "SELECT siswa.*, kelas.nama_kelas, wali_murid.nama_wali FROM siswa 
          LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
          LEFT JOIN wali_murid ON siswa.id_wali = wali_murid.id_wali";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Data Siswa</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="kelas.php" class="btn btn-primary">Kelola Kelas</a>
                <a href="wali_murid.php" class="btn btn-primary">Kelola Wali Murid</a>
            </div>
            <a href="tambah_siswa.php" class="btn btn-success">Tambah Siswa</a>
        </div>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Kelas</th>
                    <th>Wali Murid</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['nis']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['jenis_kelamin']; ?></td>
                    <td><?php echo $row['tempat_lahir']; ?></td>
                    <td><?php echo $row['tanggal_lahir']; ?></td>
                    <td><?php echo $row['nama_kelas']; ?></td>
                    <td><?php echo $row['nama_wali']; ?></td>
                    <td>
                        <a href="edit_siswa.php?id=<?php echo $row['id_siswa']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus_siswa.php?id=<?php echo $row['id_siswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>