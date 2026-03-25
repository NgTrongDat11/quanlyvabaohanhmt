<div class="page-header">
    <div>
        <h1>Chi Tiết Đơn Hàng #<?= $phieu['MaPhieu'] ?></h1>
        <p>Thông tin phiếu sửa chữa của bạn</p>
    </div>
    <a href="<?= url('khach/donhang') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<!-- Timeline trạng thái -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <?php
        $trangThaiGoc = $phieu['TinhTrang'] ?? 'Chờ xử lý';
        // Khách chỉ thấy 4 trạng thái
        if (in_array($trangThaiGoc, ['Chờ xử lý', 'Đã phân công', 'Đang kiểm tra'])) {
            $trangThai = 'Chờ xử lý';
        } elseif ($trangThaiGoc == 'Tiếp nhận') {
            $trangThai = 'Tiếp nhận';
        } elseif ($trangThaiGoc == 'Hoàn thành') {
            $trangThai = 'Hoàn thành';
        } elseif ($trangThaiGoc == 'Đã trả') {
            $trangThai = 'Đã trả';
        } else {
            $trangThai = 'Chờ xử lý';
        }
        $step = 1;
        if ($trangThai == 'Tiếp nhận') $step = 2;
        elseif ($trangThai == 'Hoàn thành') $step = 3;
        elseif ($trangThai == 'Đã trả') $step = 4;
        ?>
        <div class="status-timeline">
            <div class="timeline-step <?= $step >= 1 ? 'active' : '' ?>">
                <div class="step-icon">📝</div>
                <div class="step-label">Tiếp nhận</div>
            </div>
            <div class="timeline-line <?= $step >= 2 ? 'active' : '' ?>"></div>
            <div class="timeline-step <?= $step >= 2 ? 'active' : '' ?>">
                <div class="step-icon">🔧</div>
                <div class="step-label">Đang xử lý</div>
            </div>
            <div class="timeline-line <?= $step >= 3 ? 'active' : '' ?>"></div>
            <div class="timeline-step <?= $step >= 3 ? 'active' : '' ?>">
                <div class="step-icon">✅</div>
                <div class="step-label">Hoàn thành</div>
            </div>
            <div class="timeline-line <?= $step >= 4 ? 'active' : '' ?>"></div>
            <div class="timeline-step <?= $step >= 4 ? 'active' : '' ?>">
                <div class="step-icon">📦</div>
                <div class="step-label">Đã trả</div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="display:flex; gap:20px; flex-wrap:wrap;">
    <!-- Thông tin phiếu -->
    <div class="col" style="flex:1; min-width:300px;">
        <div class="card">
            <div class="card-header">
                <h3>📋 Thông Tin Sửa Chữa</h3>
            </div>
            <div class="card-body">
                <table class="info-table">
                    <tr>
                        <td><strong>Mã phiếu:</strong></td>
                        <td>#<?= $phieu['MaPhieu'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Thiết bị:</strong></td>
                        <td><?= e($phieu['TenSanPham'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Serial:</strong></td>
                        <td><?= e($phieu['MaSerial'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phụ kiện:</strong></td>
                        <td><?= e($phieu['PhuKienKemTheo'] ?? 'Không có') ?></td>
                    </tr>
                    <?php
                    $moTaTinhTrang = trim($phieu['GhiChuTinhTrang'] ?? '');
                    ?>
                    <?php if (!empty($moTaTinhTrang)): ?>
                    <tr>
                        <td><strong>Tình trạng:</strong></td>
                        <td><?= e($moTaTinhTrang) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Ngày nhận:</strong></td>
                        <td><?= date('d/m/Y', strtotime($phieu['NgayNhan'])) ?></td>
                    </tr>
                    <?php if ($trangThaiGoc === 'Đã trả'): ?>
                    <tr>
                        <td><strong>Ngày trả:</strong></td>
                        <td><?= $phieu['NgayTra'] ? date('d/m/Y', strtotime($phieu['NgayTra'])) : '' ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Trạng thái:</strong></td>
                        <td>
                            <?php
                            $class = 'status-pending';
                            if ($trangThai == 'Tiếp nhận' || $trangThai == 'Đang xử lý') $class = 'status-processing';
                            elseif ($trangThai == 'Hoàn thành' || $trangThai == 'Đã trả') $class = 'status-done';
                            ?>
                            <span class="status-badge <?= $class ?>"><?= e($trangThai) ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Chi tiết sửa chữa & giá -->
    <div class="col" style="flex:1; min-width:300px;">
        <div class="card">
            <div class="card-header">
                <h3>💰 Chi Phí Sửa Chữa</h3>
            </div>
            <div class="card-body">
                <?php if (empty($chiTiet)): ?>
                    <p style="color:#666; text-align:center;">Đang cập nhật chi tiết...</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hạng mục/Dịch vụ</th>
                                <th>SL</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chiTiet as $ct): ?>
                            <tr>
                                <td><?= e($ct['HangMuc'] ?? '') ?></td>
                                <td><?= $ct['SoLuong'] ?></td>
                                <td><?= number_format($ct['SoLuong'] * $ct['DonGia'], 0, ',', '.') ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <div style="background:#f8f9fa; padding:20px; border-radius:8px; margin-top:20px; text-align:center;">
                    <span style="font-size:14px; color:#666;">TỔNG TIỀN</span>
                    <div style="font-size:28px; color:var(--primary-red); font-weight:bold;">
                        <?= number_format($phieu['TongTien'] ?? 0, 0, ',', '.') ?> VNĐ
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo cho khách -->
<?php if ($trangThai == 'Hoàn thành'): ?>
<div class="card" style="margin-top:20px; border:2px solid #27ae60;">
    <div class="card-body" style="text-align:center; padding:30px; background:#e8f5e9;">
        <div style="font-size:50px;">🎉</div>
        <h2 style="color:#27ae60; margin:15px 0;">Thiết bị đã sửa xong!</h2>
        <p>Quý khách vui lòng đến cửa hàng để nhận máy.</p>
        <p style="margin-top:15px;">
            <strong>Địa chỉ:</strong> 189A Nguyễn Đáng, Khóm 6, Phường Nguyệt Hóa, Vĩnh Long
        </p>
        <p style="margin-top:8px;">
            <strong>Hotline:</strong> 094.179.1313
        </p>
    </div>
</div>
<?php endif; ?>

<!-- Liên hệ hỗ trợ -->
<div class="card" style="margin-top:20px;">
    <div class="card-body" style="text-align:center; padding:20px;">
        <p>Cần hỗ trợ? Liên hệ hotline: <strong style="color:var(--primary-red);">094.179.1313</strong></p>
    </div>
</div>

<style>
.status-timeline {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.timeline-step {
    text-align: center;
    opacity: 0.4;
}

.timeline-step.active {
    opacity: 1;
}

.step-icon {
    font-size: 30px;
    margin-bottom: 8px;
}

.step-label {
    font-size: 12px;
    font-weight: 500;
}

.timeline-line {
    width: 60px;
    height: 3px;
    background: #ddd;
    margin: 0 10px;
}

.timeline-line.active {
    background: var(--primary-red);
}

.info-table {
    width: 100%;
}

.info-table td {
    padding: 10px 5px;
    border-bottom: 1px solid #eee;
}

.info-table td:first-child {
    width: 40%;
    color: #666;
}
</style>
