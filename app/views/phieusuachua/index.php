<?php 
$user = $_SESSION['user'] ?? null;
include ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="page-header">
    <h1><?= e($title) ?></h1>
</div>

<?php if (!empty($phieus)): ?>
    <table>
        <thead>
            <tr>
                <th>Mã phiếu</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Sản phẩm</th>
                <th>Serial</th>
                <th>Ngày nhận</th>
                <th>Ngày trả</th>
                <th>NV Nhận</th>
                <th>KTV</th>
                <th>Tình trạng</th>
                <th>Tổng tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($phieus as $phieu): ?>
                <tr>
                    <td>#<?= e($phieu['MaPhieu']) ?></td>
                    <td><?= e($phieu['TenKhachHang']) ?></td>
                    <td><?= e($phieu['SDT_KhachHang']) ?></td>
                    <td><?= e($phieu['TenSanPham']) ?></td>
                    <td><?= e($phieu['MaSerial']) ?></td>
                    <td><?= date('d/m/Y', strtotime($phieu['NgayNhan'])) ?></td>
                    <td><?= $phieu['NgayTra'] ? date('d/m/Y', strtotime($phieu['NgayTra'])) : '-' ?></td>
                    <td><?= e($phieu['TenNVNhan']) ?></td>
                    <td><?= e($phieu['TenKTV']) ?></td>
                    <td><span class="badge"><?= e($phieu['TinhTrang']) ?></span></td>
                    <td><?= number_format($phieu['TongTien'], 0, ',', '.') ?>đ</td>
                    <td>
                        <a href="<?= url('phieusuachua/show/' . $phieu['MaPhieu']) ?>" class="btn-small">Xem</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty-state">
        <p>Chưa có phiếu sửa chữa nào.</p>
    </div>
<?php endif; ?>

<?php include ROOT_PATH . '/app/views/partials/footer.php'; ?>
