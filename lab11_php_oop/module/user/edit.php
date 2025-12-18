<?php
// 1. PROTEKSI ADMIN
if (!isset($_SESSION['is_login']) || $_SESSION['role'] != 'admin') {
    header("Location: /lab11_php_oop/index.php/home/index");
    exit;
}

$db = new Database();
$id = $_GET['id'] ?? null;

// Proteksi jika ID tidak ada di URL
if (!$id) {
    header("Location: /lab11_php_oop/index.php/user/index");
    exit;
}

// 2. PROSES UPDATE DATA (POST)
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    try {
        // Update Nama & Role menggunakan Prepared Statements (PDO)
        $sql_update = "UPDATE user SET nama = :nama, role = :role WHERE id = :id";
        $params = [
            ':nama' => $nama,
            ':role' => $role,
            ':id'   => $id
        ];
        $db->runQuery($sql_update, $params);

        // Update Password hanya jika kolom password diisi oleh admin
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->runQuery("UPDATE user SET password = :pass WHERE id = :id", [
                ':pass' => $hash,
                ':id'   => $id
            ]);
        }

        echo "<script>alert('Data user berhasil diperbarui!'); window.location.href='/lab11_php_oop/index.php/user/index';</script>";
        exit;
    } catch (Exception $e) {
        $error_msg = "Gagal memperbarui data: " . $e->getMessage();
    }
}

// 3. AMBIL DATA USER (SELECT)
// Menggunakan runQuery (PDO) agar sinkron dengan Database.php baru
$stmt = $db->runQuery("SELECT * FROM user WHERE id = :id", [':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) { 
    echo "<div class='alert alert-danger m-3'>User tidak ditemukan dalam sistem.</div>"; 
    exit; 
}
?>

<div class="row justify-content-center fade-in px-3 mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="m-0 fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>Edit Pengguna</h5>
            </div>
            <div class="card-body p-4">
                <?php if(isset($error_msg)): ?>
                    <div class="alert alert-danger small"><?= $error_msg ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Username (Permanen)</label>
                        <input type="text" class="form-control bg-light border-0" value="<?= htmlspecialchars($data['username']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control bg-light border-0" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Role Akses</label>
                        <select name="role" class="form-select bg-light border-0">
                            <option value="user" <?= $data['role'] == 'user' ? 'selected' : '' ?>>User Biasa</option>
                            <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Administrator</option>
                        </select>
                        <div class="form-text text-warning small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i> Perubahan Role akan berpengaruh pada hak akses menu.
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Reset Password</label>
                        <input type="password" name="password" class="form-control bg-light border-0" placeholder="Isi hanya jika ingin ganti password...">
                        <div class="form-text small text-muted">Biarkan kosong jika tidak ingin mengubah password user.</div>
                    </div>

                    <div class="d-flex justify-content-between gap-2">
                        <a href="/lab11_php_oop/index.php/user/index" class="btn btn-light px-4 rounded-pill fw-bold">Batal</a>
                        <button type="submit" name="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light { background-color: #f8f9fa !important; }
.fade-in { animation: fadeIn 0.5s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>