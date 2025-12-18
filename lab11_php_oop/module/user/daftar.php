<?php
if (isset($_SESSION['is_login'])) { header("Location: /lab11_php_oop/index.php/home/index"); exit; }

// LOGIKA CAPTCHA MATEMATIKA
if (!isset($_SESSION['captcha_result'])) {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_result'] = $num1 + $num2;
    $_SESSION['captcha_text'] = "$num1 + $num2 = ?";
}

$message = "";
if ($_POST) {
    $db = new Database();
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha'];
    $role = 'user';

    // 1. Cek CAPTCHA (Anti Bot)
    if ($captcha_input != $_SESSION['captcha_result']) {
        $message = "Jawaban Captcha salah! Silakan hitung ulang.";
    } else {
        // 2. Cek Duplikasi User
        $cek = $db->query("SELECT * FROM users WHERE username='$username' OR email='$email'")->num_rows;
        if ($cek > 0) {
            $message = "Username atau Email sudah terdaftar!";
        } else {
            // 3. Proses Registrasi
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $otp = rand(100000, 999999); // Generate 6 digit OTP
            
            // Simpan dengan is_active = 0
            $sql = "INSERT INTO users (username, password, nama, email, role, is_active, otp) 
                    VALUES ('$username', '$pass_hash', '$nama', '$email', '$role', 0, '$otp')";
            
            if ($db->query($sql)) {
                // Reset Captcha
                unset($_SESSION['captcha_result']);
                
                // SIMULASI KIRIM EMAIL (Tampilkan di Alert)
                echo "<script>
                        alert('Registrasi Berhasil! \\n\\n[SIMULASI EMAIL] \\nKode OTP Anda adalah: $otp \\n\\nSilakan masukkan kode ini untuk verifikasi.'); 
                        window.location.href='/lab11_php_oop/index.php/user/verifikasi';
                      </script>";
                exit;
            } else { 
                $message = "Terjadi kesalahan sistem."; 
            }
        }
    }
    // Refresh angka captcha jika gagal
    $num1 = rand(1, 9); $num2 = rand(1, 9);
    $_SESSION['captcha_result'] = $num1 + $num2;
    $_SESSION['captcha_text'] = "$num1 + $num2 = ?";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Anti-Bot</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;
            overflow-x: hidden;
        }
        .register-card {
            background: #ffffff; border-radius: 20px; padding: 40px; width: 100%; max-width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); animation: slideUp 0.8s ease;
        }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .form-control { border-radius: 10px; padding: 10px 15px; background: #f9f9f9; border: 1px solid #eee; }
        .btn-register {
            background: #66a6ff; border: none; border-radius: 50px; padding: 12px;
            font-weight: 600; width: 100%; color: white; transition: 0.3s;
        }
        .btn-register:hover { background: #4a90e2; transform: translateY(-3px); }
        .captcha-box {
            background: #f0f8ff; border: 1px dashed #66a6ff; padding: 10px; border-radius: 10px; text-align: center;
            font-weight: bold; font-size: 1.2rem; color: #333; letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Buat Akun Baru</h3>
            <p class="text-muted small">Lengkapi data diri Anda</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-warning text-center small py-2 rounded-3"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
            <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
            
            <div class="mb-4">
                <label class="small fw-bold text-muted ms-1 mb-1">Verifikasi Keamanan</label>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="captcha-box noselect">
                            <?= $_SESSION['captcha_text'] ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <input type="number" name="captcha" class="form-control h-100 text-center fw-bold" placeholder="Hasil?" required>
                    </div>
                </div>
                <div class="form-text small text-end">Hitung angka di atas untuk lanjut.</div>
            </div>

            <button type="submit" class="btn btn-register">DAFTAR & VERIFIKASI</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">Sudah punya akun?</small>
            <a href="/lab11_php_oop/index.php/user/login" class="fw-bold text-decoration-none" style="color: #66a6ff;">Login Disini</a>
        </div>
    </div>
</body>
</html>