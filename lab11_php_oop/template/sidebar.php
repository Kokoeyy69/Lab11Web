<?php
/**
 * 1. INISIALISASI VARIABEL GLOBAL
 * Diambil dari index.php utama untuk mendeteksi lokasi modul saat ini.
 */
global $mod, $page;

/**
 * 2. FUNGSI DETEKSI MENU AKTIF
 * Memberikan gaya visual khusus pada menu yang sedang dikunjungi.
 */
function isActive($targetMod, $targetPage) {
    global $mod, $page;
    // Logika Presisi: Aktif jika Modul DAN Halaman cocok
    if ($mod == $targetMod && $page == $targetPage) { 
        return 'active-menu shadow-sm';
    } 
    return 'text-secondary hover-menu';
}
?>

<div class="sidebar d-flex flex-column flex-shrink-0 bg-white shadow-sm h-100 border-end" 
     style="width: 260px; transition: all 0.3s ease;">

    <div class="sidebar-brand d-flex align-items-center justify-content-center py-4 px-3 border-bottom mb-2">
        <div class="logo-wrapper bg-primary bg-opacity-10 p-2 rounded-3 me-2 logo-anim">
            <i class="fas fa-cube text-primary fa-lg"></i>
        </div>
        <span class="fs-5 fw-bold text-dark" style="letter-spacing: 1px;">MODULAR</span>
    </div>
    
    <div class="list-group list-group-flush px-3 py-3 flex-grow-1 gap-1" style="overflow-y: auto;">
        
        <div class="nav-label mb-2 ms-2 mt-2">UTAMA</div>
        
        <a href="/lab11_php_oop/index.php/home/index" class="list-group-item list-group-item-action py-2 rounded-3 border-0 d-flex align-items-center <?= isActive('home', 'index') ?>">
            <i class="fas fa-tachometer-alt icon-box text-center"></i> 
            <span class="ms-1">Dashboard</span>
        </a>
        
        <a href="/lab11_php_oop/index.php/artikel/list" class="list-group-item list-group-item-action py-2 rounded-3 border-0 d-flex align-items-center <?= isActive('artikel', 'list') ?>">
            <i class="fas fa-newspaper icon-box text-center"></i> 
            <span class="ms-1">Berita</span>
        </a>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <div class="nav-label mb-2 mt-4 ms-2">ADMINISTRATOR</div>
            
            <a href="/lab11_php_oop/index.php/artikel/index" class="list-group-item list-group-item-action py-2 rounded-3 border-0 d-flex align-items-center <?= isActive('artikel', 'index') ?>">
                <i class="fas fa-edit icon-box text-center"></i> 
                <span class="ms-1">Kelola Artikel</span>
            </a>

            <a href="/lab11_php_oop/index.php/user/index" class="list-group-item list-group-item-action py-2 rounded-3 border-0 d-flex align-items-center <?= isActive('user', 'index') ?>">
                <i class="fas fa-user-shield icon-box text-center"></i> 
                <span class="ms-1">Kelola User</span>
            </a>
        <?php endif; ?>

        <div class="nav-label mb-2 mt-4 ms-2">PROFIL AKUN</div>

        <a href="/lab11_php_oop/index.php/user/profile" class="list-group-item list-group-item-action py-2 rounded-3 border-0 d-flex align-items-center <?= isActive('user', 'profile') ?>">
            <i class="fas fa-user-circle icon-box text-center"></i> 
            <span class="ms-1">Profil Saya</span>
        </a>
        
    </div>

    <div class="p-3 border-top bg-light">
        <a href="/lab11_php_oop/index.php/user/logout" 
           class="btn btn-outline-danger w-100 fw-bold shadow-sm py-2 d-flex align-items-center justify-content-center btn-logout"
           onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
            <i class="fas fa-power-off me-2"></i> Logout
        </a>
    </div>

</div>

<style>
/* 1. Styling Label Kategori Menu */
.nav-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #adb5bd;
    letter-spacing: 1.5px;
}

/* 2. Styling Wadah Ikon */
.icon-box {
    width: 32px;
    font-size: 1.1rem;
    transition: all 0.3s;
}

/* 3. Style Menu Dasar */
.list-group-item {
    background: transparent;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    font-weight: 500;
}

/* 4. Tampilan Menu Saat Aktif (Warna Biru Primary sesuai screenshot) */
.active-menu {
    background-color: #4e73df !important;
    color: #ffffff !important;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2) !important;
}
.active-menu .icon-box { color: #ffffff; }

/* 5. Efek Hover Pada Menu */
.hover-menu:hover {
    background-color: #f8f9fc !important;
    color: #4e73df !important;
    transform: translateX(3px);
}

/* 6. Animasi Logo Pulse (Denyut) */
.logo-anim { animation: logoPulse 2s infinite; }
@keyframes logoPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* 7. Tombol Logout interaktif */
.btn-logout {
    border-radius: 10px;
    transition: 0.3s;
}
.btn-logout:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-2px);
}

/* Sembunyikan scrollbar agar rapi */
.list-group::-webkit-scrollbar { width: 0; }
</style>