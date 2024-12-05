<?php
require_once 'config/database.php';
checkLogin();

// Mengambil statistik
$stmt = $conn->query("SELECT COUNT(*) as total_karyawan FROM karyawan WHERE status = 'aktif'");
$totalKaryawan = $stmt->fetch()['total_karyawan'];

$today = date('Y-m-d');
$stmt = $conn->query("SELECT COUNT(*) as hadir_hari_ini FROM absensi WHERE tanggal = '$today' AND status = 'hadir'");
$hadirHariIni = $stmt->fetch()['hadir_hari_ini'];

$stmt = $conn->query("SELECT COUNT(*) as izin_hari_ini FROM absensi WHERE tanggal = '$today' AND status IN ('izin', 'sakit')");
$izinHariIni = $stmt->fetch()['izin_hari_ini'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Absensi Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Karyawan Aktif</h5>
                        <h2><?php echo $totalKaryawan; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Hadir Hari Ini</h5>
                        <h2><?php echo $hadirHariIni; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Izin/Sakit Hari Ini</h5>
                        <h2><?php echo $izinHariIni; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>