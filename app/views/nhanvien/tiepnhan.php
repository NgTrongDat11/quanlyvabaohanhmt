<div class="page-header">
    <div>
        <h1>Tiếp Nhận Sửa Chữa</h1>
        <p>Tạo phiếu sửa chữa mới</p>
    </div>
    <a href="<?= url('nhanvien') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<form method="POST" action="<?= url('nhanvien/luuphieu') ?>" enctype="multipart/form-data">
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
        <div class="bien-nhan-body" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
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
                    <input type="text" name="PhuKienKemTheo" class="form-control" placeholder="VD: Sạc, túi đựng...">
                </div>
                
                <div class="bien-nhan-row">
                    <label>Tình trạng SP:</label>
                    <textarea name="GhiChuTinhTrang" rows="3" class="form-control" placeholder="VD: Bị lỗi nguồn, mở không lên, hư ram..."></textarea>
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
                    <select name="LoaiDichVu" class="form-control">
                        <option value="Tại Cao Hùng">Tại Cao Hùng</option>
                        <option value="Tận nơi">Tận nơi</option>
                    </select>
                </div>

                <div class="bien-nhan-row">
                    <label>Ngày trả dự kiến:</label>
                    <input type="date" name="NgayTraDuKien" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                </div>
            </div>
        </div>

        <!-- Form actions -->
        <div style="display:flex;gap:10px;margin-top:20px;border-top:1px solid #ddd;padding-top:15px;">
            <button type="submit" class="btn btn-primary btn-lg">Tạo Phiếu</button>
            <a href="<?= url('nhanvien') ?>" class="btn btn-secondary btn-lg">Hủy</a>
        </div>
    </div>

    <input type="hidden" name="MaKhachHang" id="maKhachHang" value="0">
    <input type="hidden" name="TaiKhoanKH" id="taiKhoanKH" value="">
</form>

<script>
function fillKhachTK() {
    const select = document.getElementById('selectKhachTK');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('tenKH').value = option.getAttribute('data-ten');
        document.getElementById('taiKhoanKH').value = option.getAttribute('data-username');
        document.getElementById('maKhachHang').value = '0';
        document.getElementById('sdtKH').value = '';
        document.getElementById('diachiKH').value = '';
    } else {
        document.getElementById('tenKH').value = '';
        document.getElementById('taiKhoanKH').value = '';
        document.getElementById('maKhachHang').value = '0';
        document.getElementById('sdtKH').value = '';
        document.getElementById('diachiKH').value = '';
    }
}
</script>
