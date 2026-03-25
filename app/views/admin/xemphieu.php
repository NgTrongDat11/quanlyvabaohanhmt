<div class="page-header no-print">
    <div>
        <h1>Biên Nhận #<?= $phieu['MaPhieu'] ?></h1>
        <p>Chi tiết phiếu sửa chữa</p>
    </div>
    <div class="d-flex gap-10">
        <button onclick="window.print()" class="btn btn-success">🖨 In Biên Nhận</button>
        <a href="<?= url('admin/phieusuachua') ?>" class="btn btn-secondary">← Quay lại</a>
    </div>
</div>

<div class="bien-nhan">
    <!-- Header Biên Nhận -->
    <div class="bien-nhan-header">
        <div class="bien-nhan-logo bien-nhan-logo--with-text">
            <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo">
            <div class="bien-nhan-logo-text">
                <h1>CAO HÙNG TECH</h1>
                <p>Trao giá trị - Nhận niềm tin</p>
            </div>
        </div>
        
        <div class="bien-nhan-title">
            <h2>BIÊN NHẬN</h2>
            <p class="so-phieu">Số: <strong><?= str_pad($phieu['MaPhieu'], 6, '0', STR_PAD_LEFT) ?></strong></p>
        </div>
        
        <div class="bien-nhan-company">
            <p style="font-size:10px;color:#666;">CÔNG TY TNHH CÔNG NGHỆ</p>
            <h3>CAO HÙNG</h3>
            <p>Địa chỉ: Số 189A, Nguyễn Đáng, Khóm 6, Phường Nguyệt Hóa, Vĩnh Long</p>
                <p>ĐT: 094.179.1313</p>
        </div>
    </div>

    <!-- Body Biên Nhận -->
    <div class="bien-nhan-body">
        <!-- Thông tin sản phẩm -->
        <div class="bien-nhan-section">
            <div class="bien-nhan-row">
                <label>Tên thiết bị:</label>
                <span><?= e($phieu['TenSanPham'] ?? '') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Serial:</label>
                <span><?= e($phieu['MaSerial'] ?? '') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Phụ kiện:</label>
                <span><?= e($phieu['PhuKienKemTheo'] ?? 'Không có') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Tình trạng SP:</label>
                <span>
                    <?= !empty($phieu['GhiChuTinhTrang']) ? e($phieu['GhiChuTinhTrang']) : '<em style="color:#999;">Chưa ghi nhận</em>' ?>
                </span>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="bien-nhan-section">
            <div class="bien-nhan-row">
                <label>Tên KH:</label>
                <span><?= e($phieu['TenKhachHang'] ?? '') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Địa chỉ:</label>
                <span><?= e($phieu['DiaChi_KH'] ?? '') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Điện thoại:</label>
                <span><?= e($phieu['SDT_KhachHang'] ?? '') ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Loại dịch vụ:</label>
                <span class="loai-dich-vu-print">
                    <?php if ($phieu['LoaiDichVu'] == 'Tận nơi'): ?>
                        ☑ Tận nơi &nbsp;&nbsp; ☐ Tại Cao Hùng
                    <?php else: ?>
                        ☐ Tận nơi &nbsp;&nbsp; ☑ Tại Cao Hùng
                    <?php endif; ?>
                </span>
            </div>
            <div class="bien-nhan-row">
                <label>Ngày nhận:</label>
                <span><?= date('d/m/Y', strtotime($phieu['NgayNhan'])) ?></span>
            </div>
            <div class="bien-nhan-row">
                <label>Ngày trả (dự kiến):</label>
                <span><?= $phieu['NgayTra'] ? date('d/m/Y', strtotime($phieu['NgayTra'])) : '.../.../......' ?></span>
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết sửa chữa -->
    <table class="bien-nhan-table">
        <thead>
            <tr>
                <th class="col-stt">STT</th>
                <th class="col-hangmuc">Hạng mục thay thế / sửa chữa</th>
                <th class="col-soluong">Số lượng</th>
                <th class="col-dongia">Đơn giá</th>
                <th class="col-thanhtien">Thành tiền</th>
                <th class="no-print" style="width:40px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($chiTiet)): ?>
                <tr>
                    <td>1</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="no-print"></td>
                </tr>
            <?php else: ?>
                <?php $stt = 1; foreach ($chiTiet as $ct): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td class="text-left"><?= e($ct['HangMuc']) ?></td>
                    <td><?= $ct['SoLuong'] ?></td>
                    <td class="text-right"><?= number_format($ct['DonGia'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($ct['ThanhTien'], 0, ',', '.') ?></td>
                    <td class="no-print" style="text-align:center;vertical-align:middle;padding:8px;">
                        <?php if (($phieu['TinhTrang'] ?? '') !== 'Đã trả'): ?>
                        <div style="display:flex;flex-direction:column;gap:4px;align-items:center;">
                            <button type="button" title="Sửa hạng mục" onclick="openEditModal(<?= $ct['MaChiTiet'] ?>, '<?= e($ct['HangMuc']) ?>', <?= $ct['SoLuong'] ?>, <?= $ct['DonGia'] ?>)" style="color:#0d6efd;background:none;border:none;cursor:pointer;font-size:16px;padding:2px 6px;">+</button>
                            <form method="POST" action="<?= url('admin/xoachitiet/' . $ct['MaChiTiet']) ?>" style="display:inline" onsubmit="return confirm('Xóa hạng mục này?')">
                                <button type="submit" style="color:#dc3545;background:none;border:none;cursor:pointer;font-size:18px;padding:2px 6px;">×</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Form thêm hạng mục (không in) -->
    <?php if (($phieu['TinhTrang'] ?? '') !== 'Đã trả'): ?>
    <div class="no-print" style="margin:10px 0;">
        <form method="POST" action="<?= url('admin/luuchitiet') ?>" style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
            <input type="hidden" name="MaPhieu" value="<?= $phieu['MaPhieu'] ?>">
            <div style="flex:2;min-width:200px;">
                <label style="font-size:12px;color:#666;display:block;margin-bottom:3px;">Hạng mục</label>
                <input type="text" name="HangMuc" class="form-control" placeholder="VD: Thay màn hình, thay pin..." required style="font-size:13px;padding:6px 10px;">
            </div>
            <div style="flex:0 0 80px;">
                <label style="font-size:12px;color:#666;display:block;margin-bottom:3px;">SL</label>
                <input type="number" name="SoLuong" class="form-control" value="1" min="1" style="font-size:13px;padding:6px 10px;text-align:center;">
            </div>
            <div style="flex:0 0 130px;">
                <label style="font-size:12px;color:#666;display:block;margin-bottom:3px;">Đơn giá (VNĐ)</label>
                <input type="text" name="DonGia_display" class="form-control money-input" value="0" style="font-size:13px;padding:6px 10px;">
                <input type="hidden" name="DonGia" value="0">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="padding:7px 15px;">+ Thêm</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Tổng tiền -->
    <div class="bien-nhan-total">
        <table>
            <tr>
                <td>Tiền công thay thế, sửa chữa</td>
                <td class="text-right"><?= number_format($phieu['TongTien'] ?? 0, 0, ',', '.') ?> đ</td>
            </tr>
            <tr class="total-row">
                <td>Tổng cộng (bằng chữ)</td>
                <td class="text-right" style="font-style:italic;white-space:nowrap;"><?= docSoTienBangChu($phieu['TongTien'] ?? 0) ?></td>
            </tr>
        </table>
    </div>

    <!-- Chữ ký -->
    <div class="bien-nhan-footer">
        <div>
            <strong>Người nhận TB</strong>
            <p><?= e($tenNVNhan ?? '') ?></p>
        </div>
        <div>
            <strong>KTV xử lý</strong>
            <p><?= e($tenKTV ?? '') ?></p>
        </div>
        <div>
            <strong>Người trả TB</strong>
            <p><?= e($tenNVTra ?? '') ?></p>
        </div>
        <div>
            <strong>Khách hàng ký nhận</strong>
            <p><?= e($phieu['TenKhachHang'] ?? '') ?></p>
        </div>
    </div>

    <!-- Ghi chú -->
    <div class="bien-nhan-note">
        <p>* Mọi thông tin phản hồi về cách phục vụ, chuyên môn kỹ thuật vui lòng liên hệ Hotline: 0979.38.1357</p>
        <p>* Sau 3 tháng kể từ ngày báo kết quả xử lý. Nếu khách hàng không đến nhận, cty sẽ không chịu trách nhiệm với thiết bị.</p>
    </div>
</div>

<!-- Trạng thái hiện tại (không in) -->
<div class="card mt-20 no-print">
    <div class="card-header">Trạng Thái Phiếu</div>
    <div class="card-body">
        <div class="d-flex align-center gap-20">
            <div>
                <strong>Trạng thái hiện tại:</strong>
                <?php
                $trangThai = $phieu['TinhTrang'] ?? 'Chờ xử lý';
                $badgeClass = 'badge-waiting';
                switch ($trangThai) {
                    case 'Đã phân công': $badgeClass = 'badge-assigned'; break;
                    case 'Đang kiểm tra': $badgeClass = 'badge-checking'; break;
                    case 'Tiếp nhận': $badgeClass = 'badge-processing'; break;
                    case 'Hoàn thành': $badgeClass = 'badge-done'; break;
                    case 'Đã trả': $badgeClass = 'badge-returned'; break;
                }
                ?>
                <span class="badge <?= $badgeClass ?>"><?= e($trangThai) ?></span>
            </div>
            
            <?php
            // Chỉ cho chọn trạng thái tiếp theo (không quay lại)
            $luong = ['Chờ xử lý', 'Đã phân công', 'Đang kiểm tra', 'Tiếp nhận', 'Hoàn thành', 'Đã trả'];
            $viTriHienTai = array_search($trangThai, $luong);
            $cacTrangThaiTiepTheo = ($viTriHienTai !== false) ? array_slice($luong, $viTriHienTai + 1) : [];
            ?>
            <?php if (!empty($cacTrangThaiTiepTheo)): ?>
            <form method="POST" action="<?= url('admin/capnhattrangthai/' . $phieu['MaPhieu']) ?>" class="d-flex align-center gap-10">
                <select name="TrangThai" class="form-control" style="width:auto;">
                    <?php foreach ($cacTrangThaiTiepTheo as $tt): ?>
                    <option value="<?= $tt ?>"><?= e($tt) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Thông tin Bảo Hành / Đối Tác (chỉ xem) -->
<?php if (!empty($dsBaoHanh) || !empty($dsDoiTac)): ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
    <?php if (!empty($dsBaoHanh)): ?>
    <div class="card">
        <div class="card-header"><h3>🔄 Gửi Bảo Hành (<?= count($dsBaoHanh) ?>)</h3></div>
        <div class="card-body" style="padding:0;">
            <?php foreach ($dsBaoHanh as $bh): ?>
            <div style="padding:12px 15px;border-bottom:1px solid #f0f0f0;">
                <strong><?= e($bh['TenTrungTamBH']) ?></strong><br>
                <small style="color:#888;">
                    Gửi: <?= date('d/m/Y', strtotime($bh['NgayGui'])) ?>
                    <?php if (!empty($bh['NgayNhanLai'])): ?>
                        → Nhận: <?= date('d/m/Y', strtotime($bh['NgayNhanLai'])) ?>
                    <?php endif; ?>
                </small>
                <?php if (!empty($bh['KetQuaBaoHanh'])): ?>
                    <br><span style="color:#155724;background:#d4edda;padding:2px 8px;border-radius:10px;font-size:12px;">✅ <?= e($bh['KetQuaBaoHanh']) ?></span>
                <?php else: ?>
                    <br><span style="color:#856404;background:#fff3cd;padding:2px 8px;border-radius:10px;font-size:12px;">⏳ Chờ kết quả</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dsDoiTac)): ?>
    <div class="card">
        <div class="card-header"><h3>🤝 Gửi Đối Tác (<?= count($dsDoiTac) ?>)</h3></div>
        <div class="card-body" style="padding:0;">
            <?php foreach ($dsDoiTac as $dt): ?>
            <div style="padding:12px 15px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:flex-start;">
                <div style="flex:1;">
                    <strong><?= e($dt['TenDoiTac']) ?></strong>
                    <?php
                    $ttColors = ['Đang xử lý' => '#fff3cd', 'Hoàn thành' => '#d4edda', 'Trả lại' => '#f8d7da'];
                    $bg = $ttColors[$dt['TrangThai']] ?? '#e9ecef';
                    ?>
                    <span style="background:<?= $bg ?>;padding:2px 8px;border-radius:10px;font-size:11px;margin-left:6px;"><?= e($dt['TrangThai']) ?></span><br>
                    <small style="color:#888;">
                        Gửi: <?= date('d/m/Y', strtotime($dt['NgayGui'])) ?>
                        <?php if (!empty($dt['NgayNhanLai'])): ?>
                            → Nhận: <?= date('d/m/Y', strtotime($dt['NgayNhanLai'])) ?>
                        <?php endif; ?>
                    </small><br>
                </div>
                <div style="display:flex;gap:5px;margin-left:10px;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

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
    document.getElementById('editForm').action = '<?= url('admin/suachitiet') ?>/' + maChiTiet;
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
