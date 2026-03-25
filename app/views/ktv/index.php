<div class="page-header">
    <div>
        <h1>Xin chào, <?= e($_SESSION['user']['HoTen'] ?? 'Kỹ thuật viên') ?></h1>
        <p>Công việc sửa chữa - Cao Hùng Tech</p>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="ktv-stats">
    <div class="ktv-stat-card ktv-stat-assigned">
        <div class="ktv-stat-number"><?= count($daPhanCong) ?></div>
        <div class="ktv-stat-label">Đã phân công</div>
    </div>
    <div class="ktv-stat-card ktv-stat-checking">
        <div class="ktv-stat-number"><?= count($dangKiemTra) ?></div>
        <div class="ktv-stat-label">Đang kiểm tra</div>
    </div>
    <div class="ktv-stat-card ktv-stat-fixing">
        <div class="ktv-stat-number"><?= count($dangSua) ?></div>
        <div class="ktv-stat-label">Tiếp nhận</div>
    </div>
    <div class="ktv-stat-card ktv-stat-done">
        <div class="ktv-stat-number"><?= count($hoanThanh) ?></div>
        <div class="ktv-stat-label">Hoàn thành</div>
    </div>
</div>

<!-- Chức năng chính -->
<div class="ktv-actions">
    <a href="<?= url('ktv/danglam') ?>" class="ktv-action-card">
        <div class="ktv-action-icon" style="background:#0d6efd;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <h3>Đang Xử Lý</h3>
        <p>Phiếu đang kiểm tra & sửa</p>
    </a>
    <a href="<?= url('ktv/hoanthanh') ?>" class="ktv-action-card">
        <div class="ktv-action-icon" style="background:#198754;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3>Hoàn Thành</h3>
        <p>Phiếu đã sửa xong</p>
    </a>
</div>

<!-- Bảng công việc -->
<div class="ktv-board">
    <!-- Cột Đã phân công -->
    <div class="ktv-column">
        <div class="ktv-column-header" style="border-left:4px solid #e6a817;">
            <h3>Đã Phân Công</h3>
            <span class="ktv-count"><?= count($daPhanCong) ?></span>
        </div>
        <?php if (empty($daPhanCong)): ?>
            <div class="ktv-empty">Không có phiếu nào</div>
        <?php else: ?>
            <?php foreach ($daPhanCong as $p): ?>
            <div class="ktv-card">
                <div class="ktv-card-title">#<?= $p['MaPhieu'] ?> - <?= e($p['TenSanPham'] ?? 'N/A') ?></div>
                <div class="ktv-card-info">KH: <?= e($p['TenKhachHang'] ?? '') ?></div>
                <div class="ktv-card-info">SĐT: <?= e($p['SDT_KhachHang'] ?? '') ?></div>
                <div class="ktv-card-footer">
                    <span class="ktv-card-date"><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></span>
                    <div class="ktv-card-btns">
                        <a href="<?= url('ktv/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-secondary">Xem</a>
                        <a href="<?= url('ktv/batdau/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-primary"
                           onclick="return confirm('Bắt đầu kiểm tra phiếu này?')">Kiểm tra</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Cột Đang kiểm tra -->
    <div class="ktv-column">
        <div class="ktv-column-header" style="border-left:4px solid #17a2b8;">
            <h3>Đang Kiểm Tra</h3>
            <span class="ktv-count"><?= count($dangKiemTra) ?></span>
        </div>
        <?php if (empty($dangKiemTra)): ?>
            <div class="ktv-empty">Không có phiếu nào</div>
        <?php else: ?>
            <?php foreach ($dangKiemTra as $p): ?>
            <div class="ktv-card">
                <div class="ktv-card-title">#<?= $p['MaPhieu'] ?> - <?= e($p['TenSanPham'] ?? 'N/A') ?></div>
                <div class="ktv-card-info">KH: <?= e($p['TenKhachHang'] ?? '') ?></div>
                <div class="ktv-card-footer">
                    <span class="ktv-card-date"><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></span>
                    <a href="<?= url('ktv/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Chi tiết</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Cột Tiếp nhận -->
    <div class="ktv-column">
        <div class="ktv-column-header" style="border-left:4px solid #0d6efd;">
            <h3>Tiếp nhận</h3>
            <span class="ktv-count"><?= count($dangSua) ?></span>
        </div>
        <?php if (empty($dangSua)): ?>
            <div class="ktv-empty">Không có phiếu nào</div>
        <?php else: ?>
            <?php foreach ($dangSua as $p): ?>
            <div class="ktv-card">
                <div class="ktv-card-title">#<?= $p['MaPhieu'] ?> - <?= e($p['TenSanPham'] ?? 'N/A') ?></div>
                <div class="ktv-card-info">KH: <?= e($p['TenKhachHang'] ?? '') ?></div>
                <div class="ktv-card-footer">
                    <span class="ktv-card-date"><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></span>
                    <div class="ktv-card-btns">
                        <a href="<?= url('ktv/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Chi tiết</a>
                        <?php if (floatval($p['TongTien'] ?? 0) > 0): ?>
                        <a href="<?= url('ktv/hoantat/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-success"
                           onclick="return confirm('Xác nhận hoàn thành?')">Xong</a>
                        <?php else: ?>
                        <span class="btn btn-sm btn-secondary" style="opacity:0.6;cursor:not-allowed;" title="Chưa có chi phí sửa chữa">Xong</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* === KTV STATS === */
.ktv-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}
.ktv-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px 20px;
    text-align: center;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s;
}
.ktv-stat-card:hover { transform: translateY(-2px); }
.ktv-stat-number {
    font-size: 36px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 6px;
}
.ktv-stat-label {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
.ktv-stat-assigned { border-left: 4px solid #e6a817; }
.ktv-stat-assigned .ktv-stat-number { color: #e6a817; }
.ktv-stat-checking { border-left: 4px solid #17a2b8; }
.ktv-stat-checking .ktv-stat-number { color: #17a2b8; }
.ktv-stat-fixing { border-left: 4px solid #0d6efd; }
.ktv-stat-fixing .ktv-stat-number { color: #0d6efd; }
.ktv-stat-done { border-left: 4px solid #198754; }
.ktv-stat-done .ktv-stat-number { color: #198754; }

/* === KTV ACTIONS === */
.ktv-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}
.ktv-action-card {
    background: #fff;
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    text-decoration: none;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    cursor: pointer;
}
.ktv-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #e6a817;
}
.ktv-action-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
}
.ktv-action-card h3 {
    font-size: 16px; font-weight: 700; color: #333; margin: 0 0 6px;
}
.ktv-action-card p {
    font-size: 13px; color: #888; margin: 0;
}

/* === KTV BOARD === */
.ktv-board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.ktv-column {
    background: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
}
.ktv-column-header {
    background: #fff;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
}
.ktv-column-header h3 {
    font-size: 14px;
    font-weight: 700;
    color: #333;
    margin: 0;
}
.ktv-count {
    background: #e9ecef;
    color: #555;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 12px;
}
.ktv-empty {
    padding: 24px 16px;
    text-align: center;
    color: #aaa;
    font-size: 13px;
}
.ktv-card {
    background: #fff;
    margin: 10px;
    border-radius: 10px;
    padding: 14px;
    border: 1px solid #eee;
    transition: box-shadow 0.2s;
}
.ktv-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.ktv-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #333;
    margin-bottom: 6px;
}
.ktv-card-info {
    font-size: 12px;
    color: #666;
    margin-bottom: 3px;
}
.ktv-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}
.ktv-card-date {
    font-size: 11px;
    color: #aaa;
}
.ktv-card-btns {
    display: flex;
    gap: 5px;
}

@media (max-width: 992px) {
    .ktv-stats { grid-template-columns: repeat(2, 1fr); }
    .ktv-board { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .ktv-stats, .ktv-actions { grid-template-columns: 1fr; }
}
</style>
