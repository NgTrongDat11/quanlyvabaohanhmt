<div class="page-header">
    <div>
        <h1>Gửi Bảo Hành</h1>
        <p>Quản lý các thiết bị gửi đi trung tâm bảo hành</p>
    </div>
    <button class="btn btn-primary" onclick="toggleForm('formTaoBH')">+ Thêm Mới</button>
</div>

<!-- Flash messages -->
<?php if ($flash = flash('success')): ?>
    <div style="background:#d4edda;color:#155724;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">✅ <?= e($flash) ?></div>
<?php elseif ($flash = flash('error')): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #f5c6cb;">❌ <?= e($flash) ?></div>
<?php endif; ?>

<!-- Form thêm mới (ẩn mặc định) -->
<div id="formTaoBH" style="display:none; margin-bottom:20px;">
    <div class="card">
        <div class="card-header"><h3>Tạo Bản Ghi Gửi Bảo Hành</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= url('admin/luubaohanh') ?>">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Phiếu Sửa Chữa *</label>
                        <select name="MaPhieu" class="form-control" required>
                            <option value="">-- Chọn phiếu --</option>
                            <?php foreach ($dsPhieu as $p): ?>
                                <option value="<?= $p['MaPhieu'] ?>">
                                    #<?= $p['MaPhieu'] ?> - <?= e($p['LoaiDichVu'] ?? '') ?> (<?= e($p['TinhTrang']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tên Trung Tâm Bảo Hành *</label>
                        <div style="display:flex;gap:6px;">
                            <?php if (!empty($dsTrungTamBH)): ?>
                            <select name="TenTrungTamBH" id="selectTTBH" class="form-control" onchange="chonTTBH(this)" style="flex:1;" required>
                                <option value="">-- Chọn từ danh mục --</option>
                                <?php foreach ($dsTrungTamBH as $i => $tt): ?>
                                    <option value="<?= e($tt['Ten']) ?>" data-diachi="<?= e($tt['DiaChi'] ?? '') ?>" data-sdt="<?= e($tt['SoDienThoai'] ?? '') ?>"><?= e($tt['Ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-success" onclick="document.getElementById('modalThemTTBH').style.display='flex'" title="Thêm mới vào danh mục" style="white-space:nowrap;padding:6px 12px;">➕ Thêm</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ngày Gửi *</label>
                        <input type="datetime-local" name="NgayGui" class="form-control"
                               value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày Nhận Lại (dự kiến) *</label>
                        <input type="datetime-local" name="NgayNhanLai" class="form-control"
                               value="<?= date('Y-m-d\TH:i', strtotime('+14 days')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Kết Quả Bảo Hành</label>
                        <input type="text" name="KetQuaBaoHanh" class="form-control"
                               placeholder="Để trống nếu chưa có kết quả">
                    </div>
                    <div class="form-group">
                        <label>Ghi Chú</label>
                        <input type="text" name="GhiChu" class="form-control"
                               placeholder="Ghi chú thêm...">
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ Trung Tâm</label>
                        <input type="text" name="DiaChi" class="form-control"
                               placeholder="Địa chỉ trung tâm bảo hành...">
                    </div>
                    <div class="form-group">
                        <label>Số Điện Thoại Liên Hệ</label>
                        <input type="text" name="SoDienThoai" class="form-control"
                               placeholder="SĐT liên hệ trung tâm...">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleForm('formTaoBH')">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3>📋 Danh Sách Gửi Bảo Hành</h3>
        <span style="background:var(--primary-red);color:white;padding:4px 14px;border-radius:20px;font-size:13px;">
            <?= count($dsBaoHanh) ?> bản ghi
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($dsBaoHanh)): ?>
            <p style="text-align:center;padding:40px;color:#999;">Chưa có bản ghi gửi bảo hành nào.</p>
        <?php else: ?>
        <?php
        // Phân trang
        $perPage = 10;
        $pagPage = max(1, intval($_GET['trang'] ?? 1));
        $pagTotal = count($dsBaoHanh);
        $pagTotalPages = max(1, ceil($pagTotal / $perPage));
        $pagPage = min($pagPage, $pagTotalPages);
        $offset = ($pagPage - 1) * $perPage;
        $pagedItems = array_slice($dsBaoHanh, $offset, $perPage);
        ?>
        <table class="table" style="margin:0;font-size:14px;">
            <thead>
                <tr>
                    <th class="stt-col">STT</th>
                    <th>Phiếu SC</th>
                    <th>Khách Hàng</th>
                    <th>Trung Tâm Bảo Hành</th>
                    <th>Ngày Gửi</th>
                    <th>Ngày Nhận Lại</th>
                    <th>Kết Quả</th>
                    <th>Ghi Chú</th>
                    <th style="width:140px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pagedItems as $idx => $bh): ?>
                <tr>
                    <td class="stt-col"><?= $offset + $idx + 1 ?></td>
                    <td><strong>#<?= $bh['MaPhieu'] ?></strong></td>
                    <td>
                        <?= e($bh['TenKhachHang'] ?? 'N/A') ?>
                        <?php if (!empty($bh['SoDienThoai'])): ?>
                            <br><small style="color:#888;"><?= e($bh['SoDienThoai']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= e($bh['TenTrungTamBH']) ?></td>
                    <td><?= date('d/m/Y', strtotime($bh['NgayGui'])) ?></td>
                    <td><?= $bh['NgayNhanLai'] ? date('d/m/Y', strtotime($bh['NgayNhanLai'])) : '<span style="color:#999">Chưa xác định</span>' ?></td>
                    <td>
                        <?php if (!empty($bh['KetQuaBaoHanh'])): ?>
                            <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:20px;font-size:12px;">
                                <?= e($bh['KetQuaBaoHanh']) ?>
                            </span>
                        <?php else: ?>
                            <span style="color:#999;font-size:12px;">Chờ kết quả</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#666;font-size:13px;"><?= e($bh['GhiChu']) ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-sm btn-primary"
                                onclick="moModalCapNhat('bh', <?= htmlspecialchars(json_encode($bh), ENT_QUOTES) ?>)">
                                 Sửa
                            </button>
                            <form method="POST" action="<?= url('admin/xoabaohanh/' . $bh['MaBaoHanh']) ?>" style="display:inline;" onsubmit="return confirm('Xóa bản ghi bảo hành #<?= $bh['MaBaoHanh'] ?>?')">
                                <button type="submit" class="btn btn-sm btn-danger">  Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        $pagPerPage = $perPage;
        $pagBaseUrl = url('admin/baohanh');
        $pagParams = [];
        include ROOT_PATH . '/app/views/partials/pagination.php';
        ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Cập Nhật Bảo Hành -->
<div id="modalCapNhatBH" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:30px;width:560px;max-width:95vw;max-height:90vh;overflow-y:auto;position:relative;">
        <h3 style="margin-top:0;">✏️ Cập Nhật Bảo Hành</h3>
        <form method="POST" action="<?= url('admin/capnhatbaohanh') ?>">
            <input type="hidden" name="MaBaoHanh" id="capnhat_MaBaoHanh">
            <div class="form-group">
                <label>Trung Tâm Bảo Hành</label>
                <input type="text" name="TenTrungTamBH" id="capnhat_TenTrungTamBH" class="form-control" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="form-group">
                    <label>Ngày Gửi</label>
                    <input type="datetime-local" name="NgayGui" id="capnhat_NgayGui" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Ngày Nhận Lại</label>
                    <input type="datetime-local" name="NgayNhanLai" id="capnhat_NgayNhanLai" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kết Quả Bảo Hành</label>
                <input type="text" name="KetQuaBaoHanh" id="capnhat_KetQuaBaoHanh" class="form-control"
                       placeholder="Đã sửa xong / Thay bo mạch... ">
            </div>
            <div class="form-group">
                <label>Ghi Chú</label>
                <textarea name="GhiChu" id="capnhat_GhiChu" class="form-control" rows="2"></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Lưu Cập Nhật</button>
                <button type="button" class="btn btn-secondary" onclick="dongModal('modalCapNhatBH')">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm Trung Tâm BH vào danh mục -->
<div id="modalThemTTBH" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1001;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:25px;width:420px;max-width:95vw;">
        <h3 style="margin-top:0;">➕ Thêm Trung Tâm Bảo Hành</h3>
        <div class="form-group">
            <label>Tên Trung Tâm *</label>
            <input type="text" id="new_bh_ten" class="form-control" placeholder="VD: Trung tâm BH ASUS...">
        </div>
        <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" id="new_bh_diachi" class="form-control" placeholder="Địa chỉ...">
        </div>
        <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" id="new_bh_sdt" class="form-control" placeholder="SĐT liên hệ...">
        </div>
        <div id="new_bh_msg" style="color:#28a745;font-size:13px;margin-bottom:6px;display:none;"></div>
        <div style="display:flex;gap:10px;">
            <button type="button" class="btn btn-primary" onclick="luuDanhMucBH()">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalThemTTBH').style.display='none'">Hủy</button>
        </div>
        <?php if (!empty($dsTrungTamBH)): ?>
        <hr style="margin:15px 0 10px;">
        <p style="font-size:12px;color:#888;margin-bottom:8px;">Danh mục đã lưu:</p>
        <div id="listTTBH">
        <?php foreach ($dsTrungTamBH as $i => $tt): ?>
        <div id="dm_row_bh_<?= $i ?>" style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f0f0f0;">
            <span style="font-size:13px;"><?= e($tt['Ten']) ?> <?= !empty($tt['SoDienThoai']) ? '<small style="color:#888;">- ' . e($tt['SoDienThoai']) . '</small>' : '' ?></span>
            <div style="display:flex;gap:4px;">
                <button type="button" onclick="suaDanhMucBH(<?= $i ?>, <?= htmlspecialchars(json_encode($tt, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>)" style="background:none;border:none;color:#e6a817;cursor:pointer;font-size:13px;" title="Sửa">✏️</button>
                <button type="button" onclick="xoaDanhMucBH(<?= $i ?>, this)" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:14px;" title="Xóa">×</button>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Sửa Trung Tâm BH -->
<div id="modalSuaTTBH" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1002;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:25px;width:420px;max-width:95vw;">
        <h3 style="margin-top:0;">✏️ Sửa Trung Tâm Bảo Hành</h3>
        <input type="hidden" id="sua_bh_idx">
        <div class="form-group">
            <label>Tên Trung Tâm *</label>
            <input type="text" id="sua_bh_ten" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" id="sua_bh_diachi" class="form-control">
        </div>
        <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" id="sua_bh_sdt" class="form-control">
        </div>
        <div id="sua_bh_msg" style="color:#28a745;font-size:13px;margin-bottom:6px;display:none;"></div>
        <div style="display:flex;gap:10px;">
            <button type="button" class="btn btn-primary" onclick="capNhatDanhMucBH()">Cập nhật</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalSuaTTBH').style.display='none'">Hủy</button>
        </div>
    </div>
</div>

<script>
var BH_AJAX_URL_THEM = '<?= url('admin/themdanhmuc') ?>';
var BH_AJAX_URL_XOA  = '<?= url('admin/xoadanhmuc') ?>';
var BH_AJAX_URL_SUA  = '<?= url('admin/suadanhmuc') ?>';

function toggleForm(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
function chonTTBH(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    var dcInput = document.querySelector('#formTaoBH input[name="DiaChi"]');
    var sdtInput = document.querySelector('#formTaoBH input[name="SoDienThoai"]');
    if (dcInput) dcInput.value = opt.getAttribute('data-diachi') || '';
    if (sdtInput) sdtInput.value = opt.getAttribute('data-sdt') || '';
}

function dmAjax(url, body, cb) {
    body.append('ajax', '1');
    fetch(url, {method:'POST', body:body})
        .then(r => r.json()).then(cb)
        .catch(() => alert('Lỗi kết nối!'));
}

function addOptionToSelect(ten, diachi, sdt, idx) {
    var sel = document.getElementById('selectTTBH');
    if (!sel) {
        // create select if not exists
        var wrap = document.getElementById('inputTenTTBH').previousElementSibling;
        sel = document.createElement('select');
        sel.id = 'selectTTBH'; sel.className = 'form-control'; sel.style.flex = '1';
        sel.setAttribute('onchange','chonTTBH(this)');
        var ph = document.createElement('option'); ph.value=''; ph.textContent='-- Chọn từ danh mục --';
        sel.appendChild(ph); wrap.insertBefore(sel, wrap.firstChild);
    }
    var opt = document.createElement('option');
    opt.value = idx; opt.setAttribute('data-ten', ten);
    opt.setAttribute('data-diachi', diachi||''); opt.setAttribute('data-sdt', sdt||'');
    opt.textContent = ten; sel.appendChild(opt);
}

function luuDanhMucBH() {
    var ten = document.getElementById('new_bh_ten').value.trim();
    if (!ten) { alert('Vui lòng nhập tên!'); return; }
    var body = new FormData();
    body.append('loai','TrungTamBH'); body.append('ten', ten);
    body.append('diachi', document.getElementById('new_bh_diachi').value.trim());
    body.append('sdt', document.getElementById('new_bh_sdt').value.trim());
    dmAjax(BH_AJAX_URL_THEM, body, function(r) {
        if (!r.ok) { alert(r.msg || 'Lỗi!'); return; }
        var sdt = document.getElementById('new_bh_sdt').value.trim();
        var diachi = document.getElementById('new_bh_diachi').value.trim();
        // add to select dropdown
        addOptionToSelect(ten, diachi, sdt, r.idx);
        // add to list
        var list = document.getElementById('listTTBH');
        if (!list) { var m=document.getElementById('modalThemTTBH').querySelector('div'); var hr=document.createElement('hr'); hr.style.margin='15px 0 10px'; m.appendChild(hr); var p=document.createElement('p'); p.style.cssText='font-size:12px;color:#888;margin-bottom:8px'; p.textContent='Danh mục đã lưu:'; m.appendChild(p); list=document.createElement('div'); list.id='listTTBH'; m.appendChild(list); }
        var row = document.createElement('div');
        row.id = 'dm_row_bh_'+r.idx;
        row.style.cssText = 'display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f0f0f0;';
        row.innerHTML = '<span style="font-size:13px;">'+ten+(sdt?'<small style="color:#888;"> - '+sdt+'</small>':'')+'</span>'
            +'<div style="display:flex;gap:4px;">'
            +'<button type="button" onclick="suaDanhMucBH('+r.idx+','+JSON.stringify({Ten:ten,DiaChi:diachi,SoDienThoai:sdt})+',this)" style="background:none;border:none;color:#e6a817;cursor:pointer;font-size:13px;" title="Sửa">✏️</button>'
            +'<button type="button" onclick="xoaDanhMucBH('+r.idx+',this)" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:14px;" title="Xóa">×</button>'
            +'</div>';
        list.appendChild(row);
        // clear inputs
        document.getElementById('new_bh_ten').value='';
        document.getElementById('new_bh_diachi').value='';
        document.getElementById('new_bh_sdt').value='';
        var msg = document.getElementById('new_bh_msg');
        msg.textContent = '✅ Đã thêm "'+ten+'"!'; msg.style.display='block';
        setTimeout(()=>{ msg.style.display='none'; }, 2500);
    });
}

function xoaDanhMucBH(idx, btn) {
    if (!confirm('Xóa khỏi danh mục?')) return;
    var body = new FormData();
    body.append('loai','TrungTamBH'); body.append('idx', idx);
    dmAjax(BH_AJAX_URL_XOA, body, function(r) {
        if (!r.ok) { alert('Lỗi xóa!'); return; }
        var row = document.getElementById('dm_row_bh_'+idx);
        if (row) row.remove();
        var sel = document.getElementById('selectTTBH');
        if (sel) { for (var i=0;i<sel.options.length;i++) { if (sel.options[i].value==idx) { sel.remove(i); break; } } }
    });
}

function suaDanhMucBH(idx, data) {
    document.getElementById('sua_bh_idx').value = idx;
    document.getElementById('sua_bh_ten').value = data.Ten || '';
    document.getElementById('sua_bh_diachi').value = data.DiaChi || '';
    document.getElementById('sua_bh_sdt').value = data.SoDienThoai || '';
    document.getElementById('sua_bh_msg').style.display = 'none';
    document.getElementById('modalSuaTTBH').style.display = 'flex';
}

function capNhatDanhMucBH() {
    var idx = document.getElementById('sua_bh_idx').value;
    var ten = document.getElementById('sua_bh_ten').value.trim();
    if (!ten) { alert('Vui lòng nhập tên!'); return; }
    var diachi = document.getElementById('sua_bh_diachi').value.trim();
    var sdt = document.getElementById('sua_bh_sdt').value.trim();
    var body = new FormData();
    body.append('loai','TrungTamBH'); body.append('idx', idx);
    body.append('ten', ten); body.append('diachi', diachi); body.append('sdt', sdt);
    dmAjax(BH_AJAX_URL_SUA, body, function(r) {
        if (!r.ok) { alert(r.msg || 'Lỗi!'); return; }
        var row = document.getElementById('dm_row_bh_'+idx);
        if (row) row.querySelector('span').innerHTML = ten+(sdt?'<small style="color:#888;"> - '+sdt+'</small>':'');
        var sel = document.getElementById('selectTTBH');
        if (sel) { for (var i=0;i<sel.options.length;i++) { if (sel.options[i].value==idx) { sel.options[i].textContent=ten; sel.options[i].setAttribute('data-ten',ten); sel.options[i].setAttribute('data-diachi',diachi); sel.options[i].setAttribute('data-sdt',sdt); break; } } }
        var msg = document.getElementById('sua_bh_msg');
        msg.textContent = '✅ Đã cập nhật!'; msg.style.display='block';
        setTimeout(()=>{ document.getElementById('modalSuaTTBH').style.display='none'; }, 1200);
    });
}

function moModalCapNhat(type, data) {
    if (type === 'bh') {
        document.getElementById('capnhat_MaBaoHanh').value = data.MaBaoHanh;
        document.getElementById('capnhat_TenTrungTamBH').value = data.TenTrungTamBH;
        document.getElementById('capnhat_NgayGui').value = data.NgayGui ? data.NgayGui.substring(0,16) : '';
        document.getElementById('capnhat_NgayNhanLai').value = data.NgayNhanLai ? data.NgayNhanLai.substring(0,16) : '';
        document.getElementById('capnhat_KetQuaBaoHanh').value = data.KetQuaBaoHanh || '';
        document.getElementById('capnhat_GhiChu').value = data.GhiChu || '';
        document.getElementById('modalCapNhatBH').style.display = 'flex';
    }
}
function dongModal(id) { document.getElementById(id).style.display = 'none'; }
document.getElementById('modalCapNhatBH').addEventListener('click', function(e) { if (e.target===this) dongModal('modalCapNhatBH'); });
document.getElementById('modalThemTTBH').addEventListener('click', function(e) { if (e.target===this) this.style.display='none'; });
document.getElementById('modalSuaTTBH').addEventListener('click', function(e) { if (e.target===this) this.style.display='none'; });

<?php if (!empty($_GET['form'])): ?>
document.addEventListener('DOMContentLoaded', function() { toggleForm('formTaoBH'); });
<?php endif; ?>
</script>
