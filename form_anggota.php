<?php
// Inisialisasi variabel
$nama = $email = $telepon = $alamat = $jk = $tgl_lahir = $pekerjaan = "";
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $telepon = trim($_POST["telepon"]);
    $alamat = trim($_POST["alamat"]);
    $jk = $_POST["jk"] ?? "";
    $tgl_lahir = $_POST["tgl_lahir"];
    $pekerjaan = $_POST["pekerjaan"];

    // VALIDASI

    // Nama
    if (empty($nama)) {
        $errors["nama"] = "Nama wajib diisi";
    } elseif (strlen($nama) < 3) {
        $errors["nama"] = "Minimal 3 karakter";
    }

    // Email
    if (empty($email)) {
        $errors["email"] = "Email wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Format email tidak valid";
    }

    // Telepon
    if (empty($telepon)) {
        $errors["telepon"] = "Telepon wajib diisi";
    } elseif (!preg_match("/^08[0-9]{8,11}$/", $telepon)) {
        $errors["telepon"] = "Format harus 08xxxxxxxxxx (10-13 digit)";
    }

    // Alamat
    if (empty($alamat)) {
        $errors["alamat"] = "Alamat wajib diisi";
    } elseif (strlen($alamat) < 10) {
        $errors["alamat"] = "Minimal 10 karakter";
    }

    // Jenis Kelamin
    if (empty($jk)) {
        $errors["jk"] = "Pilih jenis kelamin";
    }

    // Tanggal Lahir & Umur
    if (empty($tgl_lahir)) {
        $errors["tgl_lahir"] = "Tanggal lahir wajib diisi";
    } else {
        $today = new DateTime();
        $birth = new DateTime($tgl_lahir);
        $umur = $today->diff($birth)->y;

        if ($umur < 10) {
            $errors["tgl_lahir"] = "Umur minimal 10 tahun";
        }
    }

    // Pekerjaan
    if (empty($pekerjaan)) {
        $errors["pekerjaan"] = "Pilih pekerjaan";
    }

    // Jika tidak ada error
    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Form Registrasi Anggota</h3>

    <?php if ($success): ?>
        <div class="alert alert-success">Registrasi berhasil!</div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($nama) ?></h5>
                <p>Email: <?= htmlspecialchars($email) ?></p>
                <p>Telepon: <?= htmlspecialchars($telepon) ?></p>
                <p>Alamat: <?= htmlspecialchars($alamat) ?></p>
                <p>Jenis Kelamin: <?= htmlspecialchars($jk) ?></p>
                <p>Tanggal Lahir: <?= htmlspecialchars($tgl_lahir) ?></p>
                <p>Pekerjaan: <?= htmlspecialchars($pekerjaan) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-4">

        <!-- Nama -->
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($nama) ?>">
            <div class="invalid-feedback"><?= $errors['nama'] ?? '' ?></div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label>Email</label>
            <input type="text" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($email) ?>">
            <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
        </div>

        <!-- Telepon -->
        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($telepon) ?>">
            <div class="invalid-feedback"><?= $errors['telepon'] ?? '' ?></div>
        </div>

        <!-- Alamat -->
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($alamat) ?></textarea>
            <div class="invalid-feedback"><?= $errors['alamat'] ?? '' ?></div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="mb-3">
            <label>Jenis Kelamin</label><br>
            <input type="radio" name="jk" value="Laki-laki" <?= $jk == "Laki-laki" ? "checked" : "" ?>> Laki-laki
            <input type="radio" name="jk" value="Perempuan" <?= $jk == "Perempuan" ? "checked" : "" ?>> Perempuan
            <div class="text-danger"><?= $errors['jk'] ?? '' ?></div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control <?= isset($errors['tgl_lahir']) ? 'is-invalid' : '' ?>"
                   value="<?= $tgl_lahir ?>">
            <div class="invalid-feedback"><?= $errors['tgl_lahir'] ?? '' ?></div>
        </div>

        <!-- Pekerjaan -->
        <div class="mb-3">
            <label>Pekerjaan</label>
            <select name="pekerjaan" class="form-control <?= isset($errors['pekerjaan']) ? 'is-invalid' : '' ?>">
                <option value="">-- Pilih --</option>
                <option <?= $pekerjaan == "Pelajar" ? "selected" : "" ?>>Pelajar</option>
                <option <?= $pekerjaan == "Mahasiswa" ? "selected" : "" ?>>Mahasiswa</option>
                <option <?= $pekerjaan == "Pegawai" ? "selected" : "" ?>>Pegawai</option>
                <option <?= $pekerjaan == "Lainnya" ? "selected" : "" ?>>Lainnya</option>
            </select>
            <div class="invalid-feedback"><?= $errors['pekerjaan'] ?? '' ?></div>
        </div>

        <button type="submit" class="btn btn-primary">Daftar</button>

    </form>
</div>

</body>
</html>