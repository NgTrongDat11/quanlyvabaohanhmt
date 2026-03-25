<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Cao Hùng Tech</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo" style="width:230px;max-width:100%;height:auto;display:block;margin:0 auto 12px;">
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= url('auth/login') ?>">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required autofocus>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-block" style="background:linear-gradient(135deg,#f5a623,#e67e00);color:#fff;font-weight:bold;font-size:16px;padding:13px;border:none;border-radius:5px;cursor:pointer;width:100%;letter-spacing:1px;">ĐĂNG NHẬP</button>
            </form>

            <div style="display:flex;align-items:center;margin:18px 0 14px;">
                <div style="flex:1;height:1px;background:#ddd;"></div>
                <span style="padding:0 12px;color:#999;font-size:13px;">hoặc</span>
                <div style="flex:1;height:1px;background:#ddd;"></div>
            </div>

            <a href="<?= url('auth/google') ?>" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#333;font-size:15px;font-weight:600;text-decoration:none;cursor:pointer;transition:all 0.2s;box-sizing:border-box;" onmouseover="this.style.background='#f8f8f8';this.style.borderColor='#bbb'" onmouseout="this.style.background='#fff';this.style.borderColor='#ddd'">
                <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                Đăng nhập bằng Google
            </a>

            <div style="text-align:center;margin-top:15px;">
                <p style="font-size:14px;color:#555;margin-bottom:8px;">Chưa có tài khoản?
                    <a href="<?= url('auth/dangky') ?>" style="color:#ffc107;font-weight:600;text-decoration:none;">Đăng ký ngay</a>
                </p>
                <a href="<?= url('') ?>" style="color:#999;font-size:13px;text-decoration:none;">← Về trang chủ</a>
            </div>

            <p style="text-align:center;margin-top:20px;font-size:12px;color:#999;">
                &copy; 2026 Cao Hùng Tech - Vĩnh Long
            </p>
        </div>
    </div>
</body>
</html>
