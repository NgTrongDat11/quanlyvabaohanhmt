<?php
/**
 * AdminController - Quản lý cho Admin
 */

class AdminController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
        // Chỉ Admin mới được truy cập
        if (!$this->hasRole(['admin', 'Quản lý'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Dashboard Admin
     */
    public function index()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $khachHangModel = $this->model('KhachHang');
        $sanPhamModel = $this->model('SanPham');
        $nhanVienModel = $this->model('NhanVien');

        // Thống kê
        $allAccounts = $nhanVienModel->getAllAccounts();
        $tongKH = 0;
        $tongTK = count($allAccounts);
        foreach ($allAccounts as $acc) {
            if (($acc['LoaiTK'] ?? '') === 'khachhang') $tongKH++;
        }

        $stats = [
            'tongPhieu'    => count($phieuModel->all()),
            'choXuLy'      => count($phieuModel->getByTrangThai('Chờ xử lý')),
            'dangSua'      => count($phieuModel->getByTrangThai('Tiếp nhận')),
            'hoanthanh'    => count($phieuModel->getByTrangThai('Hoàn thành')),
            'tongKhach'    => $tongKH,
            'tongTaiKhoan' => $tongTK,
            'tongSanPham'  => count($sanPhamModel->allDangTiepNhan()),
        ];

        // Phiếu mới nhất
        $phieuMoi = $phieuModel->all();
        $phieuMoi = array_slice($phieuMoi, 0, 10);

        $this->render('admin/index', [
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'phieuMoi' => $phieuMoi
        ]);
    }

    /**
     * Quản lý phiếu sửa chữa
     */
    public function phieusuachua()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $bhModel = $this->model('GuiBaoHanh');
        $dtModel = $this->model('GuiDoiTac');
        $phieu = $phieuModel->all();

        // Index BH/DT theo MaPhieu để tra nhanh
        $bhAll = $bhModel->all();
        $dtAll = $dtModel->all();
        $bhMap = [];
        foreach ($bhAll as $bh) {
            $bhMap[$bh['MaPhieu']][] = $bh;
        }
        $dtMap = [];
        foreach ($dtAll as $dt) {
            $dtMap[$dt['MaPhieu']][] = $dt;
        }

        // Lấy danh sách KTV để phân công
        $nhanVienModel = $this->model('NhanVien');
        $allAccounts = $nhanVienModel->getAllAccounts();
        $dsKTV = [];
        foreach ($allAccounts as $username => $acc) {
            if (($acc['LoaiTK'] ?? '') === 'ktv') {
                $dsKTV[$username] = $acc;
            }
        }

        $this->render('admin/phieusuachua', [
            'title' => 'Quản Lý Phiếu Sửa Chữa',
            'phieu' => $phieu,
            'dsKTV' => $dsKTV,
            'bhMap' => $bhMap,
            'dtMap' => $dtMap
        ]);
    }

    /**
     * Phân công KTV cho phiếu
     */
    public function phancongktv()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/phieusuachua');
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        $tenDangNhapKTV = trim($_POST['TenDangNhapKTV'] ?? '');

        if (!$maPhieu || !$tenDangNhapKTV) {
            flash('error', 'Thiếu thông tin phân công!');
            $this->redirect('admin/phieusuachua');
            return;
        }

        $phieuModel = $this->model('PhieuSuaChua');
        if ($phieuModel->phancongKTV($maPhieu, $tenDangNhapKTV)) {
            // Nếu chưa có người nhận TB, tự ghi admin hiện tại
            $phieu = $phieuModel->find($maPhieu);
            if (empty($phieu['TenDangNhapNVNhan'])) {
                $phieuModel->setNVNhan($maPhieu, $_SESSION['user']['TenDangNhap'] ?? 'admin');
            }
            flash('success', 'Đã phân công KTV cho phiếu #' . $maPhieu . '!');
        } else {
            flash('error', 'Có lỗi khi phân công!');
        }

        $this->redirect('admin/phieusuachua');
    }

    /**
     * Form tạo phiếu mới (biên nhận)
     */
    public function taophieu()
    {
        $nhanVienModel = $this->model('NhanVien');
        $allAccounts = $nhanVienModel->getAllAccounts();

        // Lọc tài khoản theo loại
        $dsNhanVien = [];
        $dsKTV = [];
        $dsKhachHang = [];
        foreach ($allAccounts as $username => $acc) {
            $loai = $acc['LoaiTK'] ?? '';
            if ($loai === 'nhanvien') {
                $dsNhanVien[$username] = $acc;
            } elseif ($loai === 'ktv') {
                $dsKTV[$username] = $acc;
            } elseif ($loai === 'khachhang') {
                $dsKhachHang[$username] = $acc;
            }
        }

        $this->render('admin/taophieu', [
            'title' => 'Tạo Biên Nhận Mới',
            'dsNhanVien' => $dsNhanVien,
            'dsKTV' => $dsKTV,
            'dsKhachHang' => $dsKhachHang
        ]);
    }

    /**
     * Xóa phiếu sửa chữa
     */
    public function xoaphieu($maPhieu = null)
    {
        if (!$maPhieu || $_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('admin/phieusuachua'); return; }

        // Xóa chi tiết sửa chữa trước
        $chiTietModel = $this->model('ChiTietSuaChua');
        $chiTietModel->deleteByPhieu($maPhieu);

        // Xóa phiếu
        $phieuModel = $this->model('PhieuSuaChua');
        $phieuModel->delete($maPhieu);

        flash('success', 'Đã xóa phiếu #' . $maPhieu . ' thành công!');
        $this->redirect('admin/phieusuachua');
    }

    /**
     * Xem chi tiết phiếu (in biên nhận)
     */
    public function xemphieu($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('admin/phieusuachua');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $chiTietModel = $this->model('ChiTietSuaChua');

        $phieu = $phieuModel->find($maPhieu);
        if (!$phieu) {
            flash('error', 'Không tìm thấy phiếu!');
            $this->redirect('admin/phieusuachua');
        }

        $chiTiet = $chiTietModel->getByPhieu($maPhieu);
        
        // Lấy bình luận
        $binhLuanModel = $this->model('BinhLuan');
        $binhLuan = $binhLuanModel->getByPhieu($maPhieu);

        // Tra cứu tên hiển thị từ tài khoản
        $nhanVienModel = $this->model('NhanVien');
        $allAccounts = $nhanVienModel->getAllAccounts();
        
        $getDisplayName = function($username) use ($allAccounts) {
            if (empty($username)) return '';
            $acc = $allAccounts[$username] ?? null;
            return $acc ? ($acc['HoTen'] ?? $acc['TenNhanVien'] ?? $username) : $username;
        };
        
        $tenNVNhan = $getDisplayName($phieu['TenDangNhapNVNhan'] ?? '');
        $tenKTV    = $getDisplayName($phieu['TenDangNhapKTV'] ?? '');
        $tenNVTra  = $getDisplayName($phieu['TenDangNhapNVTra'] ?? '');

        $bhModel = $this->model('GuiBaoHanh');
        $dtModel = $this->model('GuiDoiTac');

        $this->render('admin/xemphieu', [
            'title'      => 'Biên Nhận #' . $maPhieu,
            'phieu'      => $phieu,
            'chiTiet'    => $chiTiet,
            'tenNVNhan'  => $tenNVNhan,
            'tenKTV'     => $tenKTV,
            'tenNVTra'   => $tenNVTra,
            'dsBaoHanh'  => $bhModel->getByPhieu($maPhieu),
            'dsDoiTac'   => $dtModel->getByPhieu($maPhieu),
            'binhLuan'   => $binhLuan
        ]);
    }

    /**
     * Quản lý khách hàng
     */
    public function khachhang()
    {
        $khachHangModel = $this->model('KhachHang');
        $khachHangs = $khachHangModel->all();

        $this->render('admin/khachhang', [
            'title' => 'Quản Lý Khách Hàng',
            'khachHangs' => $khachHangs
        ]);
    }

    /**
     * Quản lý sản phẩm
     */
    public function sanpham()
    {
        $sanPhamModel = $this->model('SanPham');
        $sanPhams = $sanPhamModel->allDangTiepNhan();

        $this->render('admin/sanpham', [
            'title' => 'Quản Lý Sản Phẩm',
            'sanPhams' => $sanPhams
        ]);
    }

    /**
     * Quản lý nhân viên
     */
    public function nhanvien()
    {
        $nhanVienModel = $this->model('NhanVien');
        $nhanViens = $nhanVienModel->all();

        $this->render('admin/nhanvien', [
            'title' => 'Quản Lý Nhân Viên',
            'nhanViens' => $nhanViens
        ]);
    }

    /**
     * Báo cáo thống kê
     */
    public function baocao()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $khachHangModel = $this->model('KhachHang');
        $sanPhamModel = $this->model('SanPham');

        $allPhieu = $phieuModel->all();

        // Tháng/năm hiện tại & tháng được chọn
        $thangChon = intval($_GET['thang'] ?? date('n'));
        $namChon = intval($_GET['nam'] ?? date('Y'));

        // Lọc phiếu theo tháng được chọn
        $phieuThang = array_filter($allPhieu, function($p) use ($thangChon, $namChon) {
            $m = intval(date('n', strtotime($p['NgayNhan'])));
            $y = intval(date('Y', strtotime($p['NgayNhan'])));
            return $m === $thangChon && $y === $namChon;
        });

        // Doanh thu tháng (chỉ tính phiếu Đã trả)
        $doanhThuThang = 0;
        $phieuDaTra = 0;
        $phieuHoanThanh = 0;
        foreach ($phieuThang as $p) {
            if (($p['TinhTrang'] ?? '') === 'Đã trả') {
                $doanhThuThang += floatval($p['TongTien'] ?? 0);
                $phieuDaTra++;
            }
            if (in_array($p['TinhTrang'] ?? '', ['Hoàn thành', 'Đã trả'])) {
                $phieuHoanThanh++;
            }
        }
        $tongPhieuThang = count($phieuThang);
        $tyLeHoanThanh = $tongPhieuThang > 0 ? round($phieuHoanThanh / $tongPhieuThang * 100) : 0;

        // Thống kê theo trạng thái cho tháng chọn
        $theoTrangThai = [];
        foreach ($phieuThang as $p) {
            $tt = $p['TinhTrang'] ?? 'Chờ xử lý';
            $theoTrangThai[$tt] = ($theoTrangThai[$tt] ?? 0) + 1;
        }

        // Thống kê 6 tháng gần nhất (doanh thu + số phiếu)
        $thongKe6Thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = intval(date('n', strtotime("-{$i} months")));
            $y = intval(date('Y', strtotime("-{$i} months")));
            $dt = 0;
            $sp = 0;
            foreach ($allPhieu as $p) {
                $pm = intval(date('n', strtotime($p['NgayNhan'])));
                $py = intval(date('Y', strtotime($p['NgayNhan'])));
                if ($pm === $m && $py === $y) {
                    $sp++;
                    if (($p['TinhTrang'] ?? '') === 'Đã trả') {
                        $dt += floatval($p['TongTien'] ?? 0);
                    }
                }
            }
            $thongKe6Thang[] = [
                'thang' => "T{$m}/{$y}",
                'doanhThu' => $dt,
                'soPhieu' => $sp
            ];
        }

        // Top KTV (tháng chọn)
        $ktvStats = [];
        foreach ($phieuThang as $p) {
            $ktv = $p['TenKTV'] ?? '';
            if ($ktv) {
                if (!isset($ktvStats[$ktv])) $ktvStats[$ktv] = ['soPhieu' => 0, 'doanhThu' => 0];
                $ktvStats[$ktv]['soPhieu']++;
                if (in_array($p['TinhTrang'] ?? '', ['Hoàn thành', 'Đã trả'])) {
                    $ktvStats[$ktv]['doanhThu'] += floatval($p['TongTien'] ?? 0);
                }
            }
        }
        arsort($ktvStats);

        $this->render('admin/baocao', [
            'title' => 'Báo Cáo Thống Kê',
            'thangChon' => $thangChon,
            'namChon' => $namChon,
            'tongPhieuThang' => $tongPhieuThang,
            'doanhThuThang' => $doanhThuThang,
            'phieuDaTra' => $phieuDaTra,
            'tyLeHoanThanh' => $tyLeHoanThanh,
            'theoTrangThai' => $theoTrangThai,
            'thongKe6Thang' => $thongKe6Thang,
            'ktvStats' => $ktvStats,
            'phieuThang' => array_values($phieuThang),
        ]);
    }

    /**
     * Lưu phiếu mới
     */
    public function luuphieu()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/taophieu');
        }

        // 1. Tạo khách hàng mới từ form
        $khachModel = $this->model('KhachHang');
        $sdtKH = trim($_POST['SoDienThoai'] ?? '');
        $tenKH = trim($_POST['TenKhachHang'] ?? '');
        $diaChiKH = trim($_POST['DiaChi'] ?? '');

        $kh = $sdtKH ? $khachModel->findByPhone($sdtKH) : null;
        if ($kh) {
            $maKH = $kh['MaKhachHang'];
        } else {
            $maKH = $khachModel->create([
                'TenKhachHang' => $tenKH,
                'SoDienThoai'  => $sdtKH,
                'DiaChi'       => $diaChiKH,
                'GhiChu'       => ''
            ]);
        }

        // 2. Tạo sản phẩm mới từ form
        // Upload hình ảnh thiết bị
        $hinhAnh = '';
        if (!empty($_FILES['HinhAnh']['name']) && $_FILES['HinhAnh']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['HinhAnh']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $fileName = 'sp_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
                $dest = ROOT_PATH . '/public/uploads/sanpham/' . $fileName;
                if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $dest)) {
                    $hinhAnh = 'uploads/sanpham/' . $fileName;
                }
            }
        }

        $spModel = $this->model('SanPham');
        $ghiChu = trim($_POST['GhiChuTinhTrang'] ?? '');
        $maSP = $spModel->create([
            'TenSanPham'  => trim($_POST['TenSanPham'] ?? ''),
            'LoaiSanPham' => trim($_POST['LoaiSanPham'] ?? 'Khác'),
            'HangSanXuat' => trim($_POST['HangSanXuat'] ?? ''),
            'ThuongHieu'  => trim($_POST['ThuongHieu'] ?? ''),
            'MaSerial'    => trim($_POST['MaSerial'] ?? ''),
            'HinhAnh'     => $hinhAnh,
            'GhiChu'      => $ghiChu
        ]);

        // 3. Tạo phiếu sửa chữa
        $phieuModel = $this->model('PhieuSuaChua');
        $data = [
            'MaKhachHang'    => $maKH ?: 0,
            'MaSanPham'      => $maSP ?: 0,
            'MaNhanVien'     => 0,
            'MaKTV'          => 0,
            'MaNhanVienTra'  => 0,
            'NgayNhan'       => $_POST['NgayNhan'] ?? date('Y-m-d H:i:s'),
            'NgayTra'        => $_POST['NgayTra'] ?? null,
            'LoaiDichVu'     => $_POST['LoaiDichVu'] ?? '',
            'TinhTrang'      => 'Chờ xử lý',
            'GhiChuTinhTrang' => trim($_POST['GhiChuTinhTrang'] ?? ''),
            'PhuKienKemTheo' => $_POST['PhuKienKemTheo'] ?? '',
            'TongTien'       => 0
        ];

        // Lưu thông tin NV/KTV vào ghi chú phiếu (lấy từ tài khoản)
        $nvLabel   = trim($_POST['NhanVienTiepNhan'] ?? '');
        $ktvLabel  = trim($_POST['KTVXuLy'] ?? '');
        $nvTraLabel = trim($_POST['NhanVienTra'] ?? '');
        $taiKhoanKH = trim($_POST['TaiKhoanKH'] ?? '');

        // Gán TaiKhoanKH vào data
        $data['TaiKhoanKH'] = $taiKhoanKH;

        if ($maPhieu = $phieuModel->create($data)) {
            // Lưu tên đăng nhập NV tiếp nhận (admin hoặc NV được chọn từ form)
            $nvUsername = trim($_POST['NhanVienTiepNhan'] ?? ($_SESSION['user']['TenDangNhap'] ?? ''));
            if ($nvUsername) {
                $phieuModel->setNVNhan($maPhieu, $nvUsername);
            }

            // Lưu KTV xử lý nếu admin chọn trong form
            if ($ktvLabel) {
                $phieuModel->phancongKTV($maPhieu, $ktvLabel);
            }

            // Lưu NV trả nếu admin chọn trong form
            if ($nvTraLabel) {
                $phieuModel->setNVTra($maPhieu, $nvTraLabel);
            }
            
            // Lưu chi tiết sửa chữa (hạng mục)
            $chiTietList = $_POST['chiTiet'] ?? [];
            if (!empty($chiTietList)) {
                $chiTietModel = $this->model('ChiTietSuaChua');
                $tongTien = 0;
                foreach ($chiTietList as $ct) {
                    $hangMuc = trim($ct['HangMuc'] ?? '');
                    if ($hangMuc === '') continue;
                    $soLuong = intval($ct['SoLuong'] ?? 1);
                    $donGia  = floatval(str_replace([',', '.'], '', $ct['DonGia'] ?? 0));
                    $thanhTien = $soLuong * $donGia;
                    $tongTien += $thanhTien;

                    $chiTietModel->create([
                        'MaPhieu'   => $maPhieu,
                        'HangMuc'   => $hangMuc,
                        'SoLuong'   => $soLuong,
                        'DonGia'    => $donGia,
                        'ThanhTien' => $thanhTien
                    ]);
                }
                // Cập nhật tổng tiền
                if ($tongTien > 0) {
                    $phieuModel->updateTongTien($maPhieu);
                }
            }

            $msg = 'Tạo phiếu thành công!';
            if ($nvLabel)   $msg .= ' NV nhận: ' . $nvLabel;
            if ($ktvLabel)  $msg .= ' | KTV: ' . $ktvLabel;
            if ($nvTraLabel) $msg .= ' | NV trả: ' . $nvTraLabel;
            flash('success', $msg);
            $this->redirect('admin/phieusuachua');
        } else {
            flash('error', 'Có lỗi xảy ra!');
            $this->redirect('admin/taophieu');
        }
    }

    /**
     * Cập nhật trạng thái phiếu
     */
    public function capnhattrangthai($maPhieu = null)
    {
        if (!$maPhieu || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/phieusuachua');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $trangThaiMoi = $_POST['TrangThai'] ?? '';

        // Kiểm tra luồng: chỉ cho phép đi tới, không quay lại
        $luong = ['Chờ xử lý', 'Đã phân công', 'Đang kiểm tra', 'Tiếp nhận', 'Hoàn thành', 'Đã trả'];
        $phieu = $phieuModel->find($maPhieu);
        $viTriHienTai = array_search($phieu['TinhTrang'] ?? '', $luong);
        $viTriMoi = array_search($trangThaiMoi, $luong);

        if ($viTriMoi === false || $viTriHienTai === false || $viTriMoi <= $viTriHienTai) {
            flash('error', 'Không thể chuyển ngược trạng thái!');
            $this->redirect('admin/xemphieu/' . $maPhieu);
            return;
        }

        if ($phieuModel->updateTrangThai($maPhieu, $trangThaiMoi)) {
            // Khi chuyển 'Đã trả', tự ghi người trả TB + ngày trả
            if ($trangThaiMoi === 'Đã trả') {
                $phieuModel->setNVTra($maPhieu, $_SESSION['user']['TenDangNhap'] ?? 'admin');
                $phieuModel->setNgayTra($maPhieu);
            }
            // Nếu chưa có người nhận TB, tự ghi admin
            $phieuCheck = $phieuModel->find($maPhieu);
            if (empty($phieuCheck['TenDangNhapNVNhan'])) {
                $phieuModel->setNVNhan($maPhieu, $_SESSION['user']['TenDangNhap'] ?? 'admin');
            }
            flash('success', 'Cập nhật trạng thái thành công!');
        } else {
            flash('error', 'Có lỗi xảy ra!');
        }

        $this->redirect('admin/xemphieu/' . $maPhieu);
    }

    /**
     * Lưu chi tiết sửa chữa (hạng mục thay thế)
     */
    public function luuchitiet()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/phieusuachua');
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        if (!$maPhieu) {
            $this->redirect('admin/phieusuachua');
        }

        // Không cho sửa phiếu đã trả
        $phieuModel = $this->model('PhieuSuaChua');
        $phieuCheck = $phieuModel->find($maPhieu);
        if (($phieuCheck['TinhTrang'] ?? '') === 'Đã trả') {
            flash('error', 'Phiếu đã trả, không thể chỉnh sửa!');
            $this->redirect('admin/xemphieu/' . $maPhieu);
            return;
        }

        $model = $this->model('ChiTietSuaChua');
        $donGia = floatval(str_replace([',', '.'], '', $_POST['DonGia'] ?? 0));
        $soLuong = intval($_POST['SoLuong'] ?? 1);
        $thanhTien = $donGia * $soLuong;

        $model->create([
            'MaPhieu'   => $maPhieu,
            'HangMuc'   => trim($_POST['HangMuc'] ?? ''),
            'SoLuong'   => $soLuong,
            'DonGia'    => $donGia,
            'ThanhTien' => $thanhTien
        ]);

        // Cập nhật tổng tiền phiếu
        $this->capNhatTongTien($maPhieu);

        flash('success', 'Thêm hạng mục thành công!');
        $this->redirect('admin/xemphieu/' . $maPhieu);
    }

    /**
     * Xóa chi tiết sửa chữa
     */
    public function xoachitiet($maChiTiet = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/phieusuachua');
            return;
        }
        if (!$maChiTiet) {
            $this->redirect('admin/phieusuachua');
        }

        $model = $this->model('ChiTietSuaChua');
        $ct = $model->find($maChiTiet);
        $maPhieu = $ct['MaPhieu'] ?? 0;

        // Không cho xóa nếu phiếu đã trả
        if ($maPhieu) {
            $phieuModel = $this->model('PhieuSuaChua');
            $phieuCheck = $phieuModel->find($maPhieu);
            if (($phieuCheck['TinhTrang'] ?? '') === 'Đã trả') {
                flash('error', 'Phiếu đã trả, không thể chỉnh sửa!');
                $this->redirect('admin/xemphieu/' . $maPhieu);
                return;
            }
        }

        $model->delete($maChiTiet);

        if ($maPhieu) {
            $this->capNhatTongTien($maPhieu);
        }

        flash('success', 'Đã xóa hạng mục!');
        $this->redirect($maPhieu ? 'admin/xemphieu/' . $maPhieu : 'admin/phieusuachua');
    }

    /**
     * Cập nhật hạng mục sửa chữa
     */
    public function suachitiet($maChiTiet = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/phieusuachua');
            return;
        }
        if (!$maChiTiet) {
            $this->redirect('admin/phieusuachua');
        }

        $model = $this->model('ChiTietSuaChua');
        $ct = $model->find($maChiTiet);
        $maPhieu = $ct['MaPhieu'] ?? 0;

        // Không cho sửa nếu phiếu đã trả
        if ($maPhieu) {
            $phieuModel = $this->model('PhieuSuaChua');
            $phieuCheck = $phieuModel->find($maPhieu);
            if (($phieuCheck['TinhTrang'] ?? '') === 'Đã trả') {
                flash('error', 'Phiếu đã trả, không thể chỉnh sửa!');
                $this->redirect('admin/xemphieu/' . $maPhieu);
                return;
            }
        }

        $donGia = floatval(str_replace([',', '.'], '', $_POST['DonGia'] ?? 0));
        $soLuong = intval($_POST['SoLuong'] ?? 1);
        $thanhTien = $donGia * $soLuong;

        $model->update($maChiTiet, [
            'HangMuc'   => trim($_POST['HangMuc'] ?? ''),
            'SoLuong'   => $soLuong,
            'DonGia'    => $donGia,
            'ThanhTien' => $thanhTien
        ]);

        // Cập nhật tổng tiền phiếu
        if ($maPhieu) {
            $this->capNhatTongTien($maPhieu);
        }

        flash('success', 'Cập nhật hạng mục thành công!');
        $this->redirect('admin/xemphieu/' . $maPhieu);
    }

    /**
     * Cập nhật tổng tiền phiếu từ chi tiết
     */
    private function capNhatTongTien($maPhieu)
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $phieuModel->updateTongTien($maPhieu);
    }

    /**
     * Quản lý tài khoản
     */
    public function taikhoan()
    {
        $nhanVienModel = $this->model('NhanVien');
        $dsTaiKhoan = $nhanVienModel->getAllAccounts();

        $this->render('admin/taikhoan', [
            'title' => 'Quản Lý Tài Khoản',
            'dsTaiKhoan' => $dsTaiKhoan
        ]);
    }

    /**
     * Lưu tài khoản mới
     */
    public function luutaikhoan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/taikhoan');
        }

        $username = trim($_POST['username'] ?? '');
        $hoTen    = trim($_POST['HoTen'] ?? '');
        $matKhau  = trim($_POST['MatKhau'] ?? '');
        $loaiTK   = trim($_POST['LoaiTK'] ?? 'nhanvien');

        if (empty($username) || empty($hoTen) || empty($matKhau)) {
            flash('error', 'Vui lòng nhập đầy đủ thông tin!');
            $this->redirect('admin/taikhoan');
            return;
        }

        $loaiLabels = [
            'admin'      => 'Quản lý',
            'ktv'        => 'Kỹ thuật viên',
            'nhanvien'   => 'Nhân viên tiếp nhận',
            'khachhang'  => 'Khách hàng'
        ];

        $nhanVienModel = $this->model('NhanVien');
        $ok = $nhanVienModel->createAccount($username, [
            'HoTen'   => $hoTen,
            'ChucVu'  => $loaiLabels[$loaiTK] ?? $loaiTK,
            'MatKhau' => $matKhau,
            'LoaiTK'  => $loaiTK
        ]);

        if ($ok) {
            // Nếu là tài khoản khách hàng, tạo thêm bản ghi trong bảng khachhang
            if ($loaiTK === 'khachhang') {
                try {
                    $khachModel = $this->model('KhachHang');
                    $khachModel->create([
                        'TenKhachHang' => $hoTen,
                        'SoDienThoai'  => '',
                        'DiaChi'       => '',
                        'GhiChu'       => 'Tài khoản: ' . $username
                    ]);
                } catch (Exception $e) {
                    // Không bắt buộc, bỏ qua nếu lỗi
                }
            }
            flash('success', 'Tạo tài khoản "' . $username . '" thành công!');
        } else {
            flash('error', 'Tên đăng nhập đã tồn tại!');
        }

        $this->redirect('admin/taikhoan');
    }

    /**
     * Xóa tài khoản
     */
    public function xoataikhoan($username = null)
    {
        if (!$username || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/taikhoan');
            return;
        }

        $nhanVienModel = $this->model('NhanVien');
        if ($nhanVienModel->deleteAccount($username)) {
            flash('success', 'Đã xóa tài khoản "' . $username . '"!');
        } else {
            flash('error', 'Không thể xóa tài khoản này (chỉ xóa được tài khoản tự tạo)!');
        }

        $this->redirect('admin/taikhoan');
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function capnhattaikhoan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/taikhoan');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $hoTen    = trim($_POST['HoTen'] ?? '');
        $matKhau  = trim($_POST['MatKhau'] ?? '');
        $loaiTK   = trim($_POST['LoaiTK'] ?? 'nhanvien');

        if (!$username || !$hoTen) {
            flash('error', 'Vui lòng điền đầy đủ thông tin!');
            $this->redirect('admin/taikhoan');
            return;
        }

        $nhanVienModel = $this->model('NhanVien');
        
        // Cập nhật thông tin tài khoản
        $result = $nhanVienModel->updateAccount($username, [
            'HoTen' => $hoTen,
            'LoaiTK' => $loaiTK,
            'MatKhau' => $matKhau // Chỉ update nếu có điền
        ]);

        if ($result) {
            flash('success', 'Đã cập nhật tài khoản "' . $username . '"!');
        } else {
            flash('error', 'Không thể cập nhật tài khoản này!');
        }

        $this->redirect('admin/taikhoan');
    }

    // ==========================================
    // GỬI BẢO HÀNH
    // ==========================================

    /**
     * Danh sách gửi bảo hành
     */
    public function baohanh()
    {
        $model = $this->model('GuiBaoHanh');
        $phieuModel = $this->model('PhieuSuaChua');
        $dtModel = $this->model('GuiDoiTac');
        $daGuiBH = $model->getMaPhieuDaGui();
        $daGuiDT = $dtModel->getMaPhieuDaGui();
        $daGui = array_unique(array_merge($daGuiBH, $daGuiDT));
        $dsPhieu = array_filter($phieuModel->all(), fn($p) =>
            in_array($p['TinhTrang'], ['Chờ xử lý', 'Tiếp nhận']) && !in_array($p['MaPhieu'], $daGui)
        );
        $dm = $this->getDanhMuc();
        $this->render('admin/baohanh', [
            'title'      => 'Gửi Bảo Hành',
            'dsBaoHanh'  => $model->all(),
            'dsPhieu'    => $dsPhieu,
            'dsTrungTamBH' => $dm['TrungTamBH'] ?? []
        ]);
    }

    /**
     * Lưu gửi bảo hành mới
     */
    public function luubaohanh()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/baohanh');
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        if (!$phieu || $phieu['TinhTrang'] === 'Đã trả') {
            $back = trim($_POST['_redirect'] ?? 'admin/baohanh');
            flash('error', 'Phiếu #' . $maPhieu . ' đã trả cho khách, không thể gửi bảo hành!');
            $this->redirect($back);
            return;
        }

        $model = $this->model('GuiBaoHanh');
        // Chặn phiếu đã gửi BH hoặc ĐT
        $dtModel = $this->model('GuiDoiTac');
        $daGuiBH = $model->getMaPhieuDaGui();
        $daGuiDT = $dtModel->getMaPhieuDaGui();
        if (in_array($maPhieu, $daGuiBH)) {
            flash('error', 'Phiếu #' . $maPhieu . ' đã được gửi bảo hành rồi!');
            $this->redirect('admin/baohanh');
            return;
        }
        if (in_array($maPhieu, $daGuiDT)) {
            flash('error', 'Phiếu #' . $maPhieu . ' đã được gửi đối tác, không thể gửi bảo hành!');
            $this->redirect('admin/baohanh');
            return;
        }
        $ok = $model->create([
            'MaPhieu'       => $maPhieu,
            'TenTrungTamBH' => trim($_POST['TenTrungTamBH'] ?? ''),
            'NgayGui'       => $_POST['NgayGui'] ?? date('Y-m-d H:i:s'),
            'NgayNhanLai'   => $_POST['NgayNhanLai'] ?? date('Y-m-d H:i:s', strtotime('+14 days')),
            'KetQuaBaoHanh' => trim($_POST['KetQuaBaoHanh'] ?? ''),
            'GhiChu'        => trim($_POST['GhiChu'] ?? ''),
            'DiaChi'        => trim($_POST['DiaChi'] ?? ''),
            'SoDienThoai'   => trim($_POST['SoDienThoai'] ?? '')
        ]);

        $back = trim($_POST['_redirect'] ?? 'admin/baohanh');
        if ($ok) {
            flash('success', 'Đã ghi nhận gửi bảo hành thành công!');
        } else {
            flash('error', 'Có lỗi khi lưu, vui lòng thử lại!');
        }
        $this->redirect($back);
    }

    /**
     * Cập nhật kết quả bảo hành
     */
    public function capnhatbaohanh()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/baohanh');
        }

        $id    = intval($_POST['MaBaoHanh'] ?? 0);
        $model = $this->model('GuiBaoHanh');
        $old   = $model->find($id);
        if (!$old) {
            flash('error', 'Không tìm thấy bản ghi!');
            $this->redirect('admin/baohanh');
            return;
        }

        $ok = $model->update($id, [
            'TenTrungTamBH' => trim($_POST['TenTrungTamBH'] ?? $old['TenTrungTamBH']),
            'NgayGui'       => $_POST['NgayGui'] ?? $old['NgayGui'],
            'NgayNhanLai'   => $_POST['NgayNhanLai'] ?? $old['NgayNhanLai'],
            'KetQuaBaoHanh' => trim($_POST['KetQuaBaoHanh'] ?? ''),
            'GhiChu'        => trim($_POST['GhiChu'] ?? ''),
            'DiaChi'        => trim($_POST['DiaChi'] ?? $old['DiaChi'] ?? ''),
            'SoDienThoai'   => trim($_POST['SoDienThoai'] ?? $old['SoDienThoai'] ?? '')
        ]);

        $back = trim($_POST['_redirect'] ?? 'admin/baohanh');
        flash($ok ? 'success' : 'error', $ok ? 'Đã cập nhật bảo hành!' : 'Lỗi cập nhật!');
        $this->redirect($back);
    }

    /**
     * Xóa bản ghi bảo hành
     */
    public function xoabaohanh($id = null)
    {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('admin/baohanh'); return; }
        $model = $this->model('GuiBaoHanh');
        $rec   = $model->find($id);
        $maPhieu = $rec['MaPhieu'] ?? null;
        $model->delete($id);
        flash('success', 'Đã xóa bản ghi bảo hành!');
        $this->redirect($maPhieu ? 'admin/xemphieu/' . $maPhieu : 'admin/baohanh');
    }

    // ==========================================
    // GỬI ĐỐI TÁC
    // ==========================================

    /**
     * Danh sách gửi đối tác
     */
    public function doitac()
    {
        $model     = $this->model('GuiDoiTac');
        $phieuModel = $this->model('PhieuSuaChua');
        $bhModel = $this->model('GuiBaoHanh');
        $daGuiDT = $model->getMaPhieuDaGui();
        $daGuiBH = $bhModel->getMaPhieuDaGui();
        $daGui = array_unique(array_merge($daGuiDT, $daGuiBH));
        $dsPhieu = array_filter($phieuModel->all(), fn($p) =>
            in_array($p['TinhTrang'], ['Chờ xử lý', 'Tiếp nhận']) && !in_array($p['MaPhieu'], $daGui)
        );
        $dm = $this->getDanhMuc();
        $this->render('admin/doitac', [
            'title'    => 'Gửi Đối Tác',
            'dsDoiTac' => $model->all(),
            'dsPhieu'  => $dsPhieu,
            'dsDanhMucDT' => $dm['DoiTac'] ?? []
        ]);
    }

    /**
     * Lưu gửi đối tác mới
     */
    public function luudoitac()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/doitac');
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        if (!$phieu || $phieu['TinhTrang'] === 'Đã trả') {
            $back = trim($_POST['_redirect'] ?? 'admin/doitac');
            flash('error', 'Phiếu #' . $maPhieu . ' đã trả cho khách, không thể gửi đối tác!');
            $this->redirect($back);
            return;
        }

        $model = $this->model('GuiDoiTac');
        // Chặn phiếu đã gửi DT hoặc BH
        $bhModel = $this->model('GuiBaoHanh');
        $daGuiDT = $model->getMaPhieuDaGui();
        $daGuiBH = $bhModel->getMaPhieuDaGui();
        if (in_array($maPhieu, $daGuiDT)) {
            flash('error', 'Phiếu #' . $maPhieu . ' đã được gửi đối tác rồi!');
            $this->redirect('admin/doitac');
            return;
        }
        if (in_array($maPhieu, $daGuiBH)) {
            flash('error', 'Phiếu #' . $maPhieu . ' đã được gửi bảo hành, không thể gửi đối tác!');
            $this->redirect('admin/doitac');
            return;
        }
        $ok = $model->create([
            'MaPhieu'     => $maPhieu,
            'TenDoiTac'   => trim($_POST['TenDoiTac'] ?? ''),
            'NgayGui'     => $_POST['NgayGui'] ?? date('Y-m-d H:i:s'),
            'NgayNhanLai' => $_POST['NgayNhanLai'] ?? date('Y-m-d H:i:s', strtotime('+7 days')),
            'ChiPhi'      => floatval(str_replace([',', '.'], '', $_POST['ChiPhi'] ?? 0)),
            'TrangThai'   => trim($_POST['TrangThai'] ?? 'Đang xử lý'),
            'GhiChu'      => trim($_POST['GhiChu'] ?? ''),
            'DiaChi'      => trim($_POST['DiaChi'] ?? ''),
            'SoDienThoai' => trim($_POST['SoDienThoai'] ?? '')
        ]);

        $back = trim($_POST['_redirect'] ?? 'admin/doitac');
        if ($ok) {
            flash('success', 'Đã ghi nhận gửi đối tác thành công!');
        } else {
            flash('error', 'Có lỗi khi lưu, vui lòng thử lại!');
        }
        $this->redirect($back);
    }

    /**
     * Cập nhật đối tác
     */
    public function capnhatdoitac()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/doitac');
        }

        $id    = intval($_POST['MaGuiDT'] ?? 0);
        $model = $this->model('GuiDoiTac');
        $old   = $model->find($id);
        if (!$old) {
            flash('error', 'Không tìm thấy bản ghi!');
            $this->redirect('admin/doitac');
            return;
        }

        $ok = $model->update($id, [
            'TenDoiTac'   => trim($_POST['TenDoiTac'] ?? $old['TenDoiTac']),
            'NgayGui'     => $_POST['NgayGui'] ?? $old['NgayGui'],
            'NgayNhanLai' => $_POST['NgayNhanLai'] ?? $old['NgayNhanLai'],
            'ChiPhi'      => floatval(str_replace([',', '.'], '', $_POST['ChiPhi'] ?? 0)),
            'TrangThai'   => trim($_POST['TrangThai'] ?? 'Đang xử lý'),
            'GhiChu'      => trim($_POST['GhiChu'] ?? ''),
            'DiaChi'      => trim($_POST['DiaChi'] ?? $old['DiaChi'] ?? ''),
            'SoDienThoai' => trim($_POST['SoDienThoai'] ?? $old['SoDienThoai'] ?? '')
        ]);

        $back = trim($_POST['_redirect'] ?? 'admin/doitac');
        flash($ok ? 'success' : 'error', $ok ? 'Đã cập nhật đối tác!' : 'Lỗi cập nhật!');
        $this->redirect($back);
    }

    /**
     * Xóa bản ghi đối tác
     */
    public function xoadoitac($id = null)
    {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('admin/doitac'); return; }
        $model = $this->model('GuiDoiTac');
        $rec   = $model->find($id);
        $maPhieu = $rec['MaPhieu'] ?? null;
        $model->delete($id);
        flash('success', 'Đã xóa bản ghi đối tác!');
        $this->redirect($maPhieu ? 'admin/xemphieu/' . $maPhieu : 'admin/doitac');
    }

    // ==========================================
    // SẢN PHẨM
    // ==========================================

    /**
     * Lưu sản phẩm mới
     */
    public function luusanpham()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/sanpham');
        }

        // Upload hình ảnh
        $hinhAnh = '';
        if (!empty($_FILES['HinhAnh']['name']) && $_FILES['HinhAnh']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['HinhAnh']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $fileName = 'sp_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
                $dest = ROOT_PATH . '/public/uploads/sanpham/' . $fileName;
                if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $dest)) {
                    $hinhAnh = 'uploads/sanpham/' . $fileName;
                }
            }
        }

        $model = $this->model('SanPham');
        $ok = $model->create([
            'TenSanPham'  => trim($_POST['TenSanPham'] ?? ''),
            'LoaiSanPham' => trim($_POST['LoaiSanPham'] ?? ''),
            'HangSanXuat' => trim($_POST['HangSanXuat'] ?? ''),
            'ThuongHieu'  => trim($_POST['ThuongHieu'] ?? ''),
            'MaSerial'    => trim($_POST['MaSerial'] ?? ''),
            'HinhAnh'     => $hinhAnh,
            'GhiChu'      => trim($_POST['GhiChu'] ?? '')
        ]);

        if ($ok) {
            flash('success', 'Thêm sản phẩm thành công!');
        } else {
            flash('error', 'Có lỗi khi lưu sản phẩm!');
        }
        $this->redirect('admin/sanpham');
    }

    /**
     * Cập nhật sản phẩm
     */
    public function capnhatsanpham()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/sanpham');
        }

        $maSP = trim($_POST['MaSanPham'] ?? '');
        if (!$maSP) {
            flash('error', 'Thiếu mã sản phẩm!');
            $this->redirect('admin/sanpham');
        }

        // Upload hình ảnh mới (nếu có)
        $hinhAnh = trim($_POST['HinhAnhCu'] ?? '');
        if (!empty($_FILES['HinhAnh']['name']) && $_FILES['HinhAnh']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['HinhAnh']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $fileName = 'sp_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
                $dest = ROOT_PATH . '/public/uploads/sanpham/' . $fileName;
                if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $dest)) {
                    $hinhAnh = 'uploads/sanpham/' . $fileName;
                }
            }
        }

        $model = $this->model('SanPham');
        $ok = $model->update($maSP, [
            'TenSanPham'  => trim($_POST['TenSanPham'] ?? ''),
            'LoaiSanPham' => trim($_POST['LoaiSanPham'] ?? ''),
            'HangSanXuat' => trim($_POST['HangSanXuat'] ?? ''),
            'ThuongHieu'  => trim($_POST['ThuongHieu'] ?? ''),
            'MaSerial'    => trim($_POST['MaSerial'] ?? ''),
            'HinhAnh'     => $hinhAnh,
            'GhiChu'      => trim($_POST['GhiChu'] ?? '')
        ]);

        if ($ok) {
            flash('success', 'Cập nhật sản phẩm thành công!');
        } else {
            flash('error', 'Có lỗi khi cập nhật sản phẩm!');
        }
        $this->redirect('admin/sanpham');
    }

    /**
     * Xóa sản phẩm
     */
    public function xoasanpham($id = null)
    {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/sanpham');
            return;
        }
        $model = $this->model('SanPham');
        $model->delete($id);
        flash('success', 'Đã xóa sản phẩm!');
        $this->redirect('admin/sanpham');
    }

    // ==========================================
    // NHÂN VIÊN
    // ==========================================

    /**
     * Lưu nhân viên mới
     */
    public function luunhanvien()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/nhanvien');
        }

        $model = $this->model('NhanVien');
        $ok = $model->create([
            'TenNhanVien' => trim($_POST['TenNhanVien'] ?? ''),
            'ChucVu'      => trim($_POST['ChucVu'] ?? ''),
            'SoDienThoai' => trim($_POST['SoDienThoai'] ?? ''),
            'DiaChi'      => trim($_POST['DiaChi'] ?? ''),
            'TenDangNhap' => null,
            'MatKhau'     => '123456',
            'TrangThai'   => 1
        ]);

        if ($ok) {
            flash('success', 'Thêm nhân viên thành công!');
        } else {
            flash('error', 'Có lỗi khi lưu nhân viên!');
        }
        $this->redirect('admin/nhanvien');
    }

    /**
     * Xóa nhân viên
     */
    public function xoanhanvien($id = null)
    {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('admin/nhanvien'); return; }
        $model = $this->model('NhanVien');
        $model->delete($id);
        flash('success', 'Đã xóa nhân viên!');
        $this->redirect('admin/nhanvien');
    }

    // ===== Danh mục Trung tâm BH / Đối tác (lưu JSON) =====

    private function getDanhMucFile()
    {
        return ROOT_PATH . '/data/danhmuc.json';
    }

    private function getDanhMuc()
    {
        $file = $this->getDanhMucFile();
        if (!file_exists($file)) return ['TrungTamBH' => [], 'DoiTac' => []];
        return json_decode(file_get_contents($file), true) ?: ['TrungTamBH' => [], 'DoiTac' => []];
    }

    private function saveDanhMuc($data)
    {
        file_put_contents($this->getDanhMucFile(), json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public function themdanhmuc()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || ($_POST['ajax'] ?? '') === '1';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) { echo json_encode(['ok'=>false,'msg'=>'Invalid']); exit; }
            $this->redirect('admin'); return;
        }
        $loai = $_POST['loai'] ?? '';
        $ten = trim($_POST['ten'] ?? '');
        $diachi = trim($_POST['diachi'] ?? '');
        $sdt = trim($_POST['sdt'] ?? '');
        $redirect = trim($_POST['_redirect'] ?? 'admin');

        if (!$ten || !in_array($loai, ['TrungTamBH', 'DoiTac'])) {
            if ($isAjax) { echo json_encode(['ok'=>false,'msg'=>'Thiếu thông tin!']); exit; }
            flash('error', 'Thiếu thông tin!');
            $this->redirect($redirect); return;
        }

        $dm = $this->getDanhMuc();
        $dm[$loai][] = ['Ten' => $ten, 'DiaChi' => $diachi, 'SoDienThoai' => $sdt];
        $this->saveDanhMuc($dm);

        if ($isAjax) {
            echo json_encode(['ok'=>true,'msg'=>'Đã thêm "' . $ten . '"!','ten'=>$ten,'diachi'=>$diachi,'sdt'=>$sdt,'idx'=>count($dm[$loai])-1]);
            exit;
        }
        flash('success', 'Đã thêm "' . $ten . '" vào danh mục!');
        $this->redirect($redirect . '?form=1');
    }

    public function xoadanhmuc()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || ($_POST['ajax'] ?? '') === '1';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) { echo json_encode(['ok'=>false]); exit; }
            $this->redirect('admin'); return;
        }
        $loai = $_POST['loai'] ?? '';
        $idx = intval($_POST['idx'] ?? -1);
        $redirect = trim($_POST['_redirect'] ?? 'admin');

        if (!in_array($loai, ['TrungTamBH', 'DoiTac']) || $idx < 0) {
            if ($isAjax) { echo json_encode(['ok'=>false]); exit; }
            $this->redirect($redirect); return;
        }

        $dm = $this->getDanhMuc();
        if (isset($dm[$loai][$idx])) {
            array_splice($dm[$loai], $idx, 1);
            $this->saveDanhMuc($dm);
            if ($isAjax) { echo json_encode(['ok'=>true,'list'=>array_values($dm[$loai])]); exit; }
            flash('success', 'Đã xóa khỏi danh mục!');
        }
        if ($isAjax) { echo json_encode(['ok'=>false]); exit; }
        $this->redirect($redirect);
    }

    public function suadanhmuc()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || ($_POST['ajax'] ?? '') === '1';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) { echo json_encode(['ok'=>false]); exit; }
            $this->redirect('admin'); return;
        }
        $loai = $_POST['loai'] ?? '';
        $idx = intval($_POST['idx'] ?? -1);
        $ten = trim($_POST['ten'] ?? '');
        $diachi = trim($_POST['diachi'] ?? '');
        $sdt = trim($_POST['sdt'] ?? '');
        $redirect = trim($_POST['_redirect'] ?? 'admin');

        if (!$ten || !in_array($loai, ['TrungTamBH', 'DoiTac']) || $idx < 0) {
            if ($isAjax) { echo json_encode(['ok'=>false,'msg'=>'Thiếu thông tin!']); exit; }
            flash('error', 'Thiếu thông tin!');
            $this->redirect($redirect); return;
        }

        $dm = $this->getDanhMuc();
        if (isset($dm[$loai][$idx])) {
            $dm[$loai][$idx] = ['Ten' => $ten, 'DiaChi' => $diachi, 'SoDienThoai' => $sdt];
            $this->saveDanhMuc($dm);
            if ($isAjax) { echo json_encode(['ok'=>true,'msg'=>'Đã cập nhật!','ten'=>$ten,'diachi'=>$diachi,'sdt'=>$sdt]); exit; }
            flash('success', 'Đã cập nhật "' . $ten . '"!');
        }
        if ($isAjax) { echo json_encode(['ok'=>false]); exit; }
        $this->redirect($redirect);
    }

    /**
     * Thêm bình luận vào phiếu
     */
    public function thembinhluan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        $noiDung = trim($_POST['NoiDung'] ?? '');

        if (!$maPhieu || !$noiDung) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $user = $_SESSION['user'] ?? [];
        $binhLuanModel = $this->model('BinhLuan');
        
        $data = [
            'MaPhieu' => $maPhieu,
            'TenDangNhap' => $user['TenDangNhap'] ?? 'unknown',
            'HoTen' => $user['HoTen'] ?? $user['TenNhanVien'] ?? 'Unknown',
            'LoaiTaiKhoan' => $user['LoaiTK'] ?? 'admin',
            'NoiDung' => $noiDung
        ];

        $result = $binhLuanModel->themBinhLuan($data);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm bình luận',
                'data' => [
                    'MaBinhLuan' => $result,
                    'HoTen' => $data['HoTen'],
                    'LoaiTaiKhoan' => $data['LoaiTaiKhoan'],
                    'NoiDung' => $data['NoiDung'],
                    'ThoiGian' => date('Y-m-d H:i:s')
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm bình luận']);
        }
        exit;
    }

    /**
     * Xóa bình luận
     */
    public function xoabinhluan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        $maBinhLuan = intval($_POST['MaBinhLuan'] ?? 0);
        if (!$maBinhLuan) {
            echo json_encode(['success' => false]);
            exit;
        }

        $user = $_SESSION['user'] ?? [];
        $isAdmin = $this->hasRole(['admin', 'Quản lý']);
        
        $binhLuanModel = $this->model('BinhLuan');
        $result = $binhLuanModel->xoaBinhLuan($maBinhLuan, $user['TenDangNhap'] ?? '', $isAdmin);

        echo json_encode(['success' => $result]);
        exit;
    }

    /**
     * Sửa bình luận
     */
    public function suabinhluan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $maBinhLuan = intval($_POST['MaBinhLuan'] ?? 0);
        $noiDung = trim($_POST['NoiDung'] ?? '');

        if (!$maBinhLuan || !$noiDung) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $user = $_SESSION['user'] ?? [];
        $isAdmin = $this->hasRole(['admin', 'Quản lý']);

        $binhLuanModel = $this->model('BinhLuan');
        $result = $binhLuanModel->suaBinhLuan($maBinhLuan, $noiDung, $user['TenDangNhap'] ?? '', $isAdmin);

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Đã cập nhật bình luận' : 'Không thể sửa bình luận này'
        ]);
        exit;
    }

    /**
     * Lấy bình luận mới (cho real-time update)
     */
    public function laybinhluanmoi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'comments' => []]);
            exit;
        }

        $maPhieu = intval($_POST['MaPhieu'] ?? 0);
        $afterTime = $_POST['afterTime'] ?? '';

        if (!$maPhieu || !$afterTime) {
            echo json_encode(['success' => false, 'comments' => []]);
            exit;
        }

        $binhLuanModel = $this->model('BinhLuan');
        $newComments = $binhLuanModel->getNewComments($maPhieu, $afterTime);

        $user = $_SESSION['user'] ?? [];
        $currentUser = $user['TenDangNhap'] ?? '';
        $isAdmin = $this->hasRole(['admin', 'Quản lý']);

        // Thêm thông tin permission cho mỗi comment
        foreach ($newComments as &$comment) {
            $isOwner = ($comment['TenDangNhap'] ?? '') === $currentUser;
            $comment['canManage'] = $isOwner || $isAdmin;
        }

        echo json_encode([
            'success' => true,
            'comments' => $newComments
        ]);
        exit;
    }
}

