<div class="page-header">
    <div>
        <h1>Gửi Yêu Cầu Sửa Chữa</h1>
        <p>Điền thông tin thiết bị cần sửa chữa, nhân viên sẽ liên hệ bạn sớm nhất</p>
    </div>
    <a href="<?= url('khach') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<?php if ($flash = flash('error')): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #f5c6cb;">❌ <?= e($flash) ?></div>
<?php endif; ?>

<form method="POST" action="<?= url('khach/luuphieu') ?>" enctype="multipart/form-data">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        <!-- Thông tin khách hàng -->
        <div class="card">
            <div class="card-header">
                <h3>👤 Thông Tin Khách Hàng</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Họ tên *</label>
                    <input type="text" name="TenKhachHang" class="form-control" required
                           value="<?= e($_SESSION['user']['HoTen'] ?? $_SESSION['user']['TenNhanVien'] ?? '') ?>"
                           placeholder="Nguyễn Văn A">
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" name="SoDienThoai" class="form-control" required
                           placeholder="0912 345 678">
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control"
                           placeholder="Số nhà, đường, phường/xã...">
                </div>
                <div class="form-group">
                    <label>Loại dịch vụ *</label>
                    <div style="display:flex;gap:20px;padding:8px 0;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                            <input type="radio" name="LoaiDichVu" value="Tại Cao Hùng" checked
                                   style="accent-color:#ffc107;width:16px;height:16px;">
                            <span>🏪 Mang đến cửa hàng</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                            <input type="radio" name="LoaiDichVu" value="Tận nơi"
                                   style="accent-color:#ffc107;width:16px;height:16px;">
                            <span>🏠 Sửa tại nhà</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thiết bị -->
        <div class="card">
            <div class="card-header">
                <h3>💻 Thông Tin Thiết Bị</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tên thiết bị *</label>
                    <input type="text" name="TenSanPham" class="form-control" required
                           placeholder="VD: Laptop Dell Inspiron 15, iPhone 14...">
                </div>
                <div class="form-group">
                    <label>Mã Serial (nếu có)</label>
                    <input type="text" name="MaSerial" class="form-control"
                           placeholder="Số serial trên máy...">
                </div>
                <div class="form-group">
                    <label>Phụ kiện kèm theo</label>
                    <input type="text" name="PhuKienKemTheo" class="form-control"
                           placeholder="VD: Sạc, túi đựng, chuột...">
                </div>
                <div class="form-group">
                    <label>Tình trạng thiết bị</label>
                    <textarea name="GhiChuTinhTrang" class="form-control" rows="3"
                              placeholder="VD: Máy không lên nguồn, màn hình bị sọc, pin phồng, máy chạy chậm...&#10;Hãy mô tả càng chi tiết càng tốt để chúng tôi hỗ trợ nhanh hơn."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút gửi -->
    <div style="margin-top:20px;display:flex;gap:12px;justify-content:center;">
        <button type="submit" class="btn btn-primary" style="padding:12px 40px;font-size:16px;">
            Gửi Yêu Cầu Sửa Chữa
        </button>
        <a href="<?= url('khach') ?>" class="btn btn-secondary" style="padding:12px 30px;font-size:16px;">
            Hủy
        </a>
    </div>
</form>
