<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - Cao Hùng Tech</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #e6a817;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo h1 {
            color: #e6a817;
            font-size: 24px;
            margin: 0;
            border: none;
        }
        .login-logo p {
            color: #e6a817;
            margin-top: 5px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #e6a817;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #e6a817;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(230, 168, 23, 0.4);
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert.error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
        }
        .alert.success {
            background: #efe;
            border: 1px solid #cfc;
            color: #060;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #e6a817;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <h1>🔐 Đổi Mật Khẩu</h1>
            <p>Cao Hùng Tech</p>
        </div>

        <?php if ($msg = flash('error')): ?>
            <div class="alert error">❌ <?= e($msg) ?></div>
        <?php endif; ?>

        <?php if ($msg = flash('success')): ?>
            <div class="alert success">✅ <?= e($msg) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= url('auth/changepassword') ?>">
            <div class="form-group">
                <label for="matkhaucu">Mật khẩu hiện tại</label>
                <input type="password" 
                       id="matkhaucu" 
                       name="matkhaucu" 
                       placeholder="Nhập mật khẩu hiện tại"
                       required 
                       autofocus>
            </div>

            <div class="form-group">
                <label for="matkhaumoi">Mật khẩu mới</label>
                <input type="password" 
                       id="matkhaumoi" 
                       name="matkhaumoi" 
                       placeholder="Nhập mật khẩu mới (ít nhất 6 ký tự)"
                       required>
            </div>

            <div class="form-group">
                <label for="xacnhanmatkhau">Xác nhận mật khẩu mới</label>
                <input type="password" 
                       id="xacnhanmatkhau" 
                       name="xacnhanmatkhau" 
                       placeholder="Nhập lại mật khẩu mới"
                       required>
            </div>

            <button type="submit" class="btn-login">Đổi mật khẩu</button>
            <a href="javascript:history.back()" class="back-link">← Quay lại</a>
        </form>
    </div>
</body>
</html>
