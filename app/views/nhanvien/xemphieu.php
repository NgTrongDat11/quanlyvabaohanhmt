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

    fetch('<?= url('nhanvien/thembinhluan') ?>', {
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

    fetch('<?= url('nhanvien/xoabinhluan') ?>', {
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
    
    return `
        <div class="comment-item ${colorClass}" data-id="${data.MaBinhLuan}" data-content="${escapeAttr(data.NoiDung)}">
            <div class="comment-header">
                <div class="comment-author">
                    <strong>${escapeHtml(data.HoTen || '')}</strong>
                    <span class="comment-role">(${escapeHtml(role)})</span>
                </div>
                <div class="comment-meta">
                    <span class="comment-time">${time}</span>
                    <button type="button" onclick="batDauSuaBinhLuan(${data.MaBinhLuan})" class="btn-edit-comment" title="Sửa">Sửa</button>
                    <button type="button" onclick="xoaBinhLuan(${data.MaBinhLuan})" class="btn-delete-comment" title="Xóa">×</button>
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

    fetch('<?= url('nhanvien/suabinhluan') ?>', {
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
</script>

<?php if (isset($_GET['print'])): ?>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>
