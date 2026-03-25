<div class="page-header">
    <div>
        <h1>Danh Sách Phiếu Sửa Chữa</h1>
        <p>Tất cả phiếu trong hệ thống</p>
    </div>
    <a href="<?= url('nhanvien') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<!-- Bộ lọc -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="<?= url('nhanvien/danhsach') ?>" class="filter-form">
            <div class="row" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
                <div class="form-group" style="flex:1; min-width:150px;">
                    <label>Từ ngày</label>
                    <input type="date" name="tu_ngay" class="form-control" value="<?= e($_GET['tu_ngay'] ?? '') ?>">
                </div>
                <div class="form-group" style="flex:1; min-width:150px;">
                    <label>Đến ngày</label>
                    <input type="date" name="den_ngay" class="form-control" value="<?= e($_GET['den_ngay'] ?? '') ?>">
                </div>
                <div class="form-group" style="flex:1; min-width:150px;">
                    <label>Trạng thái</label>
                    <select name="trang_thai" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="Chờ xử lý" <?= ($_GET['trang_thai'] ?? '') == 'Chờ xử lý' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="Đã phân công" <?= ($_GET['trang_thai'] ?? '') == 'Đã phân công' ? 'selected' : '' ?>>Đã phân công</option>
                        <option value="Đang kiểm tra" <?= ($_GET['trang_thai'] ?? '') == 'Đang kiểm tra' ? 'selected' : '' ?>>Đang kiểm tra</option>
                        <option value="Tiếp nhận" <?= ($_GET['trang_thai'] ?? '') == 'Tiếp nhận' ? 'selected' : '' ?>>Tiếp nhận</option>
                        <option value="Hoàn thành" <?= ($_GET['trang_thai'] ?? '') == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="Đã trả" <?= ($_GET['trang_thai'] ?? '') == 'Đã trả' ? 'selected' : '' ?>>Đã trả</option>
                    </select>
                </div>
                <div class="form-group" style="flex:2; min-width:200px;">
                    <label>Tìm kiếm</label>
                    <input type="text" name="q" class="form-control" placeholder="Mã phiếu, tên KH, SĐT, sản phẩm..." value="<?= e($_GET['q'] ?? '') ?>">
                </div>
                <div class="form-group" style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary" style="padding:8px 24px;font-size:15px;">Tìm</button>
                    <a href="<?= url('nhanvien/danhsach') ?>" class="btn btn-secondary" style="padding:8px 20px;font-size:15px;">Xóa lọc</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách phiếu -->
<div class="card">
    <div class="card-body">
        <?php
        // Phân trang
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
                <p>Không tìm thấy phiếu nào</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã Phiếu</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Sản Phẩm</th>
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
                            <td><?= e($p['TenKhachHang'] ?? '') ?></td>
                            <td><?= e($p['SDT_KhachHang'] ?? '') ?></td>
                            <td><?= e($p['TenSanPham'] ?? '') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></td>
                            <td>
                                <?php
                                $trangThai = $p['TinhTrang'] ?? 'Chờ xử lý';
                                $class = 'status-pending';
                                if ($trangThai == 'Tiếp nhận') $class = 'status-processing';
                                elseif ($trangThai == 'Hoàn thành') $class = 'status-done';
                                elseif ($trangThai == 'Đã trả') $class = 'status-done';
                                ?>
                                <span class="status-badge <?= $class ?>"><?= e($trangThai) ?></span>
                            </td>
                            <td><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</td>
                            <td>
                                <a href="<?= url('nhanvien/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pagPerPage = $perPage;
            $pagBaseUrl = url('nhanvien/danhsach');
            $pagParams = array_filter([
                'tu_ngay'    => $_GET['tu_ngay'] ?? '',
                'den_ngay'   => $_GET['den_ngay'] ?? '',
                'trang_thai' => $_GET['trang_thai'] ?? '',
                'q'          => $_GET['q'] ?? '',
            ]);
            include ROOT_PATH . '/app/views/partials/pagination.php';
            ?>
        <?php endif; ?>
    </div>
</div>
