<?php
require_once 'config/database.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $karyawan_id = $_POST['karyawan_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO data_karyawan (karyawan_id, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$karyawan_id, $username, $password]);
        $success = "Akun karyawan berhasil dibuat!";
    } catch (PDOException $e) {
        $error = "Gagal membuat akun. Username mungkin sudah digunakan.";
    }
}

// Dapatkan data karyawan
$stmt = $conn->query("
    SELECT k.* FROM karyawan k
    LEFT JOIN data_karyawan ec ON k.id = ec.karyawan_id
    WHERE ec.id IS NULL AND k.status = 'aktif'
");
$available_employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Akun Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Tambah Akun Karyawan</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Karyawan</label>
                        <select name="karyawan_id" class="form-control" required>
                            <option value="">Pilih Karyawan...</option>
                            <?php foreach ($available_employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buat Akun</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>