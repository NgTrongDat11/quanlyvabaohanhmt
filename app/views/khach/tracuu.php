<div class="page-header">
    <div>
        <h1>Tra Cứu Đơn Hàng</h1>
        <p>Nhập mã phiếu hoặc số điện thoại để tra cứu</p>
    </div>
    <a href="<?= url('khach') ?>" class="btn btn-secondary">← Trang chủ</a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Form tra cứu -->
        <form method="GET" action="<?= url('khach/tracuu') ?>" class="search-form">
            <div style="display:flex; gap:10px; max-width:600px; margin:0 auto;">
                <input type="text" name="q" class="form-control" style="flex:1; font-size:18px; padding:15px;" 
                       placeholder="Nhập mã phiếu hoặc số điện thoại..." 
                       value="<?= e($_GET['q'] ?? '') ?>" required>
                <button type="submit" class="btn btn-primary" style="padding:15px 30px; font-size:18px;">
                    🔍 Tìm
                </button>
            </div>
        </form>

        <?php if (isset($_GET['q'])): ?>
            <?php if (empty($ketQua)): ?>
                <div class="empty-state" style="margin-top:40px;">
                    <div style="font-size:60px;">🔍</div>
                    <h3>Không tìm thấy kết quả</h3>
                    <p style="color:#666;">Không tìm thấy đơn hàng với từ khóa "<?= e($_GET['q']) ?>"</p>
                </div>
            <?php else: ?>
                <div style="margin-top:30px;">
                    <h3>Kết quả tìm kiếm (<?= count($ketQua) ?> đơn)</h3>
                    
                    <div class="order-list" style="margin-top:20px;">
                        <?php foreach ($ketQua as $d): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#<?= $d['MaPhieu'] ?></span>
                                <?php
                                $trangThaiGoc = $d['TinhTrang'] ?? 'Chờ xử lý';
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
                                $class = 'status-pending';
                                if ($trangThai == 'Tiếp nhận') $class = 'status-processing';
                                elseif ($trangThai == 'Hoàn thành' || $trangThai == 'Đã trả') $class = 'status-done';
                                ?>
                                <span class="status-badge <?= $class ?>"><?= e($trangThai) ?></span>
                            </div>
                            <div class="order-body">
                                <div class="order-info">
                                    <p><strong>👤 Khách hàng:</strong> <?= e($d['TenKhachHang'] ?? '') ?></p>
                                    <p><strong>📞 SĐT:</strong> <?= e($d['SDT_KhachHang'] ?? '') ?></p>
                                    <p><strong>🖥️ Thiết bị:</strong> <?= e($d['TenSanPham'] ?? 'N/A') ?></p>
                                    <p><strong>📅 Ngày nhận:</strong> <?= date('d/m/Y', strtotime($d['NgayNhan'])) ?></p>
                                </div>
                                <div class="order-price">
                                    <span class="price"><?= number_format($d['TongTien'] ?? 0, 0, ',', '.') ?>đ</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <a href="<?= url('khach/xemdon/' . $d['MaPhieu']) ?>" class="btn btn-info">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align:center; margin-top:40px; color:#666;">
                <div style="font-size:80px; margin-bottom:20px;">🔎</div>
                <p>Nhập mã phiếu (VD: 1, 2, 3...) hoặc số điện thoại để tra cứu đơn hàng</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.search-form {
    padding: 30px 0;
    border-bottom: 1px solid #eee;
}

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
</style>
