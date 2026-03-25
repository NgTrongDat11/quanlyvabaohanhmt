<div class="page-header">
    <div>
        <h1>Xin chào, <?= e($_SESSION['user']['HoTen'] ?? 'Nhân viên') ?></h1>
        <p>Hệ thống tiếp nhận sửa chữa - Cao Hùng Tech</p>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="nv-stats">
    <div class="nv-stat-card nv-stat-today">
        <div class="nv-stat-number"><?= $thongKe['phieu_hom_nay'] ?? 0 ?></div>
        <div class="nv-stat-label">Tiếp nhận hôm nay</div>
    </div>
    <div class="nv-stat-card nv-stat-waiting">
        <div class="nv-stat-number"><?= $thongKe['cho_tra'] ?? 0 ?></div>
        <div class="nv-stat-label">Chờ trả khách</div>
    </div>
    <div class="nv-stat-card nv-stat-month">
        <div class="nv-stat-number"><?= $thongKe['tong_phieu_thang'] ?? 0 ?></div>
        <div class="nv-stat-label">Tổng phiếu tháng</div>
    </div>
</div>

<!-- Chức năng chính -->
<div class="nv-actions">
    <a href="<?= url('nhanvien/tiepnhan') ?>" class="nv-action-card">
        <div class="nv-action-icon" style="background:#e6a817;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <h3>Tiếp Nhận Mới</h3>
        <p>Tạo phiếu sửa chữa mới</p>
    </a>
    <a href="<?= url('nhanvien/traphieu') ?>" class="nv-action-card">
        <div class="nv-action-icon" style="background:#198754;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3>Trả Thiết Bị</h3>
        <p>Trả máy cho khách hàng</p>
    </a>
    <a href="<?= url('nhanvien/danhsach') ?>" class="nv-action-card">
        <div class="nv-action-icon" style="background:#0d6efd;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <h3>Danh Sách Phiếu</h3>
        <p>Xem tất cả phiếu sửa chữa</p>
    </a>
</div>

<!-- Phiếu chờ trả gần đây -->
<div class="card" style="margin-top:25px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h3>Phiếu Hoàn Thành - Chờ Trả Khách</h3>
        <a href="<?= url('nhanvien/traphieu') ?>" style="font-size:13px;color:#e6a817;text-decoration:none;font-weight:600;">Xem tất cả →</a>
    </div>
    <div class="card-body">
        <?php if (empty($choTra)): ?>
            <div class="empty-state">
                <p>Không có phiếu nào chờ trả</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã Phiếu</th>
                        <th>Khách Hàng</th>
                        <th>SĐT</th>
                        <th>Sản Phẩm</th>
                        <th>Tổng Tiền</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($choTra, 0, 5) as $p): ?>
                    <tr>
                        <td><strong>#<?= $p['MaPhieu'] ?></strong></td>
                        <td><?= e($p['TenKhachHang'] ?? '') ?></td>
                        <td><?= e($p['SDT_KhachHang'] ?? '') ?></td>
                        <td><?= e($p['TenSanPham'] ?? '') ?></td>
                        <td class="text-success"><strong><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                        <td>
                            <a href="<?= url('nhanvien/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem</a>
                            <a href="<?= url('nhanvien/traphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-success">Trả</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
/* === NV STATS === */
.nv-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}
.nv-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px 20px;
    text-align: center;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s;
}
.nv-stat-card:hover { transform: translateY(-2px); }
.nv-stat-number {
    font-size: 36px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 6px;
}
.nv-stat-label {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
.nv-stat-today { border-left: 4px solid #e6a817; }
.nv-stat-today .nv-stat-number { color: #e6a817; }
.nv-stat-waiting { border-left: 4px solid #fd7e14; }
.nv-stat-waiting .nv-stat-number { color: #fd7e14; }
.nv-stat-month { border-left: 4px solid #0d6efd; }
.nv-stat-month .nv-stat-number { color: #0d6efd; }

/* === NV ACTIONS === */
.nv-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 5px;
}
.nv-action-card {
    background: #fff;
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    text-decoration: none;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    cursor: pointer;
}
.nv-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #e6a817;
}
.nv-action-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
}
.nv-action-card h3 {
    font-size: 16px; font-weight: 700; color: #333; margin: 0 0 6px;
}
.nv-action-card p {
    font-size: 13px; color: #888; margin: 0;
}

@media (max-width: 768px) {
    .nv-stats, .nv-actions { grid-template-columns: 1fr; }
}
</style>
