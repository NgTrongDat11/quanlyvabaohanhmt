<?php 
$user = $_SESSION['user'] ?? null;
include ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="page-header">
    <h1><?= e($title) ?></h1>
    <a href="<?= url('phieusuachua') ?>" class="btn">← Quay lại</a>
</div>

<!-- Thông tin phiếu -->
<div class="detail-card">
    <h2>Thông tin phiếu sửa chữa</h2>
    <div class="two-columns">
        <div>
            <table class="detail-table">
                <tr>
                    <th>Mã phiếu:</th>
                    <td><strong>#<?= e($phieu['MaPhieu']) ?></strong></td>
                </tr>
                <tr>
                    <th>Khách hàng:</th>
                    <td>
                        <a href="<?= url('khachhang/show/' . $phieu['MaKhachHang']) ?>">
                            <?= e($phieu['TenKhachHang']) ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>SĐT:</th>
                    <td><?= e($phieu['SDT_KhachHang']) ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ:</th>
                    <td><?= e($phieu['DiaChi_KH']) ?></td>
                </tr>
            </table>
        </div>
        <div>
            <table class="detail-table">
                <tr>
                    <th>Ngày nhận:</th>
                    <td><?= date('d/m/Y H:i', strtotime($phieu['NgayNhan'])) ?></td>
                </tr>
                <tr>
                    <th>Ngày trả:</th>
                    <td><?= $phieu['NgayTra'] ? date('d/m/Y H:i', strtotime($phieu['NgayTra'])) : '<span class="badge badge-warning">Chưa trả</span>' ?></td>
                </tr>
                <tr>
                    <th>NV Nhận:</th>
                    <td><?= e($phieu['TenNVNhan']) ?></td>
                </tr>
                <tr>
                    <th>KTV:</th>
                    <td><?= e($phieu['TenKTV']) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Thông tin sản phẩm -->
<div class="detail-card">
    <h2>Thông tin sản phẩm</h2>
    <table class="detail-table">
        <tr>
            <th>Tên sản phẩm:</th>
            <td><?= e($phieu['TenSanPham']) ?></td>
        </tr>
        <tr>
            <th>Loại:</th>
            <td><?= e($phieu['LoaiSanPham']) ?></td>
        </tr>
        <tr>
            <th>Hãng:</th>
            <td><?= e($phieu['HangSanXuat']) ?></td>
        </tr>
        <tr>
            <th>Serial:</th>
            <td><strong><?= e($phieu['MaSerial']) ?></strong></td>
        </tr>
        <tr>
            <th>Loại dịch vụ:</th>
            <td><?= e($phieu['LoaiDichVu']) ?></td>
        </tr>
        <tr>
            <th>Tình trạng:</th>
            <td><span class="badge badge-warning"><?= e($phieu['TinhTrang']) ?></span></td>
        </tr>
        <tr>
            <th>Phụ kiện kèm theo:</th>
            <td><?= e($phieu['PhuKienKemTheo']) ?></td>
        </tr>
    </table>
</div>

<!-- Chi tiết sửa chữa -->
<div class="detail-card">
    <h2>Chi tiết sửa chữa / Hạng mục thay thế</h2>
    <?php if (!empty($chiTiet)): ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Hạng mục</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chiTiet as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($item['HangMuc']) ?></td>
                        <td><?= e($item['SoLuong']) ?></td>
                        <td><?= number_format($item['DonGia'], 0, ',', '.') ?>đ</td>
                        <td><?= number_format($item['ThanhTien'], 0, ',', '.') ?>đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                    <td><strong class="text-danger"><?= number_format($phieu['TongTien'], 0, ',', '.') ?>đ</strong></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>Chưa có chi tiết sửa chữa.</p>
        <p><strong>Tổng tiền: <?= number_format($phieu['TongTien'], 0, ',', '.') ?>đ</strong></p>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . '/app/views/partials/footer.php'; ?>
