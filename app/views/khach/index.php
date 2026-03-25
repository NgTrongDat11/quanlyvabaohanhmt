<div class="page-header">
    <div>
        <h1>👋 Xin chào, <?= e($_SESSION['user']['HoTen'] ?? 'Quý khách') ?></h1>
        <p>Hệ thống theo dõi đơn hàng sửa chữa - Cao Hùng Tech</p>
    </div>
</div>

<!-- Banner công ty -->
<div class="khach-banner">
    <div class="khach-banner-inner">
        <div class="khach-banner-logo">
            <div class="khach-logo-icon">
                <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo">
            </div>
            <div>
                <h1>CAO HÙNG TECH</h1>
                <p class="khach-slogan">Trao giá trị - Nhận niềm tin</p>
            </div>
        </div>
        <div class="khach-banner-services">
            <span>💻 Laptop</span>
            <span>📷 Camera</span>
            <span>🖥️ Linh kiện PC</span>
            <span>🖵 Màn hình</span>
        </div>
    </div>
</div>

<!-- Chức năng chính -->
<div class="khach-actions">
    <a href="<?= url('khach/taophieu') ?>" class="khach-action-card khach-action-new">
        <div class="khach-action-icon">📝</div>
        <h3>Gửi Yêu Cầu Sửa Chữa</h3>
        <p>Tạo phiếu sửa chữa mới</p>
    </a>
    <a href="<?= url('khach/donhang') ?>" class="khach-action-card khach-action-orders">
        <div class="khach-action-icon">📋</div>
        <h3>Đơn Hàng Của Tôi</h3>
        <p>Xem lịch sử sửa chữa</p>
    </a>
    <a href="<?= url('khach/tracuu') ?>" class="khach-action-card khach-action-search">
        <div class="khach-action-icon">🔍</div>
        <h3>Tra Cứu Đơn</h3>
        <p>Tìm kiếm theo mã phiếu</p>
    </a>
</div>

<!-- Đơn hàng gần đây -->
<?php if (!empty($donHang)): ?>
<div class="card" style="margin-top:25px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h3>📦 Đơn Hàng Gần Đây</h3>
        <a href="<?= url('khach/donhang') ?>" style="font-size:13px;color:#1f4978;text-decoration:none;font-weight:600;">Xem tất cả →</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Phiếu</th>
                    <th>Thiết Bị</th>
                    <th>Ngày Nhận</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($donHang, 0, 5) as $d): ?>
                <tr>
                    <td><strong>#<?= $d['MaPhieu'] ?></strong></td>
                    <td><?= e($d['TenSanPham'] ?? '') ?></td>
                    <td><?= date('d/m/Y', strtotime($d['NgayNhan'])) ?></td>
                    <td>
                        <?php
                        $trangThaiGoc = $d['TinhTrang'] ?? 'Chờ xử lý';
                        // Khách chỉ thấy 4 trạng thái
                        if (in_array($trangThaiGoc, ['Chờ xử lý', 'Đã phân công', 'Đang kiểm tra'])) {
                            $trangThai = 'Chờ xử lý';
                        } elseif ($trangThaiGoc == 'Tiếp nhận') {
                            $trangThai = 'Tiếp nhận';
                        } elseif ($trangThaiGoc == 'Hoàn thành') {
                            $trangThai = 'Hoàn thành';
                        } elseif ($trangThaiGoc == 'Đã trả') {
                            $trangThai = 'Đã trả';
                        } else {
                            $trangThai = 'Chờ xử lý';
                        }
                        $badgeColors = [
                            'Chờ xử lý' => '#6c757d',
                            'Tiếp nhận' => '#fd7e14',
                            'Hoàn thành' => '#198754',
                            'Đã trả' => '#20c997',
                        ];
                        $bgColor = $badgeColors[$trangThai] ?? '#6c757d';
                        ?>
                        <span style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;color:#fff;background:<?= $bgColor ?>;">
                            <?= e($trangThai) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= url('khach/xemdon/' . $d['MaPhieu']) ?>" class="btn btn-sm btn-info">Xem</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Thông tin liên hệ -->
<div class="khach-contact">
    <div class="khach-contact-header">
        <h3>CÔNG TY TNHH CÔNG NGHỆ CAO HÙNG</h3>
    </div>
    <div class="khach-contact-body">
        <div class="khach-contact-item">
            <div class="khach-contact-icon">📍</div>
            <div>
                <strong>Địa chỉ</strong>
                <p>Số 189A, Nguyễn Đáng, Khóm 6, Phường Nguyệt Hóa, Vĩnh Long</p>
            </div>
        </div>
        <div class="khach-contact-item">
            <div class="khach-contact-icon">📞</div>
            <div>
                <strong>Hotline</strong>
                <p style="font-size:20px;color:#1f4978;font-weight:700;">094.179.1313</p>
            </div>
        </div>
        <div class="khach-contact-item">
            <div class="khach-contact-icon">✉️</div>
            <div>
                <strong>Email</strong>
                <p>caohungtech@gmail.com</p>
            </div>
        </div>
        <div class="khach-contact-item">
            <div class="khach-contact-icon">⏰</div>
            <div>
                <strong>Giờ làm việc</strong>
                <p>Thứ 2 - Thứ 7: 7:30 - 17:30 &nbsp;|&nbsp; Chủ nhật: 8:00 - 17:00</p>
            </div>
        </div>
    </div>
</div>

<style>
/* === KHACH BANNER === */
.khach-banner {
    background: linear-gradient(135deg, #203b63 0%, #1f4978 52%, #18406d 100%);
    border-radius: 12px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(20,44,76,0.28);
}
.khach-banner-inner {
    padding: 35px 30px;
    text-align: center;
}
.khach-banner-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
}
.khach-logo-icon {
    width: 102px; height: 102px;
    display: flex; align-items: center; justify-content: center;
    background: transparent;
}
.khach-logo-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3)) saturate(1.08) contrast(1.08);
}
.khach-banner-logo h1 {
    font-size: 26px; color: #fff; font-weight: 800;
    letter-spacing: 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.15);
    margin: 0;
}
.khach-slogan {
    color: rgba(255,255,255,0.85); font-size: 13px; font-style: italic; margin: 2px 0 0;
}
.khach-banner-services {
    display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;
}
.khach-banner-services span {
    background: rgba(255,255,255,0.2);
    color: #fff; font-size: 13px; font-weight: 600;
    padding: 6px 16px; border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.3);
}

/* === KHACH ACTIONS === */
.khach-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 5px;
}
.khach-action-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    text-decoration: none;
    border: 2px solid #f0f0f0;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    cursor: pointer;
}
.khach-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.khach-action-icon {
    font-size: 48px; margin-bottom: 12px;
}
.khach-action-card h3 {
    font-size: 16px; font-weight: 700; margin: 0 0 6px; color: #333;
}
.khach-action-card p {
    font-size: 13px; color: #888; margin: 0;
}
.khach-action-new:hover { border-color: #e6a817; }
.khach-action-new h3 { color: #1f4978; }
.khach-action-orders:hover { border-color: #c41e3a; }
.khach-action-orders h3 { color: #c41e3a; }
.khach-action-search:hover { border-color: #3498db; }
.khach-action-search h3 { color: #3498db; }

/* === KHACH CONTACT === */
.khach-contact {
    margin-top: 25px;
    background: #fff;
    border-radius: 12px;
    border: 2px solid #1f4978;
    overflow: hidden;
}
.khach-contact-header {
    background: linear-gradient(135deg, #285387 0%, #1f4978 52%, #18406d 100%);
    padding: 14px 20px;
}
.khach-contact-header h3 {
    color: #fff; font-size: 15px; font-weight: 700;
    letter-spacing: 0.5px; margin: 0; text-align: center;
}
.khach-contact-body {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0;
}
.khach-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 18px 20px;
    border-bottom: 1px solid #f0f0f0;
    border-right: 1px solid #f0f0f0;
}
.khach-contact-item:nth-child(2n) {
    border-right: none;
}
.khach-contact-item:nth-last-child(-n+2) {
    border-bottom: none;
}
.khach-contact-icon {
    font-size: 24px; flex-shrink: 0; margin-top: 2px;
}
.khach-contact-item strong {
    display: block; font-size: 12px; color: #999; text-transform: uppercase;
    letter-spacing: 0.5px; margin-bottom: 3px;
}
.khach-contact-item p {
    margin: 0; font-size: 14px; color: #333; line-height: 1.5;
}

@media (max-width: 768px) {
    .khach-actions { grid-template-columns: 1fr; }
    .khach-contact-body { grid-template-columns: 1fr; }
    .khach-contact-item { border-right: none !important; }
}
</style>
