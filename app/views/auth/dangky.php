<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Cao Hùng Tech</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-box" style="max-width:480px;">
            <div class="login-logo">
                <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo" style="width:210px;max-width:100%;height:auto;display:block;margin:0 auto 12px;">
            </div>

            <?php if (!empty($error)): ?>
                <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:6px;margin-bottom:18px;border:1px solid #f5c6cb;font-size:14px;">
                    ❌ <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div style="background:#d4edda;color:#155724;padding:16px;border-radius:6px;margin-bottom:18px;border:1px solid #c3e6cb;font-size:14px;text-align:center;">
                    ✅ <?= $success ?>
                    <br><br>
                    <a href="<?= url('auth/login') ?>" class="btn btn-primary" style="display:inline-block;">
                        → Đăng Nhập Ngay
                    </a>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = '<?= url('auth/login') ?>';
                    }, 3000);
                </script>
            <?php else: ?>

            <form method="POST" action="<?= url('auth/luudangky') ?>">
                <div class="form-group">
                    <label>Tên đăng nhập <span style="color:red">*</span></label>
                    <input type="text" name="username" class="form-control"
                           placeholder="VD: nguyen@gmail.com, Nguyễn Văn A..."
                           value="<?= e($old['username'] ?? '') ?>"
                           required autofocus>
                </div>

                <div class="form-group">
                    <label>Họ và tên <span style="color:red">*</span></label>
                    <input type="text" name="HoTen" class="form-control"
                           placeholder="Nguyễn Văn A"
                           value="<?= e($old['HoTen'] ?? '') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại <span style="color:red">*</span></label>
                    <input type="tel" name="SoDienThoai" class="form-control"
                           placeholder="0901234567"
                           value="<?= e($old['SoDienThoai'] ?? '') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control"
                           placeholder="123 Đường ABC, Vĩnh Long"
                           value="<?= e($old['DiaChi'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Mật khẩu <span style="color:red">*</span></label>
                    <input type="password" name="MatKhau" class="form-control"
                           placeholder="Tối thiểu 6 ký tự" required minlength="6">
                </div>

                <div class="form-group">
                    <label>Xác nhận mật khẩu <span style="color:red">*</span></label>
                    <input type="password" name="XacNhanMatKhau" class="form-control"
                           placeholder="Nhập lại mật khẩu" required minlength="6">
                </div>

                <button type="submit" class="btn btn-block"
                    style="background:linear-gradient(135deg,#ffe566,#ffc107);color:#333;font-weight:bold;font-size:16px;padding:13px;border:none;border-radius:5px;cursor:pointer;width:100%;letter-spacing:1px;">
                    ĐĂNG KÝ
                </button>
            </form>

            <?php endif; ?>

            <div style="text-align:center;margin-top:20px;font-size:14px;">
                <a href="<?= url('auth/login') ?>" style="color:#ffc107;text-decoration:none;font-weight:600;">
                    ← Quay lại Đăng Nhập
                </a>
            </div>

            <p style="text-align:center;margin-top:20px;font-size:12px;color:#999;">
                &copy; 2026 Cao Hùng Tech - Vĩnh Long
            </p>
        </div>
    </div>
</body>
</html>
