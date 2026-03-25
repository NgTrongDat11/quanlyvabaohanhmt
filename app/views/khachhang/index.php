<?php 
$user = $_SESSION['user'] ?? null;
include ROOT_PATH . '/app/views/partials/header.php'; 
?>

<div class="page-header">
    <h1><?= e($title) ?></h1>
    <div class="actions">
        <form action="<?= url('khachhang/search') ?>" method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." value="<?= e($keyword ?? '') ?>">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>
</div>

<?php if (isset($keyword) && $keyword): ?>
    <p>Kết quả tìm kiếm cho: <strong>"<?= e($keyword) ?>"</strong> 
       <a href="<?= url('khachhang') ?>">Xóa bộ lọc</a>
    </p>
<?php endif; ?>

<?php if (!empty($khachHangs)): ?>
    <table>
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Ghi chú</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($khachHangs as $kh): ?>
                <tr>
                    <td><?= e($kh['MaKhachHang']) ?></td>
                    <td><?= e($kh['TenKhachHang']) ?></td>
                    <td><?= e($kh['SoDienThoai']) ?></td>
                    <td><?= e($kh['DiaChi']) ?></td>
                    <td><?= e($kh['GhiChu']) ?></td>
                    <td>
                        <a href="<?= url('khachhang/show/' . $kh['MaKhachHang']) ?>" class="btn-small">Xem</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty-state">
        <p>Không tìm thấy khách hàng nào.</p>
    </div>
<?php endif; ?>

<?php include ROOT_PATH . '/app/views/partials/footer.php'; ?>
