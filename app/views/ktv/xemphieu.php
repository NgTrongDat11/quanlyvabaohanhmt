<div class="page-header">
    <div>
        <h1>Chi Tiết Phiếu #<?= $phieu['MaPhieu'] ?></h1>
        <p>Thông tin phiếu sửa chữa</p>
    </div>
    <a href="<?= url('ktv') ?>" class="btn btn-secondary">← Về trang chính</a>
</div>

<div class="row" style="display:flex; gap:20px; flex-wrap:wrap;">
    <!-- Thông tin phiếu -->
    <div class="col" style="flex:1; min-width:300px;">
        <div class="card">
            <div class="card-header">
                <h3>📋 Thông Tin Phiếu</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Mã Phiếu:</strong></td>
                        <td>#<?= $phieu['MaPhieu'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Sản Phẩm:</strong></td>
                        <td><?= e($phieu['TenSanPham'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Serial:</strong></td>
                        <td><?= e($phieu['MaSerial'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phụ kiện:</strong></td>
                        <td><?= e($phieu['PhuKienKemTheo'] ?? 'Không có') ?></td>
                    </tr>
                    <?php if (!empty($phieu['GhiChuTinhTrang'])): ?>
                    <tr>
                        <td><strong>Tình Trạng Máy:</strong></td>
                        <td><?= e($phieu['GhiChuTinhTrang']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Ngày Nhận:</strong></td>
                        <td><?= date('d/m/Y', strtotime($phieu['NgayNhan'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Trạng Thái:</strong></td>
                        <td>
                            <?php
                            $trangThai = $phieu['TinhTrang'] ?? 'Chờ xử lý';
                            $badgeClass = 'badge-waiting';
                            switch ($trangThai) {
                                case 'Đã phân công': $badgeClass = 'badge-assigned'; break;
                                case 'Đang kiểm tra': $badgeClass = 'badge-checking'; break;
                                case 'Chờ báo giá': $badgeClass = 'badge-quoting'; break;
                                case 'Tiếp nhận': $badgeClass = 'badge-processing'; break;
                                case 'Hoàn thành': $badgeClass = 'badge-done'; break;
                                case 'Đã trả': $badgeClass = 'badge-returned'; break;
                            }
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= e($trangThai) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tổng Tiền:</strong></td>
                        <td class="text-success" style="font-size:18px;"><strong><?= number_format($phieu['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <h3>👤 Khách Hàng</h3>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> <?= e($phieu['TenKhachHang'] ?? '') ?></p>
                <p><strong>SĐT:</strong> <?= e($phieu['SDT_KhachHang'] ?? '') ?></p>
                <p><strong>Địa chỉ:</strong> <?= e($phieu['DiaChi_KH'] ?? '') ?></p>
            </div>
        </div>
    </div>

    <!-- Chi tiết sửa chữa -->
    <div class="col" style="flex:1; min-width:300px;">
        <?php
        $tinhTrang = $phieu['TinhTrang'] ?? '';
        $coTheThemHangMuc = in_array($tinhTrang, ['Đang kiểm tra', 'Tiếp nhận', 'Hoàn thành']);
        ?>
        <div class="card">
            <div class="card-header">
                <h3>🔧 Chi Tiết Sửa Chữa</h3>
            </div>
            <div class="card-body">
                <?php if (empty($chiTiet)): ?>
                    <div class="empty-state">
                        <p>Chưa có chi tiết sửa chữa</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hạng Mục</th>
                                <th>SL</th>
                                <th>Đơn Giá</th>
                                <th>Thành Tiền</th>
                                <?php if ($coTheThemHangMuc): ?><th></th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tongTien = 0;
                            foreach ($chiTiet as $ct): 
                                $thanhTien = $ct['SoLuong'] * $ct['DonGia'];
                                $tongTien += $thanhTien;
                            ?>
                            <tr>
                                <td><?= e($ct['HangMuc'] ?? '') ?></td>
                                <td><?= $ct['SoLuong'] ?></td>
                                <td><?= number_format($ct['DonGia'], 0, ',', '.') ?>đ</td>
                                <td><?= number_format($thanhTien, 0, ',', '.') ?>đ</td>
                                <?php if ($coTheThemHangMuc): ?>
                                <td style="display:flex;gap:5px;">
                                    <button type="button" class="btn btn-info" style="padding:4px 10px; font-size:13px;" onclick="openEditModal(<?= $ct['MaChiTiet'] ?>, '<?= e($ct['HangMuc']) ?>', <?= $ct['SoLuong'] ?>, <?= $ct['DonGia'] ?>)">Sửa</button>
                                    <a href="<?= url('ktv/xoachitiet/' . $ct['MaChiTiet']) ?>"
                                       class="btn btn-danger" style="padding:4px 10px; font-size:13px;"
                                       onclick="return confirm('Xóa hạng mục này?')">Xóa</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="<?= $coTheThemHangMuc ? 4 : 3 ?>" style="text-align:right;"><strong>Tổng:</strong></td>
                                <td><strong><?= number_format($tongTien, 0, ',', '.') ?>đ</strong></td>
                                <?php if ($coTheThemHangMuc): ?><td></td><?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($coTheThemHangMuc): ?>
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <h3>➕ Thêm Hạng Mục Sửa Chữa</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('ktv/themchitiet/' . $phieu['MaPhieu']) ?>">
                    <div class="form-group">
                        <label>Tên hạng mục / linh kiện *</label>
                        <input type="text" name="HangMuc" class="form-control" required
                               placeholder="VD: Thay màn hình, thay pin, vệ sinh...">
                    </div>
                    <div class="row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Số lượng</label>
                            <input type="number" name="SoLuong" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Đơn giá (VNĐ)</label>
                            <input type="text" name="DonGia_display" class="form-control money-input" value="0" required>
                            <input type="hidden" name="DonGia" value="0">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" style="width:100%;">
                        ➕ Thêm Hạng Mục
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Nút hành động theo trạng thái -->
        <div style="margin-top:20px;">
            <?php if ($tinhTrang === 'Đã phân công'): ?>
                <a href="<?= url('ktv/batdau/' . $phieu['MaPhieu']) ?>"
                   class="btn btn-primary" style="width:100%; padding:15px; font-size:16px; text-align:center; display:block;"
                   onclick="return confirm('Bắt đầu kiểm tra phiếu này?')">
                    🔍 Bắt Đầu Kiểm Tra
                </a>

            <?php elseif ($tinhTrang === 'Đang kiểm tra'): ?>
                <form method="POST" action="<?= url('ktv/baogiahttp/' . $phieu['MaPhieu']) ?>"
                      onsubmit="return confirm('Bắt đầu sửa chữa phiếu này?')">
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:15px; font-size:16px;">
                        🔧 Tiếp Nhận
                    </button>
                </form>

            <?php elseif ($tinhTrang === 'Tiếp nhận'): ?>
                <?php if (floatval($phieu['TongTien'] ?? 0) > 0): ?>
                <form method="POST" action="<?= url('ktv/hoantat/' . $phieu['MaPhieu']) ?>"
                      onsubmit="return confirm('Xác nhận hoàn thành phiếu này?')">
                    <input type="hidden" name="MaPhieu" value="<?= $phieu['MaPhieu'] ?>">
                    <button type="submit" class="btn btn-success" style="width:100%; padding:15px; font-size:18px;">
                        Hoàn Tất Phiếu
                    </button>
                </form>
                <?php else: ?>
                <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:15px;text-align:center;color:#856404;">
                    <strong>Chưa thể hoàn tất</strong> — Vui lòng thêm hạng mục sửa chữa trước khi hoàn thành phiếu.
                </div>
                <?php endif; ?>

            <?php elseif ($tinhTrang === 'Hoàn thành'): ?>
                <div style="background:#d4edda;border:1px solid #28a745;border-radius:8px;padding:15px;text-align:center;">
                    ✅ <strong>Phiếu đã hoàn thành Chờ nhân viên trả thiết bị cho khách</strong>
                </div>
                <a href="<?= url('ktv/quaylai/' . $phieu['MaPhieu']) ?>"
                   class="btn btn-warning" style="width:100%; padding:12px; font-size:15px; text-align:center; display:block; margin-top:10px;"
                   onclick="return confirm('Quay lại tiếp nhận để sửa chữa tiếp phiếu này?')">
                    Quay Lại Tiếp Nhận
                </a>
                <p style="text-align:center;color:#888;font-size:13px;margin-top:8px;">Bạn vẫn có thể thêm/xóa hạng mục nếu có phát sinh.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal sửa hạng mục -->
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;padding:24px;width:90%;max-width:500px;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
        <h3 style="margin-top:0;margin-bottom:20px;">Sửa Hạng Mục</h3>
        <form method="POST" id="editForm" style="display:flex;flex-direction:column;gap:15px;">
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">Hạng mục</label>
                <input type="text" id="editHangMuc" name="HangMuc" class="form-control" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">SL</label>
                    <input type="number" id="editSoLuong" name="SoLuong" class="form-control" value="1" min="1" required>
                </div>
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">Đơn giá (VNĐ)</label>
                    <input type="text" id="editDonGia" name="DonGia_display" class="form-control money-input" value="0" required>
                    <input type="hidden" name="DonGia" value="0">
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:10px;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(maChiTiet, hangMuc, soLuong, donGia) {
    document.getElementById('editHangMuc').value = hangMuc;
    document.getElementById('editSoLuong').value = soLuong;
    document.getElementById('editDonGia').value = formatSoTien(donGia);
    document.getElementById('editForm').action = '<?= url('ktv/suachitiet') ?>/' + maChiTiet;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function formatSoTien(n) {
    if (n === 0 || n === '0') return '0';
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Handle money input formatting
document.addEventListener('DOMContentLoaded', function() {
    const moneyInputs = document.querySelectorAll('.money-input');
    moneyInputs.forEach(input => {
        input.addEventListener('input', function() {
            const val = this.value.replace(/[^\d]/g, '');
            const num = parseInt(val) || 0;
            this.value = formatSoTien(num);
            // Update hidden field
            const form = this.closest('form');
            if (form) {
                const hidden = form.querySelector('input[type="hidden"][name="DonGia"]');
                if (hidden) hidden.value = num;
            }
        });
        
        input.addEventListener('blur', function() {
            const val = this.value.replace(/[^\d]/g, '');
            const num = parseInt(val) || 0;
            this.value = formatSoTien(num);
            // Update hidden field
            const form = this.closest('form');
            if (form) {
                const hidden = form.querySelector('input[type="hidden"][name="DonGia"]');
                if (hidden) hidden.value = num;
            }
        });
    });
});
</script>
