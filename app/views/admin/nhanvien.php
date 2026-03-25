<div class="page-header">
    <div>
        <h1>Quản Lý Nhân Viên</h1>
        <p>Danh sách nhân viên công ty</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('modalThemNV').classList.add('show')">+ Thêm Nhân Viên</button>
</div>
<?php if ($flash = flash('success')): ?>
    <div style="background:#d4edda;color:#155724;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">✅ <?= e($flash) ?></div>
<?php elseif ($flash = flash('error')): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #f5c6cb;">❌ <?= e($flash) ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <?php if (empty($nhanViens)): ?>
            <div class="empty-state">
                <p>👨‍💼</p>
                <p>Chưa có nhân viên nào</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã NV</th>
                            <th>Tên Nhân Viên</th>
                            <th>Chức Vụ</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nhanViens as $nv): ?>
                        <tr>
                            <td><strong>#<?= $nv['MaNhanVien'] ?></strong></td>
                            <td><?= e($nv['TenNhanVien']) ?></td>
                            <td>
                                <?php
                                $chucVu = $nv['ChucVu'] ?? '';
                                $badgeClass = 'badge-waiting';
                                if ($chucVu == 'Quản lý') $badgeClass = 'badge-done';
                                elseif ($chucVu == 'Kỹ thuật viên') $badgeClass = 'badge-processing';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= e($chucVu) ?></span>
                            </td>
                            <td><?= e($nv['SoDienThoai']) ?></td>
                            <td><?= e($nv['DiaChi']) ?></td>
                            <td>
                                <div class="action-btns">
                                    <form method="POST" action="<?= url('admin/xoanhanvien/' . $nv['MaNhanVien']) ?>" style="display:inline;" onsubmit="return confirm('Xóa nhân viên này?')">
                                        <button type="submit" class="action-btn" style="color:#dc3545;background:none;border:none;cursor:pointer;font-size:inherit;padding:0;" title="Xóa">🗑</button>
                                    </form>
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

<!-- Modal Thêm Nhân Viên -->
<div id="modalThemNV" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3>Thêm Nhân Viên Mới</h3>
            <button class="modal-close" onclick="document.getElementById('modalThemNV').classList.remove('show')">&times;</button>
        </div>
        <form method="POST" action="<?= url('admin/luunhanvien') ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label>Tên nhân viên *</label>
                    <input type="text" name="TenNhanVien" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Chức vụ *</label>
                    <select name="ChucVu" class="form-control" required>
                        <option value="">-- Chọn chức vụ --</option>
                        <option value="Quản lý">Quản lý</option>
                        <option value="Kỹ thuật viên">Kỹ thuật viên</option>
                        <option value="Nhân viên tiếp nhận">Nhân viên tiếp nhận</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" name="SoDienThoai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalThemNV').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>
