<?php
require_once 'config/database.php';
checkLogin();

// Menangani pengiriman formulir
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $stmt = $conn->prepare("INSERT INTO absensi (karyawan_id, tanggal, jam_masuk, jam_keluar, status, keterangan) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['karyawan_id'],
                $_POST['tanggal'],
                $_POST['jam_masuk'],
                $_POST['jam_keluar'],
                $_POST['status'],
                $_POST['keterangan']
            ]);
        }
    }
    header("Location: absensi.php");
    exit();
}

// Dapatkan data kehadiran
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$stmt = $conn->prepare("
    SELECT a.*, k.nama_lengkap, k.nik 
    FROM absensi a 
    JOIN karyawan k ON a.karyawan_id = k.id 
    WHERE a.tanggal = ? 
    ORDER BY k.nama_lengkap
");
$stmt->execute([$tanggal]);
$absensi = $stmt->fetchAll();

// Dapatkan karyawan aktif untuk dropdown
$stmt = $conn->query("SELECT id, nama_lengkap, nik FROM karyawan WHERE status = 'aktif' ORDER BY nama_lengkap");
$karyawan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi - Sistem Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Data Absensi</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="date" name="tanggal" class="form-control me-2" value="<?php echo $tanggal; ?>">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Tambah Absensi
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($absensi as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['nik']); ?></td>
                            <td><?php echo htmlspecialchars($a['nama_lengkap']); ?></td>
                            <td><?php echo $a['jam_masuk']; ?></td>
                            <td><?php echo htmlspecialchars($a['status']); ?></td>
                            <td><?php echo htmlspecialchars($a['keterangan']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tambah Kehadiran -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Karyawan</label>
                            <select name="karyawan_id" class="form-control" required>
                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($karyawan as $k): ?>
                                    <option value="<?php echo $k['id']; ?>">
                                        <?php echo htmlspecialchars($k['nik'] . ' - ' . $k['nama_lengkap']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alfa">Alfa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>