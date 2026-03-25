<?php 
$user = $_SESSION['user'] ?? null;
include ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="page-header">
    <h1><?= e($title) ?></h1>
    <a href="<?= url('khachhang') ?>" class="btn">← Quay lại</a>
</div>

<div class="detail-card">
    <h2>Thông tin khách hàng</h2>
    <table class="detail-table">
        <tr>
            <th>Mã khách hàng:</th>
            <td><?= e($khachHang['MaKhachHang']) ?></td>
        </tr>
        <tr>
            <th>Tên khách hàng:</th>
            <td><?= e($khachHang['TenKhachHang']) ?></td>
        </tr>
        <tr>
            <th>Số điện thoại:</th>
            <td><?= e($khachHang['SoDienThoai']) ?></td>
        </tr>
        <tr>
            <th>Địa chỉ:</th>
            <td><?= e($khachHang['DiaChi']) ?></td>
        </tr>
        <tr>
            <th>Ghi chú:</th>
            <td><?= e($khachHang['GhiChu']) ?></td>
        </tr>
    </table>
</div>

<div class="history-section">
    <h2>Lịch sử sửa chữa</h2>
    <?php if (!empty($lichSu)): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã phiếu</th>
                    <th>Sản phẩm</th>
                    <th>Serial</th>
                    <th>Ngày nhận</th>
                    <th>Ngày trả</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lichSu as $phieu): ?>
                    <tr>
                        <td>#<?= e($phieu['MaPhieu']) ?></td>
                        <td><?= e($phieu['TenSanPham']) ?></td>
                        <td><?= e($phieu['MaSerial']) ?></td>
                        <td><?= date('d/m/Y', strtotime($phieu['NgayNhan'])) ?></td>
                        <td><?= $phieu['NgayTra'] ? date('d/m/Y', strtotime($phieu['NgayTra'])) : '-' ?></td>
                        <td><?= number_format($phieu['TongTien'], 0, ',', '.') ?>đ</td>
                        <td>
                            <a href="<?= url('phieusuachua/show/' . $phieu['MaPhieu']) ?>">Xem</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Khách hàng chưa có lịch sử sửa chữa.</p>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . '/app/views/partials/footer.php'; ?>
