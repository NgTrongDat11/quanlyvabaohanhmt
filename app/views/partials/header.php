<?php
/**
 * Header partial - Include trong các views
 * Yêu cầu: $title, $user (từ controller)
 */
$user = $user ?? $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Cao Hùng Tech') ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="nav-left">
                    <a href="<?= url() ?>">Dashboard</a>
                    <a href="<?= url('khachhang') ?>">Khách hàng</a>
                    <a href="<?= url('phieusuachua') ?>">Phiếu sửa chữa</a>
                </div>
                <?php if ($user): ?>
                <div class="nav-right">
                    <span class="user-info">
                        <?= e($user['TenNhanVien']) ?> 
                        <small>(<?= e($user['ChucVu']) ?>)</small>
                    </span>
                    <a href="<?= url('auth/changepassword') ?>">Đổi MK</a>
                    <a href="<?= url('auth/logout') ?>" class="btn-logout">Đăng xuất</a>
                </div>
                <?php endif; ?>
            </nav>
        </header>

        <main>
            <?php if ($msg = flash('success')): ?>
                <div class="alert success"><?= e($msg) ?></div>
            <?php endif; ?>

            <?php if ($msg = flash('error')): ?>
                <div class="alert error"><?= e($msg) ?></div>
            <?php endif; ?>
