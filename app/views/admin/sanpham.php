<div class="page-header">
    <div>
        <h1>Quản Lý Sản Phẩm</h1>
        <p>Danh sách tất cả thiết bị</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($sanPhams)): ?>
            <div class="empty-state">
                <p>💻</p>
                <p>Chưa có sản phẩm nào</p>
            </div>
        <?php else: ?>
            <?php
            // Phân trang
            $perPage = 10;
            $pagPage = max(1, intval($_GET['trang'] ?? 1));
            $pagTotal = count($sanPhams);
            $pagTotalPages = max(1, ceil($pagTotal / $perPage));
            $pagPage = min($pagPage, $pagTotalPages);
            $offset = ($pagPage - 1) * $perPage;
            $pagedItems = array_slice($sanPhams, $offset, $perPage);
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="stt-col">STT</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Serial</th>
                            <th>Trạng Thái</th>
                            <th>Ghi Chú</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagedItems as $idx => $sp): ?>
                        <tr>
                            <td class="stt-col"><?= $offset + $idx + 1 ?></td>
                            <td>
                                <strong><?= e($sp['TenSanPham']) ?></strong>
                            </td>
                            <td><code><?= e($sp['MaSerial']) ?></code></td>
                            <td>
                                <?php
                                    $tt = $sp['TinhTrang'] ?? 'Chờ xử lý';
                                    $badgeClass = 'badge-waiting';
                                    switch ($tt) {
                                        case 'Đã phân công':   $badgeClass = 'badge-assigned'; break;
                                        case 'Đang kiểm tra':  $badgeClass = 'badge-checking'; break;
                                        case 'Chờ báo giá':    $badgeClass = 'badge-quoting'; break;
                                        case 'Tiếp nhận':       $badgeClass = 'badge-processing'; break;
                                        case 'Hoàn thành':     $badgeClass = 'badge-done'; break;
                                        case 'Đã trả':         $badgeClass = 'badge-returned'; break;
                                    }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= e($tt) ?></span>
                            </td>
                            <td style="max-width:200px;color:#666;font-size:13px;">
                                <?php 
                                    $ghiChu = $sp['GhiChu'] ?? '';
                                    if (mb_strlen($ghiChu) > 30) {
                                        echo '<span title="' . e($ghiChu) . '">' . e(mb_substr($ghiChu, 0, 30)) . '...</span>';
                                    } else {
                                        echo e($ghiChu);
                                    }
                                ?>
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick='moModalSua(<?= json_encode([
                                        "MaSanPham" => $sp["MaSanPham"],
                                        "TenSanPham" => $sp["TenSanPham"],
                                        "MaSerial" => $sp["MaSerial"],
                                        "GhiChu" => $sp["GhiChu"] ?? "",
                                        "HinhAnh" => $sp["HinhAnh"] ?? ""
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                    Sửa
                                </button>
                                <form method="POST" action="<?= url('admin/xoasanpham/' . $sp['MaSanPham']) ?>" style="display:inline;" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pagPerPage = $perPage;
            $pagBaseUrl = url('admin/sanpham');
            $pagParams = [];
            include ROOT_PATH . '/app/views/partials/pagination.php';
            ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div id="modalSuaSP" class="modal">
    <div class="modal-content" style="max-width:600px;">
        <div class="modal-header">
            <h3>Sửa Sản Phẩm</h3>
            <button class="modal-close" onclick="document.getElementById('modalSuaSP').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/capnhatsanpham') ?>" enctype="multipart/form-data">
            <input type="hidden" name="MaSanPham" id="sua_MaSanPham">
            <input type="hidden" name="HinhAnhCu" id="sua_HinhAnhCu">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Tên sản phẩm *</label>
                        <input type="text" name="TenSanPham" id="sua_TenSanPham" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mã Serial *</label>
                        <input type="text" name="MaSerial" id="sua_MaSerial" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="GhiChu" id="sua_GhiChu" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalSuaSP').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function moModalSua(sp) {
    document.getElementById('sua_MaSanPham').value = sp.MaSanPham;
    document.getElementById('sua_TenSanPham').value = sp.TenSanPham;
    document.getElementById('sua_MaSerial').value = sp.MaSerial;
    document.getElementById('sua_GhiChu').value = sp.GhiChu;
    document.getElementById('sua_HinhAnhCu').value = sp.HinhAnh;
    
    document.getElementById('modalSuaSP').classList.add('show');
}
</script>
