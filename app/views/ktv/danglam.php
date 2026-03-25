<div class="page-header">
    <div>
        <h1>Phiếu Đang Xử Lý</h1>
        <p>Danh sách phiếu Tiếp nhận chữa</p>
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
                <p style="font-size:48px;">🔧</p>
                <p>Không có phiếu đang xử lý</p>
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
                            <th>Trạng Thái</th>
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
                            <td>
                                <?php
                                $tt = $p['TinhTrang'] ?? '';
                                $color = '#17a2b8';
                                if ($tt === 'Chờ báo giá') $color = '#fd7e14';
                                if ($tt === 'Tiếp nhận') $color = '#007bff';
                                ?>
                                <span style="background:<?= $color ?>;color:#fff;padding:2px 8px;border-radius:12px;font-size:12px;"><?= e($tt) ?></span>
                            </td>
                            <td><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <a href="<?= url('ktv/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Chi tiết</a>
                                    <?php if ($tt === 'Tiếp nhận'): ?>
                                        <?php if (floatval($p['TongTien'] ?? 0) > 0): ?>
                                        <a href="<?= url('ktv/hoantat/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-success"
                                           onclick="return confirm('Xác nhận hoàn thành?')">Hoàn thành</a>
                                        <?php else: ?>
                                        <span class="btn btn-sm btn-secondary" style="opacity:0.6;cursor:not-allowed;" title="Chưa có chi phí sửa chữa">Hoàn thành</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pagPerPage = $perPage;
            $pagBaseUrl = url('ktv/danglam');
            $pagParams = [];
            include ROOT_PATH . '/app/views/partials/pagination.php';
            ?>
        <?php endif; ?>
    </div>
</div>
