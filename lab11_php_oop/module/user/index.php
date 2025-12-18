<?php
/**
 * 1. PROTEKSI AKSES (ACL)
 * Memastikan keamanan sistem dengan membatasi akses hanya untuk Administrator.
 */
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak: Anda tidak memiliki izin!'); window.location.href='/lab11_php_oop/index.php/home/index';</script>";
    exit;
}

$db = new Database();

/**
 * 2. AMBIL DATA PENGGUNA (PDO)
 * Mengambil data lengkap termasuk kolom 'foto' yang baru kita tambahkan.
 */
try {
    // Query menggunakan PDO untuk keamanan dari SQL Injection.
    $stmt = $db->runQuery("SELECT * FROM user ORDER BY role ASC, nama ASC");
    $data_user = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Sembunyikan detail teknis database.
    die("<div class='alert alert-danger m-3'>Terjadi kesalahan pada instruksi database.</div>");
}
?>

<div class="fade-in px-3">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-primary fw-bold"><i class="fas fa-users-cog me-2"></i>Kelola Pengguna</h1>
            <p class="text-muted small mb-0">Manajemen hak akses dan kontrol akun sistem.</p>
        </div>
        <a href="/lab11_php_oop/index.php/user/tambah" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold transition-scale">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="p-4 bg-white border-bottom">
                <div class="input-group bg-light rounded-pill px-3 py-1" style="max-width: 350px;">
                    <span class="input-group-text bg-transparent border-0 text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchUser" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari nama atau username...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableUser">
                    <thead class="bg-light text-uppercase small fw-bold text-muted">
                        <tr>
                            <th class="ps-4 py-3">Informasi Pengguna</th>
                            <th class="py-3">Kontak</th>
                            <th class="py-3">Role</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(empty($data_user)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data pengguna tersedia.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($data_user as $row): ?>
                            <tr class="transition">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <?php 
                                            $foto = !empty($row['foto']) ? $row['foto'] : "https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=4e73df&color=fff&size=128";
                                        ?>
                                        <img src="<?= $foto ?>" class="rounded-circle me-3 border shadow-sm" width="48" height="48" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama']) ?></div>
                                            <div class="small text-muted">@<?= htmlspecialchars($row['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-dark mb-1"><i class="far fa-envelope me-2 text-muted"></i><?= htmlspecialchars($row['email'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-phone-alt me-2 text-muted"></i><?= htmlspecialchars($row['no_hp'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <?php if($row['role'] == 'admin'): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.7rem;">
                                            <i class="fas fa-shield-alt me-1"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.7rem;">
                                            <i class="fas fa-user me-1"></i> User
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($row['username'] != $_SESSION['username']): ?>
                                        <div class="btn-group">
                                            <a href="/lab11_php_oop/index.php/user/edit?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle me-2 border-0" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/lab11_php_oop/index.php/user/hapus?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle border-0" onclick="return confirm('Hapus user <?= $row['username'] ?> secara permanen?')" title="Hapus User">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a href="/lab11_php_oop/index.php/user/profil" class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold text-decoration-none small">
                                            <i class="fas fa-user-circle me-1"></i> Profil Saya
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Logic Pencarian Real-time (Client Side) untuk kemudahan navigasi.
 */
document.getElementById('searchUser').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableUser tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<style>
.transition { transition: all 0.2s ease; }
.transition-scale { transition: all 0.3s ease; }
.transition-scale:hover { transform: translateY(-2px); }
.table-hover tbody tr:hover { background-color: rgba(78, 115, 223, 0.03); }
.fade-in { animation: fadeIn 0.5s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>