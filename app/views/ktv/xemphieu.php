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

<!-- Phần bình luận -->
<div class="card mt-20 no-print" id="binhLuanSection">
    <div class="card-header comment-header-custom">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <h3 style="margin:0;color:white;font-size:18px;">💬 Bình Luận & Trao Đổi</h3>
            <span class="badge comment-badge-custom"><?= count($binhLuan ?? []) ?> bình luận</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Form thêm bình luận -->
        <div class="comment-form">
            <textarea id="commentInput" class="form-control" rows="3" placeholder="Nhập bình luận... (Admin, KTV, Nhân viên có thể trao đổi ở đây)"></textarea>
            <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                <button type="button" onclick="themBinhLuan()" class="btn btn-primary">Gửi bình luận</button>
            </div>
        </div>

        <!-- Danh sách bình luận -->
        <div id="commentList" class="comment-list">
            <?php if (empty($binhLuan)): ?>
                <div class="empty-state" id="emptyComment">
                    <p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                </div>
            <?php else: ?>
                <?php foreach ($binhLuan as $bl): ?>
                    <?php
                    $isOwner = ($bl['TenDangNhap'] ?? '') === ($_SESSION['user']['TenDangNhap'] ?? '');
                    $isAdmin = ($_SESSION['user']['LoaiTK'] ?? '') === 'admin';
                    $colorClass = '';
                    switch ($bl['LoaiTaiKhoan'] ?? '') {
                        case 'admin': $colorClass = 'comment-admin'; break;
                        case 'ktv': $colorClass = 'comment-ktv'; break;
                        case 'nhanvien': $colorClass = 'comment-nhanvien'; break;
                    }
                    ?>
                    <?php $canManageComment = $isOwner || in_array(($_SESSION['user']['LoaiTK'] ?? ''), ['admin', 'Quản lý']); ?>
                    <div class="comment-item <?= $colorClass ?>" data-id="<?= $bl['MaBinhLuan'] ?>" data-content="<?= e($bl['NoiDung'] ?? '') ?>">
                        <div class="comment-header">
                            <div class="comment-author">
                                <strong><?= e($bl['HoTen'] ?? 'Unknown') ?></strong>
                                <span class="comment-role">(<?= e($bl['LoaiTaiKhoan'] ?? '') ?>)</span>
                            </div>
                            <div class="comment-meta">
                                <span class="comment-time"><?= date('d/m/Y H:i', strtotime($bl['ThoiGian'])) ?></span>
                                <?php if ($canManageComment): ?>
                                    <button type="button" onclick="batDauSuaBinhLuan(<?= $bl['MaBinhLuan'] ?>)" class="btn-edit-comment" title="Sửa">Sửa</button>
                                    <button type="button" onclick="xoaBinhLuan(<?= $bl['MaBinhLuan'] ?>)" class="btn-delete-comment" title="Xóa">×</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="comment-content"><?= nl2br(e($bl['NoiDung'] ?? '')) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Header bình luận nổi bật */
.comment-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 18px 20px;
    border-radius: 12px 12px 0 0;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.comment-badge-custom {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

/* Bình luận */
.comment-form {
    border-bottom: 1px solid #eee;
    padding-bottom: 20px;
    margin-bottom: 20px;
}
.comment-list {
    max-height: 500px;
    overflow-y: auto;
}
.comment-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 12px;
    border-left: 3px solid #ddd;
    transition: box-shadow 0.2s;
}
.comment-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.comment-admin {
    border-left-color: #dc3545;
    background: #fff5f5;
}
.comment-ktv {
    border-left-color: #0d6efd;
    background: #f0f7ff;
}
.comment-nhanvien {
    border-left-color: #198754;
    background: #f0fff4;
}
.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.comment-author {
    display: flex;
    align-items: center;
    gap: 6px;
}
.comment-role {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
}
.comment-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}
.comment-time {
    font-size: 12px;
    color: #999;
}
.comment-content {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
}
.btn-delete-comment {
    background: #dc3545;
    color: white;
    border: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    padding: 0;
    transition: background 0.2s;
}
.btn-delete-comment:hover {
    background: #bb2d3b;
}
.btn-edit-comment {
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-size: 11px;
    line-height: 1;
    padding: 6px 10px;
    transition: background 0.2s;
}
.btn-edit-comment:hover {
    background: #0b5ed7;
}
.comment-edit-wrap {
    margin-top: 8px;
}
.comment-edit-actions {
    margin-top: 8px;
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}
</style>

<script>
const maPhieu = <?= $phieu['MaPhieu'] ?>;

function themBinhLuan() {
    const noiDung = document.getElementById('commentInput').value.trim();
    if (!noiDung) {
        alert('Vui lòng nhập nội dung bình luận!');
        return;
    }

    fetch('<?= url('ktv/thembinhluan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `MaPhieu=${maPhieu}&NoiDung=${encodeURIComponent(noiDung)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentInput').value = '';
            const empty = document.getElementById('emptyComment');
            if (empty) empty.remove();
            
            const newComment = createCommentElement(data.data);
            document.getElementById('commentList').insertAdjacentHTML('beforeend', newComment);
            
            document.querySelector('.comment-list').scrollTop = document.querySelector('.comment-list').scrollHeight;
            updateCommentCount();
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể thêm bình luận'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra!');
    });
}

function xoaBinhLuan(maBinhLuan) {
    if (!confirm('Bạn có chắc muốn xóa bình luận này?')) return;

    fetch('<?= url('ktv/xoabinhluan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `MaBinhLuan=${maBinhLuan}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`.comment-item[data-id="${maBinhLuan}"]`);
            if (item) {
                item.remove();
                updateCommentCount();
                
                if (document.querySelectorAll('.comment-item').length === 0) {
                    document.getElementById('commentList').innerHTML = '<div class="empty-state" id="emptyComment"><p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p></div>';
                }
            }
        } else {
            alert('Không thể xóa bình luận!');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra!');
    });
}

function createCommentElement(data) {
    const role = data.LoaiTaiKhoan || '';
    let colorClass = '';
    switch(role) {
        case 'admin': colorClass = 'comment-admin'; break;
        case 'ktv': colorClass = 'comment-ktv'; break;
        case 'nhanvien': colorClass = 'comment-nhanvien'; break;
    }
    
    const time = new Date(data.ThoiGian).toLocaleString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Hiển thị nút sửa/xóa nếu có quyền
    const canManage = data.canManage !== undefined ? data.canManage : true;
    const actionButtons = canManage ? `
        <button type="button" onclick="batDauSuaBinhLuan(${data.MaBinhLuan})" class="btn-edit-comment" title="Sửa">Sửa</button>
        <button type="button" onclick="xoaBinhLuan(${data.MaBinhLuan})" class="btn-delete-comment" title="Xóa">×</button>
    ` : '';
    
    return `
        <div class="comment-item ${colorClass}" data-id="${data.MaBinhLuan}" data-content="${escapeAttr(data.NoiDung)}">
            <div class="comment-header">
                <div class="comment-author">
                    <strong>${escapeHtml(data.HoTen || '')}</strong>
                    <span class="comment-role">(${escapeHtml(role)})</span>
                </div>
                <div class="comment-meta">
                    <span class="comment-time">${time}</span>
                    ${actionButtons}
                </div>
            </div>
            <div class="comment-content">${formatCommentContent(data.NoiDung || '')}</div>
        </div>
    `;
}

function batDauSuaBinhLuan(maBinhLuan) {
    const item = document.querySelector(`.comment-item[data-id="${maBinhLuan}"]`);
    if (!item || item.dataset.editing === '1') return;

    const noiDung = item.getAttribute('data-content') || '';
    const contentEl = item.querySelector('.comment-content');
    if (!contentEl) return;

    item.dataset.editing = '1';
    contentEl.style.display = 'none';

    const editor = document.createElement('div');
    editor.className = 'comment-edit-wrap';
    editor.innerHTML = `
        <textarea class="form-control comment-edit-input" rows="3">${escapeHtml(noiDung)}</textarea>
        <div class="comment-edit-actions">
            <button type="button" class="btn btn-sm btn-secondary" onclick="huySuaBinhLuan(${maBinhLuan})">Hủy</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="luuSuaBinhLuan(${maBinhLuan})">Lưu</button>
        </div>
    `;
    contentEl.insertAdjacentElement('afterend', editor);
}

function huySuaBinhLuan(maBinhLuan) {
    const item = document.querySelector(`.comment-item[data-id="${maBinhLuan}"]`);
    if (!item) return;
    item.dataset.editing = '0';

    const editor = item.querySelector('.comment-edit-wrap');
    if (editor) editor.remove();

    const contentEl = item.querySelector('.comment-content');
    if (contentEl) contentEl.style.display = '';
}

function luuSuaBinhLuan(maBinhLuan) {
    const item = document.querySelector(`.comment-item[data-id="${maBinhLuan}"]`);
    if (!item) return;

    const input = item.querySelector('.comment-edit-input');
    if (!input) return;

    const noiDung = input.value.trim();
    if (!noiDung) {
        alert('Nội dung bình luận không được để trống!');
        input.focus();
        return;
    }

    fetch('<?= url('ktv/suabinhluan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `MaBinhLuan=${maBinhLuan}&NoiDung=${encodeURIComponent(noiDung)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            item.setAttribute('data-content', noiDung);
            const contentEl = item.querySelector('.comment-content');
            if (contentEl) contentEl.innerHTML = formatCommentContent(noiDung);
            huySuaBinhLuan(maBinhLuan);
        } else {
            alert(data.message || 'Không thể sửa bình luận!');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra!');
    });
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeAttr(str) {
    return escapeHtml(str).replace(/`/g, '&#096;');
}

function formatCommentContent(str) {
    return escapeHtml(str).replace(/\n/g, '<br>');
}

function updateCommentCount() {
    const count = document.querySelectorAll('.comment-item').length;
    const badge = document.querySelector('#binhLuanSection .badge');
    if (badge) {
        badge.textContent = `${count} bình luận`;
    }
}

// Real-time comment polling
let lastCommentTime = '<?= date("Y-m-d H:i:s") ?>';
let pollingInterval = null;

function pollNewComments() {
    fetch('<?= url('ktv/laybinhluanmoi') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `MaPhieu=${maPhieu}&afterTime=${encodeURIComponent(lastCommentTime)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.comments && data.comments.length > 0) {
            const commentList = document.getElementById('commentList');
            const empty = document.getElementById('emptyComment');
            if (empty) empty.remove();
            
            data.comments.forEach(comment => {
                // Kiểm tra xem comment này đã tồn tại chưa
                if (!document.querySelector(`.comment-item[data-id="${comment.MaBinhLuan}"]`)) {
                    const newCommentHTML = createCommentElement(comment);
                    commentList.insertAdjacentHTML('beforeend', newCommentHTML);
                    
                    // Update last time
                    lastCommentTime = comment.ThoiGian;
                    
                    // Scroll to new comment
                    const commentListContainer = document.querySelector('.comment-list');
                    if (commentListContainer) {
                        commentListContainer.scrollTop = commentListContainer.scrollHeight;
                    }
                }
            });
            
            // Update badge count
            updateCommentCount();
        }
    })
    .catch(err => {
        console.error('Polling error:', err);
    });
}

// Bắt đầu polling khi trang load
document.addEventListener('DOMContentLoaded', function() {
    // Poll every 2 seconds
    pollingInterval = setInterval(pollNewComments, 2000);
});

// Dừng polling khi rời khỏi trang
window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
