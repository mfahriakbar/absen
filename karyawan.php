<?php
require_once 'config/database.php';

// Ambil semua karyawan menggunakan PDO
$query = "SELECT * FROM karyawan";
$stmt = $conn->query($query);
$karyawan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menangani pengiriman formulir
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'add') {
        $nik = $_POST['nik'];  // Ambil NIK dari input form
        $nama = $_POST['nama_lengkap'];
        $gender = $_POST['jenis_kelamin'];
        $jabatan = $_POST['jabatan'];
        $email = $_POST['email'];
        $telp = $_POST['no_telp'];
        $alamat = $_POST['alamat'];
        $tgl_masuk = $_POST['tanggal_masuk'];

        // Mempersiapkan dan mengeksekusi pernyataan penyisipan menggunakan PDO
        $query = "INSERT INTO karyawan (nik, nama_lengkap, jenis_kelamin, jabatan, email, no_telp, alamat, tanggal_masuk, status) 
                  VALUES (:nik, :nama, :gender, :jabatan, :email, :telp, :alamat, :tgl_masuk, 'aktif')";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':nik' => $nik,
            ':nama' => $nama,
            ':gender' => $gender,
            ':jabatan' => $jabatan,
            ':email' => $email,
            ':telp' => $telp,
            ':alamat' => $alamat,
            ':tgl_masuk' => $tgl_masuk
        ]);
        header('Location: karyawan.php');
    }

    if ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $nik = $_POST['nik'];  // Ambil NIK dari input form
        $nama = $_POST['nama_lengkap'];
        $gender = $_POST['jenis_kelamin'];
        $jabatan = $_POST['jabatan'];
        $email = $_POST['email'];
        $telp = $_POST['no_telp'];
        $alamat = $_POST['alamat'];
        $status = $_POST['status'];

        // Mempersiapkan dan menjalankan pernyataan pembaruan menggunakan PDO
        $query = "UPDATE karyawan SET 
                  nik=:nik, 
                  nama_lengkap=:nama, 
                  jenis_kelamin=:gender, 
                  jabatan=:jabatan, 
                  email=:email, 
                  no_telp=:telp, 
                  alamat=:alamat, 
                  status=:status 
                  WHERE id=:id";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':nik' => $nik,
            ':nama' => $nama,
            ':gender' => $gender,
            ':jabatan' => $jabatan,
            ':email' => $email,
            ':telp' => $telp,
            ':alamat' => $alamat,
            ':status' => $status
        ]);
        header('Location: karyawan.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Data Karyawan</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            Tambah Karyawan
        </button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($karyawan as $k):
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($k['nik']); ?></td>
                        <td><?php echo htmlspecialchars($k['nama_lengkap']); ?></td>
                        <td><?php echo $k['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td><?php echo htmlspecialchars($k['jabatan']); ?></td>
                        <td><?php echo htmlspecialchars($k['email']); ?></td>
                        <td><?php echo htmlspecialchars($k['no_telp']); ?></td>
                        <td><?php echo htmlspecialchars($k['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($k['status']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $k['id']; ?>">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Tambah Karyawan -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="no_telp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Karyawan -->
    <?php foreach ($karyawan as $k): ?>
        <div class="modal fade" id="editModal<?php echo $k['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo $k['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" value="<?php echo htmlspecialchars($k['nik']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($k['nama_lengkap']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="L" <?php echo ($k['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?php echo ($k['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" value="<?php echo htmlspecialchars($k['jabatan']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($k['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" name="no_telp" class="form-control" value="<?php echo htmlspecialchars($k['no_telp']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" required><?php echo htmlspecialchars($k['alamat']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif" <?php echo ($k['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="tidak aktif" <?php echo ($k['status'] == 'tidak aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>