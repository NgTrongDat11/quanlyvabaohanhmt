<div class="page-header">
    <div>
        <h1>Trả Phiếu Cho Khách</h1>
        <p>Danh sách phiếu đã hoàn thành, sẵn sàng trả</p>
    </div>
    <a href="<?= url('nhanvien') ?>" class="btn btn-secondary">← Quay lại</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($phieu)): ?>
            <div class="empty-state">
                <p style="font-size:48px;">📭</p>
                <p>Không có phiếu nào chờ trả</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Phiếu</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Sản Phẩm</th>
                            <th>Ngày Nhận</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phieu as $p): ?>
                        <tr>
                            <td><strong>#<?= $p['MaPhieu'] ?></strong></td>
                            <td><?= e($p['TenKhachHang'] ?? '') ?></td>
                            <td>
                                <a href="tel:<?= e($p['SDT_KhachHang'] ?? '') ?>" class="text-primary">
                                    <?= e($p['SDT_KhachHang'] ?? '') ?>
                                </a>
                            </td>
                            <td><?= e($p['TenSanPham'] ?? '') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['NgayNhan'])) ?></td>
                            <td class="text-success"><strong><?= number_format($p['TongTien'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                            <td style="white-space:nowrap;">
                                <a href="<?= url('nhanvien/xemphieu/' . $p['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="xacNhanTra(<?= $p['MaPhieu'] ?>, <?= intval($p['TongTien'] ?? 0) ?>)">Trả</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal xác nhận trả -->
<div id="modalTra" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Xác Nhận Trả Máy</h3>
            <span class="close" onclick="dongModal()">&times;</span>
        </div>
        <form method="POST" action="<?= url('nhanvien/xacnhantra') ?>">
            <input type="hidden" name="MaPhieu" id="maPhieuTra">
            <div class="modal-body">
                <div class="form-group">
                    <label>Số tiền thu:</label>
                    <input type="text" name="SoTienThu_display" id="soTienThu" class="form-control money-input" required value="0">
                    <input type="hidden" name="SoTienThu" value="0">
                </div>
                <div class="form-group">
                    <label>Hình thức thanh toán:</label>
                    <select name="HinhThucTT" id="hinhThucTT" class="form-control">
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Chuyển khoản">Chuyển khoản</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="dongModal()">Hủy</button>
                <button type="submit" class="btn btn-success">Xác nhận trả</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.3);
}
.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 {
    margin: 0;
}
.close {
    font-size: 24px;
    cursor: pointer;
    color: #999;
}
.close:hover {
    color: #333;
}
.modal-body {
    padding: 20px;
}
.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}
</style>

<script>
var currentTongTien = 0;
var currentMaPhieu  = 0;

function xacNhanTra(maPhieu, tongTien) {
    currentMaPhieu  = maPhieu;
    currentTongTien = tongTien || 0;
    document.getElementById('maPhieuTra').value = maPhieu;

    // Đặt số tiền thu mặc định = tổng tiền
    var displayInput = document.getElementById('soTienThu');
    if (displayInput) {
        displayInput.value = new Intl.NumberFormat('vi-VN').format(currentTongTien);
        // Cập nhật hidden input
        var hiddenInput = displayInput.closest('form').querySelector('input[name="SoTienThu"]');
        if (hiddenInput) hiddenInput.value = currentTongTien;
    }

    // Reset mặc định về tiền mặt
    document.getElementById('hinhThucTT').value = 'Tiền mặt';

    document.getElementById('modalTra').style.display = 'flex';
}

function dongModal() {
    document.getElementById('modalTra').style.display = 'none';
}

// Click outside to close
document.getElementById('modalTra').addEventListener('click', function(e) {
    if (e.target === this) {
        dongModal();
    }
});
</script>
