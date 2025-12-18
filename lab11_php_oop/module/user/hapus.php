<?php
/**
 * 1. PROTEKSI AKSES (ACL)
 * Memastikan hanya Administrator yang berwenang menghapus akun.
 */
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: /lab11_php_oop/index.php/home/index");
    exit;
}

$db = new Database();
// Pastikan ID adalah angka untuk keamanan tambahan
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    header("Location: /lab11_php_oop/index.php/user/index");
    exit;
}

/**
 * 2. PROTEKSI DIRI SENDIRI
 * Mencegah admin menghapus akunnya sendiri yang sedang digunakan.
 */
if ($id === (int)$_SESSION['user_id']) {
    echo "<script>alert('Kesalahan: Anda tidak diizinkan menghapus akun sendiri!'); window.location.href='/lab11_php_oop/index.php/user/index';</script>";
    exit;
}

try {
    /**
     * 3. AMBIL DATA USER (CEK FOTO)
     * Mengambil path foto sebelum data di database dimusnahkan.
     */
    $stmt = $db->runQuery("SELECT foto FROM user WHERE id = :id", [':id' => $id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        /**
         * 4. HAPUS FILE FISIK FOTO PROFIL (Cleanup)
         * Menghapus gambar dari folder assets agar tidak jadi sampah.
         */
        if (!empty($user_data['foto'])) {
            // Ubah path URL (/lab11_php_oop/assets/...) menjadi path sistem file
            $relative_path = str_replace("/lab11_php_oop/", "", $user_data['foto']);
            $full_file_path = __DIR__ . "/../../" . $relative_path;

            if (file_exists($full_file_path)) {
                unlink($full_file_path); // Hapus file fisik
            }
        }

        /**
         * 5. HAPUS DATA DARI DATABASE
         * Menghapus record pengguna secara permanen.
         */
        $sql_delete = "DELETE FROM user WHERE id = :id";
        if ($db->runQuery($sql_delete, [':id' => $id])) {
            echo "<script>alert('Akun pengguna dan file fotonya berhasil dihapus!'); window.location.href='/lab11_php_oop/index.php/user/index';</script>";
            exit;
        }
    } else {
        // Jika ID tidak ditemukan di database
        header("Location: /lab11_php_oop/index.php/user/index");
    }

} catch (Exception $e) {
    /**
     * Penanganan Error Database
     * Sembunyikan pesan teknis yang sensitif bagi user.
     */
    die("<div style='text-align:center; padding-top:50px; font-family:sans-serif;'>
            <h3 style='color:red;'>Gagal menghapus data.</h3>
            <p>Terjadi kesalahan pada instruksi database.</p>
            <a href='/lab11_php_oop/index.php/user/index'>Kembali ke Daftar User</a>
         </div>");
}