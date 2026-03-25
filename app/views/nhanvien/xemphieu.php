<div class="page-header no-print">
    <div>
        <h1>Biên Nhận #<?= $phieu['MaPhieu'] ?></h1>
        <p>Chi tiết phiếu sửa chữa</p>
    </div>
    <div class="d-flex gap-10">
        <button onclick="window.print()" class="btn btn-success">🖨 In Biên Nhận</button>
        <a href="<?= url('nhanvien/danhsach') ?>" class="btn btn-secondary">← Quay lại</a>
    </div>
</div>

<div class="bien-nhan" id="printArea">
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
                    <?php
                    $moTaTinhTrang = trim($phieu['GhiChuTinhTrang'] ?? '');
                    ?>
                    <?= $moTaTinhTrang ? e($moTaTinhTrang) : '<em style="color:#999;">Chưa ghi nhận</em>' ?>
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
                    <?php if (($phieu['LoaiDichVu'] ?? '') == 'Tận nơi'): ?>
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
                </tr>
            <?php else: ?>
                <?php $stt = 1; foreach ($chiTiet as $ct): ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <td class="text-left"><?= e($ct['HangMuc']) ?></td>
                    <td><?= $ct['SoLuong'] ?></td>
                    <td class="text-right"><?= number_format($ct['DonGia'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($ct['ThanhTien'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

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

<?php if (isset($_GET['print'])): ?>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>
