<?php
// index.php
include "config.php";

// Variabel untuk pesan sukses / gagal
$alert = "";

// ====== PROSES TAMBAH DATA ======
if (isset($_POST['simpan'])) {
    $kode   = mysqli_real_escape_string($conn, $_POST['kode_rute']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_rute']);
    $berangkat = mysqli_real_escape_string($conn, $_POST['titik_berangkat']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['titik_tujuan']);
    $status = mysqli_real_escape_string($conn, $_POST['status_rute']);

    $sql = "INSERT INTO rute (kode_rute, nama_rute, titik_berangkat, titik_tujuan, status_rute)
            VALUES ('$kode', '$nama', '$berangkat', '$tujuan', '$status')";
    if (mysqli_query($conn, $sql)) {
        $alert = '<div class="alert alert-success mt-2">Data rute berhasil ditambahkan.</div>';
    } else {
        $alert = '<div class="alert alert-danger mt-2">Gagal menambah data: '.mysqli_error($conn).'</div>';
    }
}

// ====== PROSES UPDATE DATA ======
if (isset($_POST['update'])) {
    $id     = (int) $_POST['rute_id'];
    $kode   = mysqli_real_escape_string($conn, $_POST['kode_rute']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_rute']);
    $berangkat = mysqli_real_escape_string($conn, $_POST['titik_berangkat']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['titik_tujuan']);
    $status = mysqli_real_escape_string($conn, $_POST['status_rute']);

    $sql = "UPDATE rute SET 
                kode_rute='$kode',
                nama_rute='$nama',
                titik_berangkat='$berangkat',
                titik_tujuan='$tujuan',
                status_rute='$status'
            WHERE rute_id=$id";

    if (mysqli_query($conn, $sql)) {
        $alert = '<div class="alert alert-success mt-2">Data rute berhasil diubah.</div>';
    } else {
        $alert = '<div class="alert alert-danger mt-2">Gagal mengubah data: '.mysqli_error($conn).'</div>';
    }
}

// ====== PROSES HAPUS DATA (setelah konfirmasi) ======
if (isset($_POST['hapus'])) {
    $id = (int) $_POST['rute_id'];
    $sql = "DELETE FROM rute WHERE rute_id=$id";
    if (mysqli_query($conn, $sql)) {
        $alert = '<div class="alert alert-success mt-2">Data rute berhasil dihapus.</div>';
    } else {
        $alert = '<div class="alert alert-danger mt-2">Gagal menghapus data: '.mysqli_error($conn).'</div>';
    }
}

// Menentukan halaman yang aktif
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Hitung jumlah rute untuk dashboard
$totalRute = 0;
$resCount = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM rute");
if ($rowCount = mysqli_fetch_assoc($resCount)) {
    $totalRute = $rowCount['jml'];
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Aplikasi Shuttle Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
        Shuttle Kampus
    </a>
  </div>
</nav>

<div class="container mt-4 mb-5">

    <!-- Menu Tabs -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link <?php if($page=='dashboard') echo 'active'; ?>"
               href="index.php?page=dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($page=='tampil') echo 'active'; ?>"
               href="index.php?page=tampil">Tampil Data Rute</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($page=='tambah') echo 'active'; ?>"
               href="index.php?page=tambah">Tambah Data Rute</a>
        </li>
    </ul>

    <!-- Tempat tampil pesan -->
    <?php echo $alert; ?>

    <?php if ($page == 'dashboard'): ?>

        <!-- ===== DASHBOARD ===== -->
        <div class="row g-3">
            <div class="col-md-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang 👋</h5>
                        <p class="card-text small text-muted">
                            Aplikasi ini merupakan sistem sederhana untuk mengelola data 
                            <strong>Rute Shuttle Kampus</strong>.
                        </p>
                        <ul>
                            <li>Menu <strong>Tampil Data Rute</strong> untuk melihat data rute.</li>
                            <li>Menu <strong>Tambah Data Rute</strong> untuk menambah rute baru.</li>
                            <li>Tombol <strong>Ubah</strong> dan <strong>Hapus</strong> pada tabel untuk mengelola data.</li>
                        </ul>
                        <p class="mb-0">
                            Silakan gunakan menu di atas sesuai kebutuhan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card text-bg-success shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Total Rute Terdaftar</h5>
                        <p class="display-4 mb-1 fw-bold text-center"><?php echo $totalRute; ?></p>
                        <p class="text-center mb-0 small">Rute aktif maupun non-aktif yang tersimpan di sistem</p>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($page == 'tampil'): ?>

        <!-- ===== LAYAR TAMPIL DATA ===== -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Data Rute Shuttle Kampus</h4>
            <a href="index.php?page=tambah" class="btn btn-sm btn-primary">+ Tambah Rute</a>
        </div>
        <div class="table-responsive card shadow-sm">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Rute</th>
                        <th>Nama Rute</th>
                        <th>Titik Berangkat</th>
                        <th>Titik Tujuan</th>
                        <th>Status Rute</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $result = mysqli_query($conn, "SELECT * FROM rute ORDER BY rute_id DESC");
                if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            Belum ada data rute. Silakan tambahkan data baru.
                        </td>
                    </tr>
                <?php
                else:
                    while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['kode_rute']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_rute']); ?></td>
                        <td><?php echo htmlspecialchars($row['titik_berangkat']); ?></td>
                        <td><?php echo htmlspecialchars($row['titik_tujuan']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($row['status_rute']=='aktif')?'success':'secondary'; ?>">
                                <?php echo $row['status_rute']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?page=edit&id=<?php echo $row['rute_id']; ?>"
                               class="btn btn-sm btn-warning">Ubah</a>
                            <a href="index.php?page=hapus&id=<?php echo $row['rute_id']; ?>"
                               class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                endif;
                ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($page == 'tambah'): ?>

        <!-- ===== LAYAR TAMBAH DATA ===== -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Tambah Data Rute</h4>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Kode Rute</label>
                        <input type="text" name="kode_rute" class="form-control" placeholder="CILEDUG-01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Rute</label>
                        <input type="text" name="nama_rute" class="form-control" placeholder="Ciledug - Kampus Utama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titik Berangkat</label>
                        <input type="text" name="titik_berangkat" class="form-control" placeholder="Lokasi Awal" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titik Tujuan</label>
                        <input type="text" name="titik_tujuan" class="form-control" placeholder="Lokasi Tujuan Akhir" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Rute</label>
                        <select name="status_rute" class="form-select" required>
                            <option value="aktif">aktif</option>
                            <option value="non-aktif">non-aktif</option>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">Submit (Simpan)</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>

    <?php elseif ($page == 'edit'): ?>

        <!-- ===== LAYAR UBAH DATA ===== -->
        <?php
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $result = mysqli_query($conn, "SELECT * FROM rute WHERE rute_id=$id");
        $data = mysqli_fetch_assoc($result);
        if (!$data): ?>
            <div class="alert alert-warning">Data tidak ditemukan.</div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Ubah Data Rute</h4>
                    <form method="post" action="">
                        <input type="hidden" name="rute_id" value="<?php echo $data['rute_id']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Kode Rute</label>
                            <input type="text" name="kode_rute" class="form-control"
                                   value="<?php echo htmlspecialchars($data['kode_rute']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Rute</label>
                            <input type="text" name="nama_rute" class="form-control"
                                   value="<?php echo htmlspecialchars($data['nama_rute']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Titik Berangkat</label>
                            <input type="text" name="titik_berangkat" class="form-control"
                                   value="<?php echo htmlspecialchars($data['titik_berangkat']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Titik Tujuan</label>
                            <input type="text" name="titik_tujuan" class="form-control"
                                   value="<?php echo htmlspecialchars($data['titik_tujuan']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Rute</label>
                            <select name="status_rute" class="form-select" required>
                                <option value="aktif" <?php if($data['status_rute']=='aktif') echo 'selected'; ?>>aktif</option>
                                <option value="non-aktif" <?php if($data['status_rute']=='non-aktif') echo 'selected'; ?>>non-aktif</option>
                            </select>
                        </div>

                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="index.php?page=tampil" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page == 'hapus'): ?>

        <!-- ===== LAYAR HAPUS DATA (KONFIRMASI) ===== -->
        <?php
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $result = mysqli_query($conn, "SELECT * FROM rute WHERE rute_id=$id");
        $data = mysqli_fetch_assoc($result);
        if (!$data): ?>
            <div class="alert alert-warning">Data tidak ditemukan.</div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3 text-danger">Hapus Data Rute</h4>
                    <div class="alert alert-warning">
                        Apakah Anda yakin ingin menghapus rute:
                        <strong><?php echo htmlspecialchars($data['kode_rute']." - ".$data['nama_rute']); ?></strong> ?
                    </div>
                    <form method="post" action="">
                        <input type="hidden" name="rute_id" value="<?php echo $data['rute_id']; ?>">
                        <button type="submit" name="hapus" class="btn btn-danger">Ya, Hapus</button>
                        <a href="index.php?page=tampil" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>
</body>
</html>
