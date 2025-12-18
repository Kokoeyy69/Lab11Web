<?php
// Cek jika sudah login, redirect ke home
if (isset($_SESSION['is_login'])) {
    header("Location: /lab11_php_oop/index.php/home/index");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $login_input = $_POST['username']; // Bisa username ATAU email
    $password = $_POST['password'];

    try {
        // 1. Cari user di database menggunakan PDO (runQuery)
        // Perbaikan: Menggunakan tabel 'user' sesuai database Anda
        $sql = "SELECT * FROM user WHERE username = :u OR email = :u LIMIT 1";
        $stmt = $db->runQuery($sql, [':u' => $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Cek apakah user ditemukan dan Password cocok
        if ($user && password_verify($password, $user['password'])) {
            
            /**
             * 3. CEK STATUS AKTIF (Verifikasi OTP)
             * Catatan: Jika kolom 'is_active' belum ada di database, bagian ini akan diabaikan
             * agar tidak menyebabkan error.
             */
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                $message = "Akun belum diverifikasi! <br> <a href='/lab11_php_oop/index.php/user/verifikasi' class='fw-bold text-danger text-decoration-underline'>Klik disini untuk verifikasi OTP</a>";
            } else {
                // Login Sukses: Set Session
                $_SESSION['is_login'] = true;
                $_SESSION['id']       = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama']     = $user['nama'];
                $_SESSION['role']     = $user['role'];
                
                header("Location: /lab11_php_oop/index.php/home/index");
                exit;
            }

        } else {
            $message = "Akun tidak ditemukan atau Password salah!";
        }
    } catch (Exception $e) {
        $message = "Terjadi kesalahan sistem: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Modular App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 15px;
            margin: 0;
            overflow-x: hidden;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; }
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: fadeInUp 0.8s ease-out;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        @media (max-width: 576px) {
            .login-card { padding: 25px 20px; }
            h3 { font-size: 1.5rem; }
        }

        .form-floating .form-control { border-radius: 10px; border: 1px solid #ddd; background: #fff; }
        .form-floating .form-control:focus { border-color: #e73c7e; box-shadow: 0 0 0 0.25rem rgba(231, 60, 126, 0.25); }
        .btn-login {
            background: linear-gradient(to right, #e73c7e, #ee7752); border: none; border-radius: 10px; padding: 12px;
            font-weight: 600; width: 100%; color: white; transition: 0.2s;
        }
        .btn-login:hover { transform: scale(1.02); color: white; opacity: 0.9; }
        .password-container { position: relative; }
        .toggle-password { position: absolute; right: 15px; top: 18px; cursor: pointer; color: #aaa; z-index: 10; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="fa-solid fa-rocket fa-3x mb-3" style="color: #e73c7e;"></i>
            <h3 class="fw-bold text-dark">Welcome Back</h3>
            <p class="text-muted small">Login untuk mengakses dashboard</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-danger d-flex align-items-center small py-2 mb-3">
                <div><?= $message ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="userInput" placeholder="Username" required>
                <label for="userInput"><i class="fa-solid fa-user me-2"></i>Username / Email</label>
            </div>
            <div class="form-floating mb-2 password-container">
                <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                <label for="passInput"><i class="fa-solid fa-lock me-2"></i>Password</label>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()" id="toggleIcon"></i>
            </div>
            <div class="text-end mb-4">
                <a href="/lab11_php_oop/index.php/user/lupa_password" class="text-decoration-none small fw-bold" style="color: #e73c7e;">Lupa Password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-login shadow-sm">MASUK SEKARANG</button>
        </form>
        <div class="text-center mt-4">
            <span class="text-muted small">Belum memiliki akun?</span>
            <a href="/lab11_php_oop/index.php/user/daftar" class="fw-bold text-decoration-none ms-1" style="color: #e73c7e;">Daftar Disini</a>
        </div>
    </div>
    <script>
        function togglePassword() {
            var input = document.getElementById("passInput"); var icon = document.getElementById("toggleIcon");
            if (input.type === "password") { 
                input.type = "text"; 
                icon.classList.remove("fa-eye"); 
                icon.classList.add("fa-eye-slash"); 
            } else { 
                input.type = "password"; 
                icon.classList.remove("fa-eye-slash"); 
                icon.classList.add("fa-eye"); 
            }
        }
    </script>
</body>
</html>