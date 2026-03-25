<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Cao Hùng Tech' ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon">
                    <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo">
                </div>
                <h2>CAO HÙNG TECH</h2>
                <p>Trao giá trị - Nhận niềm tin</p>
            </div>

            <nav class="sidebar-menu">
                <ul>
                    <?php 
                    $loaiTK = $_SESSION['user']['LoaiTK'] ?? '';
                    $currentUrl = $_GET['url'] ?? '';
                    ?>

                    <!-- Nút về trang chủ (chung tất cả role) -->
                    <li><a href="<?= url('') ?>">
                        <i>🏠</i> Trang Chủ
                    </a></li>
                    <li class="menu-divider"></li>

                    <!-- Menu cho Admin -->
                    <?php if ($loaiTK === 'admin'): ?>
                        <li class="menu-label">Tổng quan</li>
                        <li><a href="<?= url('admin') ?>" class="<?= $currentUrl === 'admin' ? 'active' : '' ?>">
                            <i>📊</i> Dashboard
                        </a></li>

                        <li class="menu-label">Quản lý</li>
                        <li><a href="<?= url('admin/phieusuachua') ?>" class="<?= strpos($currentUrl, 'phieusuachua') !== false ? 'active' : '' ?>">
                            <i>📋</i> Phiếu Sửa Chữa
                        </a></li>
                        <li><a href="<?= url('admin/taikhoan') ?>" class="<?= strpos($currentUrl, 'taikhoan') !== false ? 'active' : '' ?>">
                            <i>👤</i> Tài Khoản
                        </a></li>
                        <li><a href="<?= url('admin/sanpham') ?>" class="<?= strpos($currentUrl, 'sanpham') !== false ? 'active' : '' ?>">
                            <i>💻</i> Sản Phẩm
                        </a></li>

                        <li class="menu-label">Kho & Đối tác</li>
                        <li><a href="<?= url('admin/baohanh') ?>" class="<?= strpos($currentUrl, 'baohanh') !== false ? 'active' : '' ?>">
                            <i>🔄</i> Gửi Bảo Hành
                        </a></li>
                        <li><a href="<?= url('admin/doitac') ?>" class="<?= strpos($currentUrl, 'doitac') !== false ? 'active' : '' ?>">
                            <i>🤝</i> Gửi Đối Tác
                        </a></li>

                        <li class="menu-label">Hệ thống</li>
                        <li><a href="<?= url('admin/baocao') ?>" class="<?= strpos($currentUrl, 'baocao') !== false ? 'active' : '' ?>">
                            <i>📈</i> Báo Cáo
                        </a></li>

                    <!-- Menu cho Kỹ thuật viên -->
                    <?php elseif ($loaiTK === 'ktv'): ?>
                        <li class="menu-label">Công việc</li>
                        <li><a href="<?= url('ktv') ?>" class="<?= $currentUrl === 'ktv' ? 'active' : '' ?>">
                            <i>📊</i> Tổng Quan
                        </a></li>
                        <li><a href="<?= url('ktv/danglam') ?>" class="<?= strpos($currentUrl, 'danglam') !== false ? 'active' : '' ?>">
                            <i>🔧</i> Đang Xử Lý
                        </a></li>
                        <li><a href="<?= url('ktv/hoanthanh') ?>" class="<?= strpos($currentUrl, 'hoanthanh') !== false ? 'active' : '' ?>">
                            <i>✅</i> Hoàn Thành
                        </a></li>

                    <!-- Menu cho Nhân viên tiếp nhận -->
                    <?php elseif ($loaiTK === 'nhanvien'): ?>
                        <li class="menu-label">Tổng quan</li>
                        <li><a href="<?= url('nhanvien') ?>" class="<?= $currentUrl === 'nhanvien' ? 'active' : '' ?>">
                            <i>📊</i> Dashboard
                        </a></li>

                        <li class="menu-label">Tiếp nhận</li>
                        <li><a href="<?= url('nhanvien/tiepnhan') ?>" class="<?= strpos($currentUrl, 'tiepnhan') !== false ? 'active' : '' ?>">
                            <i>📝</i> Tiếp Nhận Mới
                        </a></li>
                        <li><a href="<?= url('nhanvien/traphieu') ?>" class="<?= strpos($currentUrl, 'traphieu') !== false ? 'active' : '' ?>">
                            <i>📤</i> Trả Thiết Bị
                        </a></li>
                        <li><a href="<?= url('nhanvien/danhsach') ?>" class="<?= strpos($currentUrl, 'danhsach') !== false ? 'active' : '' ?>">
                            <i>📋</i> Danh Sách Phiếu
                        </a></li>

                    <!-- Menu cho Khách hàng -->
                    <?php elseif ($loaiTK === 'khachhang'): ?>
                        <li class="menu-label">Tổng quan</li>
                        <li><a href="<?= url('khach') ?>" class="<?= $currentUrl === 'khach' ? 'active' : '' ?>">
                            <i>📊</i> Dashboard
                        </a></li>

                        <li class="menu-label">Dịch vụ</li>
                        <li><a href="<?= url('khach/taophieu') ?>" class="<?= strpos($currentUrl, 'taophieu') !== false ? 'active' : '' ?>">
                            <i>📝</i> Gửi Yêu Cầu Sửa Chữa
                        </a></li>

                        <li class="menu-label">Thiết bị của tôi</li>
                        <li><a href="<?= url('khach/donhang') ?>" class="<?= strpos($currentUrl, 'donhang') !== false ? 'active' : '' ?>">
                            <i>📋</i> Đơn Hàng Của Tôi
                        </a></li>
                        <li><a href="<?= url('khach/tracuu') ?>" class="<?= strpos($currentUrl, 'tracuu') !== false ? 'active' : '' ?>">
                            <i>🔍</i> Tra Cứu
                        </a></li>

                    <?php endif; ?>
                </ul>
            </nav>

            <div class="sidebar-user">
                <a href="<?= url('auth/changepassword') ?>" class="user-info" style="text-decoration:none;color:inherit;cursor:pointer;" title="Đổi mật khẩu">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['user']['HoTen'] ?? $_SESSION['user']['TenNhanVien'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="user-name"><?= e($_SESSION['user']['HoTen'] ?? $_SESSION['user']['TenNhanVien'] ?? 'User') ?></div>
                        <div class="user-role"><?= e($_SESSION['user']['ChucVu'] ?? '') ?></div>
                    </div>
                </a>
                <a href="<?= url('auth/logout') ?>" class="btn-logout">
                    Đăng Xuất
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php if ($flash = flash('success')): ?>
                <div class="alert alert-success"><?= e($flash) ?></div>
            <?php endif; ?>

            <?php if ($flash = flash('error')): ?>
                <div class="alert alert-danger"><?= e($flash) ?></div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </main>
    </div>

    <script src="<?= asset('js/main.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
