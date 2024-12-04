<?php
require_once 'config/database.php';

// Set zona waktu Jakarta
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: login.php");
    exit();
}

$karyawan_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT * FROM absensi WHERE karyawan_id = ? AND tanggal = ?");
$stmt->execute([$karyawan_id, $today]);
$existing = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$existing) {
        $status = $_POST['status'];
        $keterangan = $_POST['keterangan'] ?? '';
        $jam = date('H:i:s'); // Jam sesuai dengan zona waktu Jakarta

        $stmt = $conn->prepare("INSERT INTO absensi (karyawan_id, tanggal, jam_masuk, status, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$karyawan_id, $today, $jam, $status, $keterangan]);

        header("Location: absen.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Absensi</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Absensi Harian</h3>
                        <small>Tanggal: <?php echo date('d-m-Y'); ?></small>
                    </div>
                    <div class="card-body">
                        <?php if ($existing): ?>
                            <div class="alert alert-info">
                                <p>Anda sudah melakukan absensi hari ini:</p>
                                <p>Status: <?php echo ucfirst($existing['status']); ?></p>
                                <p>Jam: <?php echo date('H:i', strtotime($existing['jam_masuk'])); ?></p>
                                <?php if ($existing['keterangan']): ?>
                                    <p>Keterangan: <?php echo $existing['keterangan']; ?></p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Status Kehadiran</label>
                                    <select name="status" class="form-control" required>
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan (opsional)</label>
                                    <textarea name="keterangan" class="form-control"
                                        placeholder="Masukkan keterangan jika izin atau sakit"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Absensi</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>