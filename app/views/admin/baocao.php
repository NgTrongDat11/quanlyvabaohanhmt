<div class="page-header">
    <div>
        <h1>Báo Cáo Thống Kê</h1>
        <p>Tổng quan hoạt động kinh doanh</p>
    </div>
    <form method="GET" action="<?= url('admin/baocao') ?>" style="display:flex;gap:10px;align-items:center;">
        <select name="thang" class="form-control" style="width:auto;">
            <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $thangChon ? 'selected' : '' ?>>Tháng <?= $i ?></option>
            <?php endfor; ?>
        </select>
        <select name="nam" class="form-control" style="width:auto;">
            <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
            <option value="<?= $y ?>" <?= $y == $namChon ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary" style="padding:8px 24px;font-size:15px;">Xem</button>
    </form>
</div>

<!-- Thống kê chính -->
<div class="bc-stats">
    <div class="bc-stat-card bc-stat-1">
        <div class="bc-stat-number"><?= $tongPhieuThang ?></div>
        <div class="bc-stat-label">Tổng phiếu tháng <?= $thangChon ?></div>
    </div>
    <div class="bc-stat-card bc-stat-2">
        <div class="bc-stat-number"><?= number_format($doanhThuThang, 0, ',', '.') ?>đ</div>
        <div class="bc-stat-label">Doanh thu (đã trả)</div>
    </div>
    <div class="bc-stat-card bc-stat-3">
        <div class="bc-stat-number"><?= $phieuDaTra ?></div>
        <div class="bc-stat-label">Phiếu đã trả</div>
    </div>
    <div class="bc-stat-card bc-stat-4">
        <div class="bc-stat-number"><?= $tyLeHoanThanh ?>%</div>
        <div class="bc-stat-label">Tỷ lệ hoàn thành</div>
    </div>
</div>

<!-- Biểu đồ 6 tháng + Trạng thái -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">

    <!-- Biểu đồ 6 tháng -->
    <div class="card">
        <div class="card-header"><h3>Doanh thu & Phiếu 6 tháng gần nhất</h3></div>
        <div class="card-body">
            <div class="bc-chart">
                <?php
                $maxDT = max(array_column($thongKe6Thang, 'doanhThu'));
                if ($maxDT <= 0) $maxDT = 1;
                $maxSP = max(array_column($thongKe6Thang, 'soPhieu'));
                if ($maxSP <= 0) $maxSP = 1;
                ?>
                <?php foreach ($thongKe6Thang as $tk): ?>
                <div class="bc-chart-col">
                    <div class="bc-bar-wrap">
                        <div class="bc-bar bc-bar-dt" style="height:<?= round($tk['doanhThu'] / $maxDT * 120) ?>px;" title="<?= number_format($tk['doanhThu'], 0, ',', '.') ?>đ">
                            <span class="bc-bar-val"><?= $tk['doanhThu'] > 0 ? number_format($tk['doanhThu'] / 1000, 0) . 'k' : '0' ?></span>
                        </div>
                        <div class="bc-bar bc-bar-sp" style="height:<?= round($tk['soPhieu'] / $maxSP * 120) ?>px;" title="<?= $tk['soPhieu'] ?> phiếu">
                            <span class="bc-bar-val"><?= $tk['soPhieu'] ?></span>
                        </div>
                    </div>
                    <div class="bc-chart-label"><?= $tk['thang'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;gap:20px;justify-content:center;margin-top:12px;font-size:12px;color:#888;">
                <span><span style="display:inline-block;width:12px;height:12px;background:#e6a817;border-radius:2px;vertical-align:middle;margin-right:4px;"></span> Doanh thu</span>
                <span><span style="display:inline-block;width:12px;height:12px;background:#0d6efd;border-radius:2px;vertical-align:middle;margin-right:4px;"></span> Số phiếu</span>
            </div>
        </div>
    </div>

    <!-- Phân bổ trạng thái -->
    <div class="card">
        <div class="card-header"><h3>Trạng thái tháng <?= $thangChon ?></h3></div>
        <div class="card-body">
            <?php if (empty($theoTrangThai)): ?>
                <div class="empty-state"><p>Không có dữ liệu</p></div>
            <?php else: ?>
                <?php
                $ttColors = [
                    'Chờ xử lý' => '#ffc107',
                    'Đã phân công' => '#e6a817',
                    'Đang kiểm tra' => '#17a2b8',
                    'Tiếp nhận' => '#0d6efd',
                    'Hoàn thành' => '#198754',
                    'Đã trả' => '#6c757d',
                ];
                ?>
                <?php foreach ($theoTrangThai as $tt => $sl): ?>
                <div class="bc-status-row">
                    <div class="bc-status-info">
                        <span class="bc-status-dot" style="background:<?= $ttColors[$tt] ?? '#999' ?>;"></span>
                        <span><?= e($tt) ?></span>
                    </div>
                    <div class="bc-status-bar-wrap">
                        <div class="bc-status-bar" style="width:<?= $tongPhieuThang > 0 ? round($sl / $tongPhieuThang * 100) : 0 ?>%;background:<?= $ttColors[$tt] ?? '#999' ?>;"></div>
                    </div>
                    <strong><?= $sl ?></strong>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Danh sách phiếu -->
<div style="margin-top:20px;">

    <!-- Danh sách phiếu trong tháng -->
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <h3>Phiếu tháng <?= $thangChon ?>/<?= $namChon ?></h3>
            <span style="background:#e6a817;color:white;padding:3px 12px;border-radius:12px;font-size:12px;"><?= $tongPhieuThang ?> phiếu</span>
        </div>
        <div class="card-body" style="padding:0;">
            <?php
            // Phân trang
            $perPage = 10;
            $pagPage = max(1, intval($_GET['trang'] ?? 1));
            $pagTotal = count($phieuThang);
            $pagTotalPages = max(1, ceil($pagTotal / $perPage));
            $pagPage = min($pagPage, $pagTotalPages);
            $offset = ($pagPage - 1) * $perPage;
            $pagedItems = array_slice($phieuThang, $offset, $perPage);
            ?>
            <?php if (empty($phieuThang)): ?>
                <div class="empty-state" style="padding:20px;"><p>Không có phiếu nào</p></div>
            <?php else: ?>
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th>Ngày Nhận</th>
                            <th>Trạng Thái</th>
                            <th style="text-align:right;">Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagedItems as $idx => $p): ?>
                        <tr>
                            <td><?= $offset + $idx + 1 ?></td>
                            <td><a href="<?= url('admin/xemphieu/' . $p['MaPhieu']) ?>" style="color:#e6a817;font-weight:700;">#<?= $p['MaPhieu'] ?></a></td>
                            <td><?= e($p['TenKhachHang'] ?? '') ?></td>
                            <td><?= e($p['TenSanPham'] ?? '') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></td>
                            <td>
                                <?php
                                $bc = 'badge-waiting';
                                switch ($p['TinhTrang'] ?? '') {
                                    case 'Đã phân công': $bc = 'badge-assigned'; break;
                                    case 'Đang kiểm tra': $bc = 'badge-checking'; break;
                                    case 'Tiếp nhận': $bc = 'badge-processing'; break;
                                    case 'Hoàn thành': $bc = 'badge-done'; break;
                                    case 'Đã trả': $bc = 'badge-returned'; break;
                                }
                                ?>
                                <span class="badge <?= $bc ?>"><?= e($p['TinhTrang'] ?? 'Chờ xử lý') ?></span>
                            </td>
                            <td style="text-align:right;"><strong><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                $pagPerPage = $perPage;
                $pagBaseUrl = url('admin/baocao');
                $pagParams = ['thang' => $thangChon, 'nam' => $namChon];
                include ROOT_PATH . '/app/views/partials/pagination.php';
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* === BC STATS === */
.bc-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 25px;
}
.bc-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 22px 16px;
    text-align: center;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s;
}
.bc-stat-card:hover { transform: translateY(-2px); }
.bc-stat-number {
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 6px;
}
.bc-stat-label { font-size: 12px; color: #888; font-weight: 500; }
.bc-stat-1 { border-left: 4px solid #e6a817; }
.bc-stat-1 .bc-stat-number { color: #e6a817; }
.bc-stat-2 { border-left: 4px solid #198754; }
.bc-stat-2 .bc-stat-number { color: #198754; }
.bc-stat-3 { border-left: 4px solid #6c757d; }
.bc-stat-3 .bc-stat-number { color: #6c757d; }
.bc-stat-4 { border-left: 4px solid #0d6efd; }
.bc-stat-4 .bc-stat-number { color: #0d6efd; }

/* === CHART === */
.bc-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    height: 160px;
    padding-top: 10px;
}
.bc-chart-col { display:flex; flex-direction:column; align-items:center; }
.bc-bar-wrap { display:flex; gap:4px; align-items:flex-end; }
.bc-bar {
    width: 28px;
    min-height: 4px;
    border-radius: 4px 4px 0 0;
    position: relative;
    transition: height 0.3s;
}
.bc-bar-dt { background: #e6a817; }
.bc-bar-sp { background: #0d6efd; }
.bc-bar-val {
    position: absolute;
    top: -18px;
    left: 50%; transform: translateX(-50%);
    font-size: 10px;
    color: #666;
    white-space: nowrap;
    font-weight: 600;
}
.bc-chart-label { font-size: 11px; color: #888; margin-top: 6px; }

/* === STATUS ROWS === */
.bc-status-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    font-size: 13px;
}
.bc-status-info { display:flex; align-items:center; gap:6px; min-width:110px; }
.bc-status-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.bc-status-bar-wrap { flex:1; background:#f0f0f0; border-radius:4px; height:8px; overflow:hidden; }
.bc-status-bar { height:100%; border-radius:4px; transition:width 0.3s; }

@media (max-width: 992px) {
    .bc-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .bc-stats { grid-template-columns: 1fr; }
}
</style>
