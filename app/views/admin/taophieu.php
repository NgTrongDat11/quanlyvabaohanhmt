<div class="page-header">
    <div>
        <h1>Tạo Biên Nhận Mới</h1>
        <p>Nhập thông tin tiếp nhận thiết bị</p>
    </div>
    <a href="<?= url('admin/phieusuachua') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<form method="POST" action="<?= url('admin/luuphieu') ?>" enctype="multipart/form-data">
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
                <p class="so-phieu">Số: <strong>Tự động</strong></p>
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
                <h4>Thông Tin Sản Phẩm</h4>
                
                <div class="bien-nhan-row">
                    <label>Tên thiết bị:</label>
                    <input type="text" name="TenSanPham" class="form-control" placeholder="VD: Laptop Dell Inspiron 15, iPhone 14..." required>
                </div>

                <div class="bien-nhan-row">
                    <label>Serial/IMEI:</label>
                    <input type="text" name="MaSerial" class="form-control" placeholder="Số serial hoặc IMEI...">
                </div>

                <div class="bien-nhan-row">
                    <label>Phụ kiện kèm theo:</label>
                    <input type="text" name="PhuKienKemTheo" placeholder="VD: Sạc, túi đựng...">
                </div>
                
                <div class="bien-nhan-row">
                    <label>Tình trạng SP:</label>
                    <textarea name="GhiChuTinhTrang" rows="3" style="flex:1;border:1px solid #ddd;padding:5px;font-size:13px;" placeholder="VD: Bị lỗi nguồn, mở không lên, hư ram..."></textarea>
                </div>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="bien-nhan-section">
                <h4>Thông Tin Khách Hàng</h4>
                
                <div class="bien-nhan-row">
                    <label>Tên KH:</label>
                    <input type="text" name="TenKhachHang" class="form-control" placeholder="Nhập tên khách hàng..." required>
                </div>

                <div class="bien-nhan-row">
                    <label>Số điện thoại:</label>
                    <input type="text" name="SoDienThoai" class="form-control" placeholder="Nhập SĐT khách hàng..." required>
                </div>

                <div class="bien-nhan-row">
                    <label>Địa chỉ:</label>
                    <input type="text" name="DiaChi" class="form-control" placeholder="Nhập địa chỉ khách hàng...">
                </div>
                
                <div class="bien-nhan-row">
                    <label>Loại dịch vụ:</label>
                    <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="radio" name="LoaiDichVu" value="Tận nơi" required> Tận nơi
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="LoaiDichVu" value="Tại Cao Hùng" checked> Tại Cao Hùng
                        </label>
                    </div>
                </div>
                
                <div class="bien-nhan-row">
                    <label>Ngày nhận:</label>
                    <input type="datetime-local" name="NgayNhan" value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>
                
                <div class="bien-nhan-row">
                    <label>Ngày trả (dự kiến):</label>
                    <input type="datetime-local" name="NgayTra" value="<?= date('Y-m-d\TH:i', strtotime('+3 days')) ?>">
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
                    <th style="width:40px;border:1.5px solid #e6a817;"></th>
                </tr>
            </thead>
            <tbody id="chiTietBody">
                <tr>
                    <td class="stt-cell">1</td>
                    <td><input type="text" name="chiTiet[0][HangMuc]" class="input-hangmuc" placeholder="Nhập hạng mục..."></td>
                    <td><input type="number" name="chiTiet[0][SoLuong]" value="1" min="1" class="input-soluong" oninput="tinhThanhTien(this)" onchange="tinhThanhTien(this)"></td>
                    <td><input type="text" name="chiTiet[0][DonGia_display]" value="0" class="input-dongia" oninput="formatTien(this); tinhThanhTien(this);" onblur="formatTien(this); tinhThanhTien(this);"><input type="hidden" name="chiTiet[0][DonGia]" value="0"></td>
                    <td class="text-right thanh-tien-cell">0</td>
                    <td style="text-align:center;"><button type="button" onclick="xoaHang(this)" class="btn-xoa-hang" title="Xóa hàng">×</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Tổng tiền -->
        <div style="border:1.5px solid #e6a817;border-top:none;margin-top:-20px;margin-bottom:10px;">
            <div style="display:flex;border-bottom:1.5px solid #e6a817;">
                <div style="flex:1;padding:10px 15px;font-weight:600;font-size:13px;">Tiền công thay thế, sửa chữa</div>
                <div style="width:150px;padding:10px 15px;text-align:right;font-weight:600;font-size:13px;border-left:1.5px solid #e6a817;" id="tongTienCong">0 đ</div>
            </div>
            <div style="display:flex;">
                <div style="flex:1;padding:10px 15px;font-weight:700;font-size:13px;">Tổng cộng (bằng chữ)</div>
                <div style="width:300px;padding:10px 15px;text-align:right;font-weight:600;font-size:13px;font-style:italic;border-left:1.5px solid #e6a817;" id="tongCongChu"></div>
            </div>
        </div>

        <div style="margin:8px 0 15px; display:flex; gap:10px;">
            <button type="button" onclick="themHangMuc()" style="font-size:13px;padding:6px 24px;background:#e6a817;color:#fff;border:none;border-radius:4px;cursor:pointer;font-weight:500;">+ Thêm hạng mục</button>
        </div>

        <!-- Phân công -->
        <div class="card mb-20">
            <div class="card-header">Phân Công Công Việc</div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Người nhận TB:</label>
                        <select name="NhanVienTiepNhan" class="form-control">
                            <option value="">-- Chưa phân công --</option>
                            <?php foreach ($dsNhanVien as $username => $acc): ?>
                                <option value="<?= e($username) ?>">
                                    <?= e($acc['HoTen'] ?? $acc['TenNhanVien'] ?? $username) ?> (<?= e($username) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($dsNhanVien)): ?>
                            <small style="color:#e67e00;">Chưa có tài khoản NV nào. Hãy <a href="<?= url('admin/taikhoan') ?>">tạo tài khoản</a> với loại "Nhân viên tiếp nhận".</small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>KTV xử lý:</label>
                        <select name="KTVXuLy" class="form-control">
                            <option value="">-- Chưa phân công --</option>
                            <?php foreach ($dsKTV as $username => $acc): ?>
                                <option value="<?= e($username) ?>">
                                    <?= e($acc['TenNhanVien'] ?? $acc['HoTen'] ?? $username) ?> (<?= e($username) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($dsKTV)): ?>
                            <small style="color:#e67e00;">Chưa có tài khoản KTV nào. Hãy <a href="<?= url('admin/taikhoan') ?>">tạo tài khoản</a> với loại "Kỹ thuật viên".</small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Người trả TB:</label>
                        <select name="NhanVienTra" class="form-control">
                            <option value="">-- Chưa phân công --</option>
                            <?php foreach ($dsNhanVien as $username => $acc): ?>
                                <option value="<?= e($username) ?>">
                                    <?= e($acc['TenNhanVien'] ?? $acc['HoTen'] ?? $username) ?> (<?= e($username) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Khách hàng ký nhận:</label>
                        <select name="TaiKhoanKH" id="selectKhachHang" class="form-control" onchange="fillKhachHang()">
                            <option value="" data-ten="" data-sdt="" data-diachi="">-- Chọn tài khoản KH --</option>
                            <?php foreach ($dsKhachHang as $username => $acc): ?>
                                <option value="<?= e($username) ?>"
                                    data-ten="<?= e($acc['TenNhanVien'] ?? $acc['HoTen'] ?? $username) ?>"
                                    data-sdt="<?= e($acc['SoDienThoai'] ?? '') ?>"
                                    data-diachi="<?= e($acc['DiaChi'] ?? '') ?>">
                                    <?= e($acc['TenNhanVien'] ?? $acc['HoTen'] ?? $username) ?> (<?= e($username) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($dsKhachHang)): ?>
                            <small style="color:#e67e00;">Chưa có tài khoản KH nào. Hãy <a href="<?= url('admin/taikhoan') ?>">tạo tài khoản</a> với loại "Khách hàng".</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-end gap-10">
            <a href="<?= url('admin/phieusuachua') ?>" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-success">Lưu Biên Nhận</button>
        </div>
    </div>
</form>

<script>
function fillKhachHang() {
    var sel = document.getElementById('selectKhachHang');
    var opt = sel.options[sel.selectedIndex];
    var ten = opt.getAttribute('data-ten') || '';
    var sdt = opt.getAttribute('data-sdt') || '';
    var diachi = opt.getAttribute('data-diachi') || '';

    if (ten) {
        var tenInput = document.querySelector('input[name="TenKhachHang"]');
        if (tenInput) tenInput.value = ten;
    }
    if (sdt) {
        var sdtInput = document.querySelector('input[name="SoDienThoai"]');
        if (sdtInput) sdtInput.value = sdt;
    }
    if (diachi) {
        var dcInput = document.querySelector('input[name="DiaChi"]');
        if (dcInput) dcInput.value = diachi;
    }
}

function tinhThanhTien(el) {
    var row = el.closest('tr');
    var sl = parseInt(row.querySelector('input[name*="SoLuong"]').value) || 0;
    var dgDisplay = row.querySelector('input[name*="DonGia_display"]');
    var dgHidden = row.querySelector('input[type="hidden"][name*="DonGia"]');
    var raw = dgDisplay.value.replace(/\./g, '');
    var dg = parseInt(raw) || 0;
    dgHidden.value = dg;
    var tt = sl * dg;
    row.querySelector('.thanh-tien-cell').textContent = formatSoTien(tt);
    tinhTongTien();
}

function formatTien(el) {
    var cursorPos = el.selectionStart;
    var oldLen = el.value.length;
    var val = el.value.replace(/[^\d]/g, '');
    var num = parseInt(val) || 0;
    var formatted = formatSoTien(num);
    el.value = formatted;
    // Cập nhật hidden field
    var row = el.closest('tr');
    var hidden = row.querySelector('input[type="hidden"][name*="DonGia"]');
    if (hidden) hidden.value = num;
    // Giữ vị trí con trỏ hợp lý
    var newLen = formatted.length;
    var newPos = cursorPos + (newLen - oldLen);
    if (newPos < 0) newPos = 0;
    el.setSelectionRange(newPos, newPos);
}

function formatSoTien(n) {
    if (n === 0) return '0';
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

var rowCount = 1;
function themHangMuc() {
    var tbody = document.getElementById('chiTietBody');
    var tr = document.createElement('tr');
    var idx = rowCount;
    rowCount++;
    var stt = tbody.querySelectorAll('tr').length + 1;
    tr.innerHTML = '<td class="stt-cell">' + stt + '</td>' +
        '<td><input type="text" name="chiTiet[' + idx + '][HangMuc]" class="input-hangmuc" placeholder="Nhập hạng mục..."></td>' +
        '<td><input type="number" name="chiTiet[' + idx + '][SoLuong]" value="1" min="1" class="input-soluong" oninput="tinhThanhTien(this)" onchange="tinhThanhTien(this)"></td>' +
        '<td><input type="text" name="chiTiet[' + idx + '][DonGia_display]" value="0" class="input-dongia" oninput="formatTien(this); tinhThanhTien(this);" onblur="formatTien(this); tinhThanhTien(this);"><input type="hidden" name="chiTiet[' + idx + '][DonGia]" value="0"></td>' +
        '<td class="text-right thanh-tien-cell">0</td>' +
        '<td style="text-align:center;"><button type="button" onclick="xoaHang(this)" class="btn-xoa-hang" title="Xóa hàng">×</button></td>';
    tbody.appendChild(tr);
}

function xoaHang(btn) {
    var row = btn.closest('tr');
    var tbody = document.getElementById('chiTietBody');
    // Không cho xóa nếu chỉ còn 1 dòng
    if (tbody.querySelectorAll('tr').length <= 1) {
        alert('Phải có ít nhất 1 hạng mục!');
        return;
    }
    row.remove();
    capNhatSTT();
    tinhTongTien();
}

function capNhatSTT() {
    var rows = document.querySelectorAll('#chiTietBody tr');
    rows.forEach(function(row, i) {
        row.querySelector('.stt-cell').textContent = i + 1;
    });
}

function tinhTongTien() {
    var cells = document.querySelectorAll('.thanh-tien-cell');
    var tong = 0;
    cells.forEach(function(cell) {
        var val = cell.textContent.replace(/\./g, '');
        tong += parseInt(val) || 0;
    });
    document.getElementById('tongTienCong').textContent = formatSoTien(tong) + ' đ';
    document.getElementById('tongCongChu').textContent = docSoTienBangChu(tong);
}

function docSoTienBangChu(so) {
    if (so === 0) return 'Không đồng';
    var chuSo = ['không','một','hai','ba','bốn','năm','sáu','bảy','tám','chín'];
    var donVi = ['','nghìn','triệu','tỷ'];

    function docBaChuSo(n) {
        var tram = Math.floor(n / 100);
        var chuc = Math.floor((n % 100) / 10);
        var dv = n % 10;
        var s = '';
        if (tram > 0) {
            s += chuSo[tram] + ' trăm ';
            if (chuc === 0 && dv > 0) s += 'lẻ ';
        }
        if (chuc > 1) {
            s += chuSo[chuc] + ' mươi ';
            if (dv === 1) s += 'mốt ';
            else if (dv === 5) s += 'lăm ';
            else if (dv > 0) s += chuSo[dv] + ' ';
        } else if (chuc === 1) {
            s += 'mười ';
            if (dv === 5) s += 'lăm ';
            else if (dv > 0) s += chuSo[dv] + ' ';
        } else if (dv > 0 && tram === 0) {
            s += chuSo[dv] + ' ';
        } else if (dv > 0) {
            s += chuSo[dv] + ' ';
        }
        return s;
    }

    var parts = [];
    var temp = so;
    while (temp > 0) { parts.push(temp % 1000); temp = Math.floor(temp / 1000); }
    var result = '';
    for (var i = parts.length - 1; i >= 0; i--) {
        if (parts[i] > 0) result += docBaChuSo(parts[i]) + donVi[i] + ' ';
    }
    result = result.trim() + ' đồng';
    return result.charAt(0).toUpperCase() + result.slice(1);
}
</script>
