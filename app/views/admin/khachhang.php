<div class="page-header">
    <div>
        <h1>Quản Lý Khách Hàng</h1>
        <p>Danh sách khách hàng</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('modalThemKH').classList.add('show')">+ Thêm Khách Hàng</button>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($khachHangs)): ?>
            <div class="empty-state">
                <p>👥</p>
                <p>Chưa có khách hàng nào</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã KH</th>
                            <th>Tên Khách Hàng</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Ghi Chú</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($khachHangs as $kh): ?>
                        <tr>
                            <td><strong>#<?= $kh['MaKhachHang'] ?></strong></td>
                            <td><?= e($kh['TenKhachHang']) ?></td>
                            <td><?= e($kh['SoDienThoai']) ?></td>
                            <td><?= e($kh['DiaChi']) ?></td>
                            <td><?= e($kh['GhiChu'] ?? '') ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view" title="Xem">👁</button>
                                    <button class="action-btn edit" title="Sửa">✏</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Thêm Khách Hàng -->
<div id="modalThemKH" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Thêm Khách Hàng Mới</h3>
            <button class="modal-close" onclick="document.getElementById('modalThemKH').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/luukhachhang') ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label>Tên khách hàng *</label>
                    <input type="text" name="TenKhachHang" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" name="SoDienThoai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control">
                </div>
                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="GhiChu" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalThemKH').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>
