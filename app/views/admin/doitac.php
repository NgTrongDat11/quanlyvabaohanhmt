<div class="page-header">
    <div>
        <h1>Gửi Đối Tác</h1>
        <p>Quản lý các thiết bị gửi đến đối tác xử lý</p>
    </div>
    <button class="btn btn-primary" onclick="toggleForm('formTaoDT')">+ Thêm Mới</button>
</div>

<!-- Flash messages -->
<?php if ($flash = flash('success')): ?>
    <div style="background:#d4edda;color:#155724;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">✅ <?= e($flash) ?></div>
<?php elseif ($flash = flash('error')): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px 18px;border-radius:6px;margin-bottom:15px;border:1px solid #f5c6cb;">❌ <?= e($flash) ?></div>
<?php endif; ?>

<!-- Form thêm mới -->
<div id="formTaoDT" style="display:none; margin-bottom:20px;">
    <div class="card">
        <div class="card-header"><h3>Tạo Bản Ghi Gửi Đối Tác</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= url('admin/luudoitac') ?>">
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
                        <label>Tên Đối Tác *</label>
                        <div style="display:flex;gap:6px;">
                            <?php if (!empty($dsDanhMucDT)): ?>
                            <select name="TenDoiTac" id="selectDoiTac" class="form-control" onchange="chonDoiTac(this)" style="flex:1;" required>
                                <option value="">-- Chọn từ danh mục --</option>
                                <?php foreach ($dsDanhMucDT as $i => $dt): ?>
                                    <option value="<?= e($dt['Ten']) ?>" data-diachi="<?= e($dt['DiaChi'] ?? '') ?>" data-sdt="<?= e($dt['SoDienThoai'] ?? '') ?>"><?= e($dt['Ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-success" onclick="document.getElementById('modalThemDT').style.display='flex'" title="Thêm mới vào danh mục" style="white-space:nowrap;padding:6px 12px;">➕ Thêm</button>
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
                               value="<?= date('Y-m-d\TH:i', strtotime('+7 days')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Trạng Thái</label>
                        <select name="TrangThai" class="form-control">
                            <option value="Đang xử lý"> Đang xử lý</option>
                            <option value="Hoàn thành"> Hoàn thành</option>
                            <option value="Trả lại"> Trả lại</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label>Ghi Chú</label>
                        <input type="text" name="GhiChu" class="form-control"
                               placeholder="Ghi chú thêm...">
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ Đối Tác</label>
                        <input type="text" name="DiaChi" class="form-control"
                               placeholder="Địa chỉ đối tác...">
                    </div>
                    <div class="form-group">
                        <label>Số Điện Thoại Liên Hệ</label>
                        <input type="text" name="SoDienThoai" class="form-control"
                               placeholder="SĐT liên hệ đối tác...">
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" class="btn btn-primary">Lưu Bản Ghi</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleForm('formTaoDT')">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3>📋 Danh Sách Gửi Đối Tác</h3>
        <span style="background:var(--primary-red);color:white;padding:4px 14px;border-radius:20px;font-size:13px;">
            <?= count($dsDoiTac) ?> bản ghi
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($dsDoiTac)): ?>
            <p style="text-align:center;padding:40px;color:#999;">Chưa có bản ghi gửi đối tác nào.</p>
        <?php else: ?>
        <?php
        // Phân trang
        $perPage = 10;
        $pagPage = max(1, intval($_GET['trang'] ?? 1));
        $pagTotal = count($dsDoiTac);
        $pagTotalPages = max(1, ceil($pagTotal / $perPage));
        $pagPage = min($pagPage, $pagTotalPages);
        $offset = ($pagPage - 1) * $perPage;
        $pagedItems = array_slice($dsDoiTac, $offset, $perPage);

        $ttColors = [
            'Đang xử lý' => ['bg' => '#fff3cd', 'color' => '#856404'],
            'Hoàn thành'  => ['bg' => '#d4edda', 'color' => '#155724'],
            'Trả lại'     => ['bg' => '#f8d7da', 'color' => '#721c24'],
        ];
        ?>
        <table class="table" style="margin:0;font-size:14px;">
            <thead>
                <tr>
                    <th class="stt-col">STT</th>
                    <th>Phiếu SC</th>
                    <th>Khách Hàng</th>
                    <th>Đối Tác</th>
                    <th>Ngày Gửi</th>
                    <th>Ngày Nhận Lại</th>
                    <th>Trạng Thái</th>
                    <th>Ghi Chú</th>
                    <th style="width:140px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pagedItems as $idx => $dt):
                $ttStyle = $ttColors[$dt['TrangThai']] ?? ['bg' => '#e9ecef', 'color' => '#495057'];
            ?>
                <tr>
                    <td class="stt-col"><?= $offset + $idx + 1 ?></td>
                    <td><strong>#<?= $dt['MaPhieu'] ?></strong></td>
                    <td>
                        <?= e($dt['TenKhachHang'] ?? 'N/A') ?>
                        <?php if (!empty($dt['SoDienThoai'])): ?>
                            <br><small style="color:#888;"><?= e($dt['SoDienThoai']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= e($dt['TenDoiTac']) ?></td>
                    <td><?= date('d/m/Y', strtotime($dt['NgayGui'])) ?></td>
                    <td><?= $dt['NgayNhanLai'] ? date('d/m/Y', strtotime($dt['NgayNhanLai'])) : '<span style="color:#999">Chưa xác định</span>' ?></td>
                    <td>
                        <span style="background:<?= $ttStyle['bg'] ?>;color:<?= $ttStyle['color'] ?>;padding:3px 10px;border-radius:20px;font-size:12px;">
                            <?= e($dt['TrangThai']) ?>
                        </span>
                    </td>
                    <td style="color:#666;font-size:13px;"><?= e($dt['GhiChu']) ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-sm btn-primary"
                                onclick="moModalCapNhat(<?= htmlspecialchars(json_encode($dt), ENT_QUOTES) ?>)">
                                 Sửa
                            </button>
                            <form method="POST" action="<?= url('admin/xoadoitac/' . $dt['MaGuiDT']) ?>" style="display:inline;" onsubmit="return confirm('Xóa bản ghi đối tác #<?= $dt['MaGuiDT'] ?>?')">
                                <button type="submit" class="btn btn-sm btn-danger"> Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        $pagPerPage = $perPage;
        $pagBaseUrl = url('admin/doitac');
        $pagParams = [];
        include ROOT_PATH . '/app/views/partials/pagination.php';
        ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Cập Nhật Đối Tác -->
<div id="modalCapNhatDT" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:30px;width:580px;max-width:95vw;max-height:90vh;overflow-y:auto;">
        <h3 style="margin-top:0;">✏️ Cập Nhật Gửi Đối Tác</h3>
        <form method="POST" action="<?= url('admin/capnhatdoitac') ?>">
            <input type="hidden" name="MaGuiDT" id="cn_MaGuiDT">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label>Tên Đối Tác</label>
                    <input type="text" name="TenDoiTac" id="cn_TenDoiTac" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Ngày Gửi</label>
                    <input type="datetime-local" name="NgayGui" id="cn_NgayGui" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Ngày Nhận Lại</label>
                    <input type="datetime-local" name="NgayNhanLai" id="cn_NgayNhanLai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Trạng Thái</label>
                    <select name="TrangThai" id="cn_TrangThai" class="form-control">
                        <option value="Đang xử lý"> Đang xử lý</option>
                        <option value="Hoàn thành"> Hoàn thành</option>
                        <option value="Trả lại">   Trả lại</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label>Ghi Chú</label>
                    <textarea name="GhiChu" id="cn_GhiChu" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Lưu Cập Nhật</button>
                <button type="button" class="btn btn-secondary" onclick="dongModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm Đối Tác vào danh mục -->
<div id="modalThemDT" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1001;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:25px;width:420px;max-width:95vw;">
        <h3 style="margin-top:0;">➕ Thêm Đối Tác</h3>
        <div class="form-group">
            <label>Tên Đối Tác *</label>
            <input type="text" id="new_dt_ten" class="form-control" placeholder="VD: Kỹ thuật Minh Khoa...">
        </div>
        <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" id="new_dt_diachi" class="form-control" placeholder="Địa chỉ...">
        </div>
        <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" id="new_dt_sdt" class="form-control" placeholder="SĐT liên hệ...">
        </div>
        <div id="new_dt_msg" style="color:#28a745;font-size:13px;margin-bottom:6px;display:none;"></div>
        <div style="display:flex;gap:10px;">
            <button type="button" class="btn btn-primary" onclick="luuDanhMucDT()">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalThemDT').style.display='none'">Hủy</button>
        </div>
        <?php if (!empty($dsDanhMucDT)): ?>
        <hr style="margin:15px 0 10px;">
        <p style="font-size:12px;color:#888;margin-bottom:8px;">Danh mục đã lưu:</p>
        <div id="listDT">
        <?php foreach ($dsDanhMucDT as $i => $dt): ?>
        <div id="dm_row_dt_<?= $i ?>" style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f0f0f0;">
            <span style="font-size:13px;"><?= e($dt['Ten']) ?> <?= !empty($dt['SoDienThoai']) ? '<small style="color:#888;">- ' . e($dt['SoDienThoai']) . '</small>' : '' ?></span>
            <div style="display:flex;gap:4px;">
                <button type="button" onclick="suaDanhMucDT(<?= $i ?>, <?= htmlspecialchars(json_encode($dt, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>)" style="background:none;border:none;color:#e6a817;cursor:pointer;font-size:13px;" title="Sửa">✏️</button>
                <button type="button" onclick="xoaDanhMucDT(<?= $i ?>, this)" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:14px;" title="Xóa">×</button>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Sửa Đối Tác -->
<div id="modalSuaDT_dm" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1002;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;padding:25px;width:420px;max-width:95vw;">
        <h3 style="margin-top:0;">✏️ Sửa Đối Tác</h3>
        <input type="hidden" id="sua_dt_idx">
        <div class="form-group">
            <label>Tên Đối Tác *</label>
            <input type="text" id="sua_dt_ten" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Địa Chỉ</label>
            <input type="text" id="sua_dt_diachi" class="form-control">
        </div>
        <div class="form-group">
            <label>Số Điện Thoại</label>
            <input type="text" id="sua_dt_sdt" class="form-control">
        </div>
        <div id="sua_dt_msg" style="color:#28a745;font-size:13px;margin-bottom:6px;display:none;"></div>
        <div style="display:flex;gap:10px;">
            <button type="button" class="btn btn-primary" onclick="capNhatDanhMucDT()">Cập nhật</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalSuaDT_dm').style.display='none'">Hủy</button>
        </div>
    </div>
</div>

<script>
var DT_AJAX_URL_THEM = '<?= url('admin/themdanhmuc') ?>';
var DT_AJAX_URL_XOA  = '<?= url('admin/xoadanhmuc') ?>';

function dmAjaxDT(url, body, cb) {
    body.append('ajax', '1');
    fetch(url, {method:'POST', body:body})
        .then(r => r.json()).then(cb)
        .catch(() => alert('Lỗi kết nối!'));
}

function addOptionToSelectDT(ten, diachi, sdt, idx) {
    var sel = document.getElementById('selectDoiTac');
    if (!sel) {
        var wrap = document.getElementById('inputTenDoiTac').previousElementSibling;
        sel = document.createElement('select');
        sel.id = 'selectDoiTac'; sel.className = 'form-control'; sel.style.flex = '1';
        sel.setAttribute('onchange','chonDoiTac(this)');
        var ph = document.createElement('option'); ph.value=''; ph.textContent='-- Chọn từ danh mục --';
        sel.appendChild(ph); wrap.insertBefore(sel, wrap.firstChild);
    }
    var opt = document.createElement('option');
    opt.value = idx; opt.setAttribute('data-ten', ten);
    opt.setAttribute('data-diachi', diachi||''); opt.setAttribute('data-sdt', sdt||'');
    opt.textContent = ten; sel.appendChild(opt);
}

function luuDanhMucDT() {
    var ten = document.getElementById('new_dt_ten').value.trim();
    if (!ten) { alert('Vui lòng nhập tên!'); return; }
    var diachi = document.getElementById('new_dt_diachi').value.trim();
    var sdt = document.getElementById('new_dt_sdt').value.trim();
    var body = new FormData();
    body.append('loai','DoiTac'); body.append('ten', ten);
    body.append('diachi', diachi); body.append('sdt', sdt);
    dmAjaxDT(DT_AJAX_URL_THEM, body, function(r) {
        if (!r.ok) { alert(r.msg || 'Lỗi!'); return; }
        addOptionToSelectDT(ten, diachi, sdt, r.idx);
        var list = document.getElementById('listDT');
        if (!list) { var m=document.getElementById('modalThemDT').querySelector('div'); var hr=document.createElement('hr'); hr.style.margin='15px 0 10px'; m.appendChild(hr); var p=document.createElement('p'); p.style.cssText='font-size:12px;color:#888;margin-bottom:8px'; p.textContent='Danh mục đã lưu:'; m.appendChild(p); list=document.createElement('div'); list.id='listDT'; m.appendChild(list); }
        var row = document.createElement('div');
        row.id = 'dm_row_dt_'+r.idx;
        row.style.cssText = 'display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f0f0f0;';
        row.innerHTML = '<span style="font-size:13px;">'+ten+(sdt?'<small style="color:#888;"> - '+sdt+'</small>':'')+'</span>'
            +'<div style="display:flex;gap:4px;">'
            +'<button type="button" onclick="suaDanhMucDT('+r.idx+','+JSON.stringify({Ten:ten,DiaChi:diachi,SoDienThoai:sdt})+')" style="background:none;border:none;color:#e6a817;cursor:pointer;font-size:13px;" title="Sửa">✏️</button>'
            +'<button type="button" onclick="xoaDanhMucDT('+r.idx+',this)" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:14px;" title="Xóa">×</button>'
            +'</div>';
        list.appendChild(row);
        document.getElementById('new_dt_ten').value='';
        document.getElementById('new_dt_diachi').value='';
        document.getElementById('new_dt_sdt').value='';
        var msg = document.getElementById('new_dt_msg');
        msg.textContent = '✅ Đã thêm "'+ten+'"!'; msg.style.display='block';
        setTimeout(()=>{ msg.style.display='none'; }, 2500);
    });
}

function xoaDanhMucDT(idx, btn) {
    if (!confirm('Xóa khỏi danh mục?')) return;
    var body = new FormData();
    body.append('loai','DoiTac'); body.append('idx', idx);
    dmAjaxDT(DT_AJAX_URL_XOA, body, function(r) {
        if (!r.ok) { alert('Lỗi xóa!'); return; }
        var row = document.getElementById('dm_row_dt_'+idx);
        if (row) row.remove();
        var sel = document.getElementById('selectDoiTac');
        if (sel) { for (var i=0;i<sel.options.length;i++) { if (sel.options[i].value==idx) { sel.remove(i); break; } } }
    });
}

function toggleForm(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
function chonDoiTac(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    var dcInput = document.querySelector('#formTaoDT input[name="DiaChi"]');
    var sdtInput = document.querySelector('#formTaoDT input[name="SoDienThoai"]');
    if (dcInput) dcInput.value = opt.getAttribute('data-diachi') || '';
    if (sdtInput) sdtInput.value = opt.getAttribute('data-sdt') || '';
}
function moModalCapNhat(data) {
    document.getElementById('cn_MaGuiDT').value = data.MaGuiDT;
    document.getElementById('cn_TenDoiTac').value = data.TenDoiTac;
    document.getElementById('cn_NgayGui').value = data.NgayGui ? data.NgayGui.substring(0,16) : '';
    document.getElementById('cn_NgayNhanLai').value = data.NgayNhanLai ? data.NgayNhanLai.substring(0,16) : '';
    document.getElementById('cn_GhiChu').value = data.GhiChu || '';
    const sel = document.getElementById('cn_TrangThai');
    for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === data.TrangThai) { sel.selectedIndex = i; break; }
    }
    document.getElementById('modalCapNhatDT').style.display = 'flex';
}
function dongModal() {
    document.getElementById('modalCapNhatDT').style.display = 'none';
}
function suaDanhMucDT(idx, data) {
    document.getElementById('sua_dt_idx').value = idx;
    document.getElementById('sua_dt_ten').value = data.Ten || '';
    document.getElementById('sua_dt_diachi').value = data.DiaChi || '';
    document.getElementById('sua_dt_sdt').value = data.SoDienThoai || '';
    document.getElementById('modalSuaDT_dm').style.display = 'flex';
}
document.getElementById('modalCapNhatDT').addEventListener('click', function(e) {
    if (e.target === this) dongModal();
});
document.getElementById('modalThemDT').addEventListener('click', function(e) {
    if (e.target === this) document.getElementById('modalThemDT').style.display = 'none';
});
document.getElementById('modalSuaDT_dm').addEventListener('click', function(e) {
    if (e.target === this) document.getElementById('modalSuaDT_dm').style.display = 'none';
});
</script>
