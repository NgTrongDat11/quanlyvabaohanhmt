<div class="page-header">
    <div>
        <h1>Phiếu Đã Hoàn Thành</h1>
        <p>Danh sách phiếu đã xử lý xong</p>
    </div>
    <a href="<?= url('ktv') ?>" class="btn btn-secondary">← Về trang chính</a>
</div>

<div class="card">
    <div class="card-body">
        <?php
        $perPage = 10;
        $pagPage = max(1, intval($_GET['trang'] ?? 1));
        $pagTotal = count($phieu);
        $pagTotalPages = max(1, ceil($pagTotal / $perPage));
        $pagPage = min($pagPage, $pagTotalPages);
        $offset = ($pagPage - 1) * $perPage;
        $pagedItems = array_slice($phieu, $offset, $perPage);
        ?>
        <?php if (empty($phieu)): ?>
            <div class="empty-state">
                <p style="font-size:48px;">✅</p>
                <p>Chưa có phiếu hoàn thành</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã Phiếu</th>
                            <th>Sản Phẩm</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Ngày Nhận</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagedItems as $idx => $p): ?>
                        <tr>
                            <td><?= $offset + $idx + 1 ?></td>
                            <td><strong>#<?= $p['MaPhieu'] ?></strong></td>
                            <td><?= e($p['TenSanPham'] ?? 'N/A') ?></td>
                            <td><?= e($p['TenKhachHang'] ?? '') ?></td>
                            <td><?= e($p['SDT_KhachHang'] ?? '') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></td>
                            <td class="text-success"><strong><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                            <td>
                                <a href="<?= url('ktv/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem chi tiết</a>
                                <a href="<?= url('ktv/quaylai/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-warning" style="margin-left:4px;"
                                   onclick="return confirm('Quay lại tiếp nhận phiếu #<?= $p['MaPhieu'] ?>?')"> Tiếp nhận lại</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pagPerPage = $perPage;
            $pagBaseUrl = url('ktv/hoanthanh');
            $pagParams = [];
            include ROOT_PATH . '/app/views/partials/pagination.php';
            ?>
        <?php endif; ?>
    </div>
</div>
