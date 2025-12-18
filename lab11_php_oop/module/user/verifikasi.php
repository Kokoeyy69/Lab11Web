<?php
if (isset($_SESSION['is_login'])) { header("Location: /lab11_php_oop/index.php/home/index"); exit; }
$message = "";

if ($_POST) {
    $db = new Database();
    $otp_input = $_POST['otp'];
    
    // Cek OTP di database
    $sql = "SELECT * FROM users WHERE otp='$otp_input' AND is_active=0";
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_user = $user['id'];
        
        // Aktifkan akun dan hapus OTP agar tidak bisa dipakai lagi
        $db->query("UPDATE users SET is_active=1, otp=NULL WHERE id=$id_user");
        
        echo "<script>
                alert('Verifikasi Berhasil! Akun Anda sudah aktif. Silakan Login.');
                window.location.href='/lab11_php_oop/index.php/user/login';
              </script>";
        exit;
    } else {
        $message = "Kode OTP salah atau sudah kadaluarsa!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .card-otp {
            background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1); text-align: center;
        }
        .otp-input {
            letter-spacing: 15px; text-align: center; font-size: 24px; font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card-otp">
        <h3 class="fw-bold mb-3">Verifikasi OTP</h3>
        <p class="text-muted small mb-4">Masukkan 6 digit kode yang telah kami kirimkan.</p>
        
        <?php if ($message): ?>
            <div class="alert alert-danger py-2 small"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <input type="text" name="otp" class="form-control otp-input" maxlength="6" placeholder="000000" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Verifikasi Akun</button>
        </form>
    </div>
</body>
</html>