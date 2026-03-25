<div class="page-header">
    <div>
        <h1>Đơn Hàng Của Tôi</h1>
        <p>Lịch sử sửa chữa của bạn</p>
    </div>
    <a href="<?= url('khach') ?>" class="btn btn-secondary">← Trang chủ</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (($tongDonHang ?? 0) == 0 && empty($donHang)): ?>
            <div class="empty-state" style="padding:60px;">
                <div style="font-size:80px;">📭</div>
                <h3>Chưa có đơn hàng nào</h3>
                <p style="color:#666;">Bạn chưa có đơn hàng sửa chữa nào trong hệ thống</p>
            </div>
        <?php else: ?>
            <!-- Bộ lọc trạng thái -->
            <div style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">
                <a href="<?= url('khach/donhang') ?>" class="btn <?= empty($_GET['trang_thai']) ? 'btn-primary' : 'btn-secondary' ?>">
                    Tất cả
                </a>
                <a href="<?= url('khach/donhang?trang_thai=Chờ xử lý') ?>" 
                   class="btn <?= ($_GET['trang_thai'] ?? '') == 'Chờ xử lý' ? 'btn-primary' : 'btn-secondary' ?>">
                    Chờ xử lý
                </a>
                <a href="<?= url('khach/donhang?trang_thai=Tiếp nhận') ?>" 
                   class="btn <?= ($_GET['trang_thai'] ?? '') == 'Tiếp nhận' ? 'btn-primary' : 'btn-secondary' ?>">
                    Tiếp nhận
                </a>
                <a href="<?= url('khach/donhang?trang_thai=Hoàn thành') ?>" 
                   class="btn <?= ($_GET['trang_thai'] ?? '') == 'Hoàn thành' ? 'btn-primary' : 'btn-secondary' ?>">
                    Hoàn thành
                </a>
                <a href="<?= url('khach/donhang?trang_thai=Đã trả') ?>" 
                   class="btn <?= ($_GET['trang_thai'] ?? '') == 'Đã trả' ? 'btn-primary' : 'btn-secondary' ?>">
                    Đã trả
                </a>
            </div>

            <?php if (empty($donHang)): ?>
                <div class="empty-state" style="padding:40px;">
                    <div style="font-size:60px;">💭</div>
                    <h3>Không có đơn hàng nào</h3>
                    <p style="color:#666;">Không tìm thấy đơn hàng với trạng thái "<?= e($_GET['trang_thai'] ?? '') ?>"</p>
                </div>
            <?php else: ?>
            <!-- Danh sách đơn hàng dạng card -->
            <div class="order-list">
                <?php foreach ($donHang as $d): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">#<?= $d['MaPhieu'] ?></span>
                        <?php
                        $trangThaiGoc = $d['TinhTrang'] ?? 'Chờ xử lý';
                        // Khách chỉ thấy 4 trạng thái: Chờ xử lý, Tiếp nhận, Hoàn thành, Đã trả
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
                        $class = 'status-pending';
                        if ($trangThai == 'Tiếp nhận') $class = 'status-processing';
                        elseif ($trangThai == 'Hoàn thành' || $trangThai == 'Đã trả') $class = 'status-done';
                        ?>
                        <span class="status-badge <?= $class ?>"><?= e($trangThai) ?></span>
                    </div>
                    <div class="order-body">
                        <div class="order-info">
                            <p><strong>🖥️ Thiết bị:</strong> <?= e($d['TenSanPham'] ?? 'N/A') ?></p>
                            <p><strong>📅 Ngày nhận:</strong> <?= date('d/m/Y', strtotime($d['NgayNhan'])) ?></p>
                            <?php if ($trangThaiGoc === 'Đã trả'): ?>
                            <p><strong>📅 Ngày trả:</strong> <?= $d['NgayTra'] ? date('d/m/Y', strtotime($d['NgayTra'])) : '' ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="order-price">
                            <span class="price"><?= number_format($d['TongTien'] ?? 0, 0, ',', '.') ?>đ</span>
                        </div>
                    </div>
                    <div class="order-footer">
                        <a href="<?= url('khach/xemdon/' . $d['MaPhieu']) ?>" class="btn btn-info btn-sm">
                            Xem chi tiết →
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.order-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.order-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.order-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.order-header {
    background: #f8f9fa;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e0e0e0;
}

.order-id {
    font-weight: bold;
    font-size: 16px;
}

.order-body {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-info p {
    margin: 5px 0;
    color: #555;
}

.order-price .price {
    font-size: 22px;
    font-weight: bold;
    color: var(--primary-red);
}

.order-footer {
    padding: 10px 15px;
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    text-align: right;
}

@media (max-width: 600px) {
    .order-body {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>
