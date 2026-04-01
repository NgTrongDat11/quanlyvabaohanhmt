<div class="page-header">
    <div>
        <h1>Quản Lý Phiếu Sửa Chữa</h1>
        <p>Danh sách tất cả phiếu sửa chữa</p>
    </div>
    <a href="<?= url('admin/taophieu') ?>" class="btn btn-primary">+ Tạo Biên Nhận Mới</a>
</div>

<!-- Bộ lọc -->
<div class="card mb-20">
    <div class="card-body">
        <div class="tabs">
            <a href="?trang_thai=" class="tab <?= empty($_GET['trang_thai']) ? 'active' : '' ?>">Tất cả</a>
            <a href="?trang_thai=Chờ xử lý" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Chờ xử lý' ? 'active' : '' ?>">Chờ xử lý</a>
            <a href="?trang_thai=Đã phân công" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Đã phân công' ? 'active' : '' ?>">Đã phân công</a>
            <a href="?trang_thai=Đang kiểm tra" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Đang kiểm tra' ? 'active' : '' ?>">Đang kiểm tra</a>
            <a href="?trang_thai=Tiếp nhận" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Tiếp nhận' ? 'active' : '' ?>">Tiếp nhận</a>
            <a href="?trang_thai=Hoàn thành" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Hoàn thành' ? 'active' : '' ?>">Hoàn thành</a>
            <a href="?trang_thai=Đã trả" class="tab <?= ($_GET['trang_thai'] ?? '') == 'Đã trả' ? 'active' : '' ?>">Đã trả</a>
        </div>
    </div>
</div>

<!-- Danh sách -->
<div class="card">
    <div class="card-body">
        <?php
        // Lọc theo trạng thái trước
        $trangThaiFilter = $_GET['trang_thai'] ?? '';
        $filtered = $phieu;
        if ($trangThaiFilter) {
            $filtered = array_filter($phieu, fn($p) => ($p['TinhTrang'] ?? '') == $trangThaiFilter);
            $filtered = array_values($filtered);
        }

        // Phân trang
        $perPage = 10;
        $pagPage = max(1, intval($_GET['trang'] ?? 1));
        $pagTotal = count($filtered);
        $pagTotalPages = max(1, ceil($pagTotal / $perPage));
        $pagPage = min($pagPage, $pagTotalPages);
        $offset = ($pagPage - 1) * $perPage;
        $pagedItems = array_slice($filtered, $offset, $perPage);
        ?>

        <?php if (empty($filtered)): ?>
            <div class="empty-state">
                <p>📋</p>
                <p>Chưa có phiếu nào</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="stt-col">STT</th>
                            <th>Mã Phiếu</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Sản Phẩm</th>
                            <th>Serial</th>
                            <th>Ghi Chú</th>
                            <th>Ngày Nhận</th>
                            <th>KTV Phân Công</th>
                            <th>Trạng Thái</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagedItems as $idx => $p): ?>
                        <tr>
                            <td class="stt-col"><?= $offset + $idx + 1 ?></td>
                            <td><strong>#<?= $p['MaPhieu'] ?></strong></td>
                            <td><?= e($p['TenKhachHang'] ?? 'N/A') ?></td>
                            <td><?= e($p['SDT_KhachHang'] ?? '') ?></td>
                            <td>
                                <?= e($p['TenSanPham'] ?? 'N/A') ?>
                            </td>
                            <td><?= e($p['MaSerial'] ?? '') ?></td>
                            <td style="max-width:180px;"><?= e($p['GhiChuTinhTrang'] ?? '') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($p['NgayNhan'])) ?></td>
                            <td>
                                <?php $ktvAssigned = $p['TenDangNhapKTV'] ?? ''; ?>
                                <?php if ($ktvAssigned): ?>
                                    <span style="color:#155724;font-weight:600;"><?= e($ktvAssigned) ?></span><br>
                                    <button class="btn btn-sm" style="font-size:11px;padding:2px 8px;margin-top:3px;" onclick="moModalPhanCong(<?= $p['MaPhieu'] ?>, '<?= e($ktvAssigned) ?>')">Thay đổi</button>
                                <?php else: ?>
                                    <span style="color:#aaa;">Chưa phân</span><br>
                                    <button class="btn btn-sm btn-primary" style="font-size:11px;padding:2px 8px;margin-top:3px;" onclick="moModalPhanCong(<?= $p['MaPhieu'] ?>, '')">+ Phân công</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $mp = $p['MaPhieu'];
                                $phieuBH = $bhMap[$mp] ?? [];
                                $phieuDT = $dtMap[$mp] ?? [];
                                $trangThai = $p['TinhTrang'] ?? 'Chờ xử lý';

                                if (!empty($phieuBH) && !in_array($trangThai, ['Hoàn thành', 'Đã trả'])):
                                ?>
                                    <span class="badge badge-baohanh">Gửi bảo hành</span>
                                <?php elseif (!empty($phieuDT) && !in_array($trangThai, ['Hoàn thành', 'Đã trả'])): ?>
                                    <span class="badge badge-doitac">Gửi đối tác</span>
                                <?php else:
                                    $badgeClass = 'badge-waiting';
                                    switch ($trangThai) {
                                        case 'Đã phân công':   $badgeClass = 'badge-assigned'; break;
                                        case 'Đang kiểm tra':  $badgeClass = 'badge-checking'; break;
                                        case 'Chờ báo giá':    $badgeClass = 'badge-quoting'; break;
                                        case 'Tiếp nhận':       $badgeClass = 'badge-processing'; break;
                                        case 'Hoàn thành':     $badgeClass = 'badge-done'; break;
                                        case 'Đã trả':         $badgeClass = 'badge-returned'; break;
                                    }
                                ?>
                                    <span class="badge <?= $badgeClass ?>"><?= e($trangThai) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right"><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</td>
                            <td style="text-align:center;">
                                <div style="display:inline-flex; gap:6px; align-items:center; justify-content:center;">
                                    <a href="<?= url('admin/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info" style="display:inline-block; min-width:56px; padding:6px 12px; line-height:1.5; font-size:13px; font-weight:600; box-sizing:border-box;">Xem</a>
                                    <form method="POST" action="<?= url('admin/xoaphieu/' . $p['MaPhieu']) ?>" style="display:inline-flex; margin:0;" onsubmit="return confirm('Xóa phiếu #<?= $p['MaPhieu'] ?>? Hành động này không thể hoàn tác!')">
                                        <button type="submit" class="btn btn-sm btn-danger" style="display:inline-block; min-width:56px; padding:6px 12px; line-height:1.5; font-size:13px; font-weight:600; box-sizing:border-box; font-family:inherit;">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pagPerPage = $perPage;
            $pagBaseUrl = url('admin/phieusuachua');
            $pagParams = $trangThaiFilter ? ['trang_thai' => $trangThaiFilter] : [];
            include ROOT_PATH . '/app/views/partials/pagination.php';
            ?>
        <?php endif; ?>
    </div>
</div>
<!-- Modal Phân Công KTV -->
<div id="modalPhanCong" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:30px;min-width:380px;max-width:480px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,.2);">
        <h3 style="margin:0 0 20px;">👤 Phân Công Kỹ Thuật Viên</h3>
        <form method="POST" action="<?= url('admin/phancongktv') ?>">
            <input type="hidden" name="MaPhieu" id="pcMaPhieu" value="">
            <div class="form-group">
                <label>Phiếu sửa chữa</label>
                <input type="text" id="pcTenPhieu" class="form-control" readonly style="background:#f5f5f5;">
            </div>
            <div class="form-group">
                <label>Chọn KTV *</label>
                <select name="TenDangNhapKTV" class="form-control" required>
                    <option value="">-- Chọn kỹ thuật viên --</option>
                    <?php if (!empty($dsKTV)): ?>
                        <?php foreach ($dsKTV as $username => $acc): ?>
                        <option value="<?= e($username) ?>"><?= e($acc['HoTen'] ?? $acc['TenNhanVien'] ?? $username) ?> (<?= e($username) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="dongModalPhanCong()">Hủy</button>
                <button type="submit" class="btn btn-primary">Xác nhận phân công</button>
            </div>
        </form>
    </div>
</div>

<script>
function moModalPhanCong(maPhieu, ktvHienTai) {
    document.getElementById('pcMaPhieu').value = maPhieu;
    document.getElementById('pcTenPhieu').value = 'Phiếu #' + maPhieu + (ktvHienTai ? ' (KTV hiện tại: ' + ktvHienTai + ')' : '');
    const modal = document.getElementById('modalPhanCong');
    modal.style.display = 'flex';
    // Pre-select current KTV
    if (ktvHienTai) {
        const sel = modal.querySelector('select[name="TenDangNhapKTV"]');
        for (let opt of sel.options) { if (opt.value === ktvHienTai) { opt.selected = true; break; } }
    }
}
function dongModalPhanCong() {
    document.getElementById('modalPhanCong').style.display = 'none';
}
document.getElementById('modalPhanCong').addEventListener('click', function(e) {
    if (e.target === this) dongModalPhanCong();
});
</script>
