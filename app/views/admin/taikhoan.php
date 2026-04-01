<div class="page-header">
    <div>
        <h1>Quản Lý Tài Khoản</h1>
        <p>Tạo và quản lý tài khoản đăng nhập hệ thống</p>
    </div>
</div>

<!-- Form tạo tài khoản -->
<div class="card mb-20">
    <div class="card-header">
        <h3>Thêm Tài Khoản Mới</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= url('admin/luutaikhoan') ?>">
            <div class="form-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:15px;">
                <div class="form-group">
                    <label>Tên người dùng <span style="color:red">*</span></label>
                    <input type="text" name="username" class="form-control"
                           placeholder="VD: nguyenvana, ktv01..." required>
                </div>
                <div class="form-group">
                    <label>Họ và tên <span style="color:red">*</span></label>
                    <input type="text" name="HoTen" class="form-control"
                           placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu <span style="color:red">*</span></label>
                    <input type="password" name="MatKhau" class="form-control"
                           placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-group">
                    <label>Loại tài khoản <span style="color:red">*</span></label>
                    <select name="LoaiTK" class="form-control" required>
                        <option value="nhanvien">Nhân viên tiếp nhận</option>
                        <option value="ktv">Kỹ thuật viên</option>
                        <option value="admin">Quản lý (Admin)</option>
                        <option value="khachhang">Khách hàng</option>
                    </select>
                </div>
            </div>
            <div style="margin-top:15px; text-align:right;">
                <button type="submit" class="btn btn-primary">
                    Tạo Tài Khoản
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách tài khoản -->
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Danh Sách Tài Khoản</h3>
        <span style="background:#e67e00;color:white;padding:4px 12px;border-radius:20px;font-size:13px;">
            <?= count($dsTaiKhoan) ?> tài khoản
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php
        // Phân trang tài khoản
        $allAccArr = array_keys($dsTaiKhoan);
        $perPage = 10;
        $pagPage = max(1, intval($_GET['trang'] ?? 1));
        $pagTotal = count($allAccArr);
        $pagTotalPages = max(1, ceil($pagTotal / $perPage));
        $pagPage = min($pagPage, $pagTotalPages);
        $offset = ($pagPage - 1) * $perPage;
        $pagedKeys = array_slice($allAccArr, $offset, $perPage);

        $hardcoded = ['admin', 'kythuatvien', 'nhanvien', 'khachhang'];
        $badgeColors = [
            'admin'     => '#c41e3a',
            'ktv'       => '#3498db',
            'nhanvien'  => '#27ae60',
            'khachhang' => '#7f8c8d'
        ];
        ?>
        <table class="table" style="margin:0;">
            <thead>
                <tr>
                    <th class="stt-col">STT</th>
                    <th>Tên Người Dùng</th>
                    <th>Họ Tên</th>
                    <th>Vai Trò</th>
                    <th>Loại TK</th>
                    <th style="text-align:center;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagedKeys as $idx => $username):
                    $acc = $dsTaiKhoan[$username];
                    $isHardcoded = in_array($username, $hardcoded);
                    $loaiTK = $acc['LoaiTK'] ?? '';
                    $badgeColor = $badgeColors[$loaiTK] ?? '#999';
                ?>
                <tr>
                    <td class="stt-col"><?= $offset + $idx + 1 ?></td>
                    <td>
                        <strong><?= e($username) ?></strong>
                        <?php if ($isHardcoded): ?>
                            <span style="font-size:10px;color:#999;background:#f5f5f5;padding:1px 6px;border-radius:8px;margin-left:5px;">mặc định</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($acc['TenNhanVien'] ?? $acc['HoTen'] ?? '') ?></td>
                    <td><?= e($acc['ChucVu'] ?? '') ?></td>
                    <td>
                        <span style="background:<?= $badgeColor ?>;color:white;padding:3px 10px;border-radius:20px;font-size:12px;">
                            <?= e($loaiTK) ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <?php if (!$isHardcoded): ?>
                            <button type="button" class="btn btn-sm btn-primary" 
                                onclick="moModalSuaTaiKhoan(<?= htmlspecialchars(json_encode($acc + ['username' => $username]), ENT_QUOTES) ?>)">
                                Sửa
                            </button>
                            <form method="POST" action="<?= url('admin/xoataikhoan/' . urlencode($username)) ?>" style="display:inline;" onsubmit="return confirm('Xóa tài khoản <?= e($username) ?>?')">
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#ccc;font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        $pagPerPage = $perPage;
        $pagBaseUrl = url('admin/taikhoan');
        $pagParams = [];
        include ROOT_PATH . '/app/views/partials/pagination.php';
        ?>
    </div>
</div>

<!-- Modal Sửa Tài Khoản -->
<div id="modalSuaTaiKhoan" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1002;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:25px;width:500px;max-width:95vw;">
        <h3 style="margin-top:0;">✏️ Sửa Thông Tin Tài Khoản</h3>
        <form method="POST" action="<?= url('admin/capnhattaikhoan') ?>">
            <input type="hidden" name="username" id="edit_username">
            
            <div class="form-group">
                <label>Tên người dùng</label>
                <input type="text" id="edit_username_display" class="form-control" disabled>
            </div>
            
            <div class="form-group">
                <label>Họ và tên <span style="color:red">*</span></label>
                <input type="text" name="HoTen" id="edit_HoTen" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="MatKhau" id="edit_MatKhau" class="form-control" placeholder="Để trống nếu không đổi">
                <small style="color:#888;">Chỉ điền nếu muốn thay đổi mật khẩu</small>
            </div>
            
            <div class="form-group">
                <label>Loại tài khoản <span style="color:red">*</span></label>
                <select name="LoaiTK" id="edit_LoaiTK" class="form-control" required>
                    <option value="nhanvien">Nhân viên tiếp nhận</option>
                    <option value="ktv">Kỹ thuật viên</option>
                    <option value="admin">Quản lý (Admin)</option>
                    <option value="khachhang">Khách hàng</option>
                </select>
            </div>
            
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="dongModalSuaTaiKhoan()">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function moModalSuaTaiKhoan(acc) {
    document.getElementById('edit_username').value = acc.username;
    document.getElementById('edit_username_display').value = acc.username;
    document.getElementById('edit_HoTen').value = acc.TenNhanVien || acc.HoTen || '';
    document.getElementById('edit_MatKhau').value = '';
    document.getElementById('edit_LoaiTK').value = acc.LoaiTK || 'nhanvien';
    
    const modal = document.getElementById('modalSuaTaiKhoan');
    modal.style.display = 'flex';
}

function dongModalSuaTaiKhoan() {
    document.getElementById('modalSuaTaiKhoan').style.display = 'none';
}

// Đóng modal khi click bên ngoài
document.getElementById('modalSuaTaiKhoan')?.addEventListener('click', function(e) {
    if (e.target === this) dongModalSuaTaiKhoan();
});
</script>
