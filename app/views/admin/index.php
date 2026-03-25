<div class="page-header">
    <div>
        <h1>Xin chào, <?= e($_SESSION['user']['HoTen'] ?? 'Admin') ?></h1>
        <p>Tổng quan hệ thống quản lý sửa chữa - Cao Hùng Tech</p>
    </div>
    <a href="<?= url('admin/taophieu') ?>" class="btn btn-primary">+ Tạo Biên Nhận Mới</a>
</div>

<!-- Thống kê -->
<div class="adm-stats">
    <div class="adm-stat-card adm-stat-1">
        <div class="adm-stat-number"><?= $stats['tongPhieu'] ?? 0 ?></div>
        <div class="adm-stat-label">Tổng phiếu</div>
    </div>
    <div class="adm-stat-card adm-stat-2">
        <div class="adm-stat-number"><?= $stats['choXuLy'] ?? 0 ?></div>
        <div class="adm-stat-label">Chờ xử lý</div>
    </div>
    <div class="adm-stat-card adm-stat-3">
        <div class="adm-stat-number"><?= $stats['dangSua'] ?? 0 ?></div>
        <div class="adm-stat-label">Tiếp nhận</div>
    </div>
    <div class="adm-stat-card adm-stat-4">
        <div class="adm-stat-number"><?= $stats['hoanthanh'] ?? 0 ?></div>
        <div class="adm-stat-label">Hoàn thành</div>
    </div>
    <div class="adm-stat-card adm-stat-5">
        <div class="adm-stat-number"><?= $stats['tongKhach'] ?? 0 ?></div>
        <div class="adm-stat-label">TK Khách hàng</div>
    </div>
    <div class="adm-stat-card adm-stat-6">
        <div class="adm-stat-number"><?= $stats['tongTaiKhoan'] ?? 0 ?></div>
        <div class="adm-stat-label">Tổng tài khoản</div>
    </div>
    <div class="adm-stat-card adm-stat-1" style="opacity:0.85;">
        <div class="adm-stat-number"><?= $stats['tongSanPham'] ?? 0 ?></div>
        <div class="adm-stat-label">Sản phẩm</div>
    </div>
</div>

<!-- Chức năng chính -->
<div class="adm-actions">
    <a href="<?= url('admin/phieusuachua') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#e6a817;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <h3>Phiếu Sửa Chữa</h3>
        <p>Quản lý tất cả phiếu</p>
    </a>
    <a href="<?= url('admin/sanpham') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#0d6efd;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <h3>Sản Phẩm</h3>
        <p>Quản lý thiết bị</p>
    </a>
    <a href="<?= url('admin/taikhoan') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#6f42c1;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3>Tài Khoản</h3>
        <p>Quản lý người dùng</p>
    </a>
    <a href="<?= url('admin/baohanh') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#fd7e14;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        </div>
        <h3>Gửi Bảo Hành</h3>
        <p>Bảo hành nhà sản xuất</p>
    </a>
    <a href="<?= url('admin/doitac') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#198754;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        </div>
        <h3>Gửi Đối Tác</h3>
        <p>Gửi sửa đối tác ngoài</p>
    </a>
    <a href="<?= url('admin/baocao') ?>" class="adm-action-card">
        <div class="adm-action-icon" style="background:#dc3545;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        </div>
        <h3>Báo Cáo</h3>
        <p>Thống kê doanh thu</p>
    </a>
</div>

<!-- Phiếu mới nhất -->
<div class="card" style="margin-top:25px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h3>Phiếu Sửa Chữa Mới Nhất</h3>
        <a href="<?= url('admin/phieusuachua') ?>" style="font-size:13px;color:#e6a817;text-decoration:none;font-weight:600;">Xem tất cả →</a>
    </div>
    <div class="card-body">
        <?php if (empty($phieuMoi)): ?>
            <div class="empty-state">
                <p>Chưa có phiếu nào</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Phiếu</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th>Ngày Nhận</th>
                            <th>Trạng Thái</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phieuMoi as $p): ?>
                        <tr>
                            <td><strong>#<?= $p['MaPhieu'] ?></strong></td>
                            <td><?= e($p['TenKhachHang'] ?? 'N/A') ?></td>
                            <td><?= e($p['TenSanPham'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></td>
                            <td>
                                <?php
                                $badgeClass = 'badge-waiting';
                                switch ($p['TinhTrang'] ?? '') {
                                    case 'Đã phân công': $badgeClass = 'badge-assigned'; break;
                                    case 'Đang kiểm tra': $badgeClass = 'badge-checking'; break;
                                    case 'Tiếp nhận': $badgeClass = 'badge-processing'; break;
                                    case 'Hoàn thành': $badgeClass = 'badge-done'; break;
                                    case 'Đã trả': $badgeClass = 'badge-returned'; break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= e($p['TinhTrang'] ?? 'Chờ xử lý') ?></span>
                            </td>
                            <td><strong><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                            <td>
                                <a href="<?= url('admin/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* === ADMIN STATS === */
.adm-stats {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}
.adm-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 18px;
    text-align: center;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, box-shadow 0.2s;
}
.adm-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
.adm-stat-number {
    font-size: 42px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 8px;
}
.adm-stat-label {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
.adm-stat-1 { border-left: 4px solid #e6a817; }
.adm-stat-1 .adm-stat-number { color: #e6a817; }
.adm-stat-2 { border-left: 4px solid #fd7e14; }
.adm-stat-2 .adm-stat-number { color: #fd7e14; }
.adm-stat-3 { border-left: 4px solid #0d6efd; }
.adm-stat-3 .adm-stat-number { color: #0d6efd; }
.adm-stat-4 { border-left: 4px solid #198754; }
.adm-stat-4 .adm-stat-number { color: #198754; }
.adm-stat-5 { border-left: 4px solid #6f42c1; }
.adm-stat-5 .adm-stat-number { color: #6f42c1; }
.adm-stat-6 { border-left: 4px solid #17a2b8; }
.adm-stat-6 .adm-stat-number { color: #17a2b8; }

/* === ADMIN ACTIONS === */
.adm-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.adm-action-card {
    background: #fff;
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    text-decoration: none;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    cursor: pointer;
}
.adm-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #e6a817;
}
.adm-action-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
}
.adm-action-card h3 {
    font-size: 16px; font-weight: 700; color: #333; margin: 0 0 6px;
}
.adm-action-card p {
    font-size: 13px; color: #888; margin: 0;
}

/* === BADGE EXTRA === */
.badge-assigned { background: #e6a817; color: #fff; }
.badge-checking { background: #17a2b8; color: #fff; }

@media (max-width: 1100px) {
    .adm-stats { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 768px) {
    .adm-stats { grid-template-columns: repeat(2, 1fr); }
    .adm-actions { grid-template-columns: 1fr; }
}
</style>
