<?php
/**
 * NhanvienController - Dành cho Nhân viên tiếp nhận
 */

class NhanvienController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
        if (!$this->hasRole(['nhanvien', 'Nhân viên tiếp nhận'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Dashboard nhân viên
     */
    public function index()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        
        $choTra = $phieuModel->getByTrangThai('Hoàn thành');
        
        $thongKe = [
            'phieu_hom_nay' => count($phieuModel->getByDate(date('Y-m-d'))),
            'cho_tra' => count($choTra),
            'tong_phieu_thang' => count($phieuModel->all()),
        ];

        $this->render('nhanvien/index', [
            'title' => 'Dashboard Nhân Viên',
            'thongKe' => $thongKe,
            'choTra' => $choTra
        ]);
    }

    /**
     * Form tiếp nhận mới (tạo biên nhận)
     */
    public function tiepnhan()
    {
        $khachHangModel = $this->model('KhachHang');
        $nhanVienModel = $this->model('NhanVien');

        // Lấy danh sách tài khoản khách hàng
        $allAccounts = $nhanVienModel->getAllAccounts();
        $dsKhachTK = [];
        foreach ($allAccounts as $username => $acc) {
            if (($acc['LoaiTK'] ?? '') === 'khachhang') {
                $dsKhachTK[$username] = $acc;
            }
        }

        $this->render('nhanvien/tiepnhan', [
            'title' => 'Tiếp Nhận Thiết Bị Mới',
            'khachhang' => $khachHangModel->all(),
            'dsKhachTK' => $dsKhachTK
        ]);
    }

    /**
     * Trả thiết bị
     */
    public function traphieu()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->getByTrangThai('Hoàn thành');

        $this->render('nhanvien/traphieu', [
            'title' => 'Trả Thiết Bị',
            'phieu' => $phieu
        ]);
    }

    /**
     * Danh sách tất cả phiếu
     */
    public function danhsach()
    {
        $phieuModel = $this->model('PhieuSuaChua');

        // Lấy bộ lọc từ GET params
        $filters = [
            'tu_ngay'    => trim($_GET['tu_ngay'] ?? ''),
            'den_ngay'   => trim($_GET['den_ngay'] ?? ''),
            'trang_thai' => trim($_GET['trang_thai'] ?? ''),
            'q'          => trim($_GET['q'] ?? ''),
        ];

        // Kiểm tra có filter nào được dùng không
        $hasFilter = !empty($filters['tu_ngay']) || !empty($filters['den_ngay']) 
                  || !empty($filters['trang_thai']) || !empty($filters['q']);

        if ($hasFilter) {
            $phieu = $phieuModel->filter($filters);
        } else {
            $phieu = $phieuModel->all();
        }

        $this->render('nhanvien/danhsach', [
            'title' => 'Danh Sách Phiếu',
            'phieu' => $phieu
        ]);
    }

    /**
     * Lưu phiếu tiếp nhận
     */
    public function luuphieu()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nhanvien/tiepnhan');
        }

        $phieuModel  = $this->model('PhieuSuaChua');
        $khachModel  = $this->model('KhachHang');

        // Xác định MaKhachHang
        $maKhachHang = intval($_POST['MaKhachHang'] ?? 0);

        if ($maKhachHang <= 0) {
            // Khách mới → tạo bản ghi khachhang trước
            $tenKH  = trim($_POST['TenKhachHang'] ?? '');
            $sdtKH  = trim($_POST['SoDienThoai'] ?? '');
            $diaChiKH = trim($_POST['DiaChi'] ?? '');

            if (empty($tenKH) || empty($sdtKH)) {
                flash('error', 'Vui lòng nhập tên và số điện thoại khách hàng!');
                $this->redirect('nhanvien/tiepnhan');
                return;
            }

            // Kiểm tra SĐT đã tồn tại chưa
            $existing = $khachModel->findByPhone($sdtKH);
            if ($existing) {
                $maKhachHang = $existing['MaKhachHang'];
            } else {
                $newId = $khachModel->create([
                    'TenKhachHang' => $tenKH,
                    'SoDienThoai'  => $sdtKH,
                    'DiaChi'       => $diaChiKH,
                    'GhiChu'       => ''
                ]);
                if (!$newId) {
                    flash('error', 'Không thể tạo khách hàng mới!');
                    $this->redirect('nhanvien/tiepnhan');
                    return;
                }
                $maKhachHang = $newId;
            }
        }

        // Tạo bản ghi sanpham mới cho thiết bị vừa tiếp nhận
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

        $data = [
            'MaKhachHang'    => $maKhachHang,
            'MaSanPham'      => $maSP ?: 0,
            'MaNhanVien'     => $_SESSION['user']['MaNhanVien'] ?? 0,
            'MaKTV'          => 0,
            'MaNhanVienTra'  => 0,
            'NgayNhan'       => date('Y-m-d H:i:s'),
            'NgayTra'        => $_POST['NgayTraDuKien'] ?? null,
            'LoaiDichVu'     => trim($_POST['LoaiDichVu'] ?? 'Tại Cao Hùng'),
            'TinhTrang'      => 'Chờ xử lý',
            'GhiChuTinhTrang' => trim($_POST['GhiChuTinhTrang'] ?? ''),
            'PhuKienKemTheo' => trim($_POST['PhuKienKemTheo'] ?? ''),
            'TongTien'       => 0,
            'TaiKhoanKH'     => trim($_POST['TaiKhoanKH'] ?? '')
        ];

        $maPhieu = $phieuModel->create($data);
        if ($maPhieu) {
            // Lưu tên đăng nhập NV tiếp nhận
            $phieuModel->setNVNhan($maPhieu, $_SESSION['user']['TenDangNhap'] ?? '');
            
            // In phiếu nếu người dùng bấm "Tạo & In"
            if (isset($_POST['print'])) {
                $this->redirect('nhanvien/xemphieu/' . $maPhieu . '?print=1');
            }
            flash('success', 'Tiếp nhận thành công! Phiếu #' . $maPhieu);
            $this->redirect('nhanvien/danhsach');
        } else {
            flash('error', 'Có lỗi khi tạo phiếu, vui lòng kiểm tra lại!');
            $this->redirect('nhanvien/tiepnhan');
        }
    }

    /**
     * Xác nhận trả thiết bị
     */
    public function xacnhantra()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nhanvien/traphieu');
        }

        $maPhieu = $_POST['MaPhieu'] ?? null;
        if (!$maPhieu) {
            $this->redirect('nhanvien/traphieu');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        
        $phieuModel->updateTrangThai($maPhieu, 'Đã trả');
        // Lưu tên đăng nhập NV trả + ghi ngày trả thực tế
        $phieuModel->setNVTra($maPhieu, $_SESSION['user']['TenDangNhap'] ?? '');
        $phieuModel->setNgayTra($maPhieu);

        flash('success', 'Đã trả thiết bị - Phiếu #' . $maPhieu);
        $this->redirect('nhanvien/traphieu');
    }

    /**
     * Xem chi tiết phiếu
     */
    public function xemphieu($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('nhanvien/danhsach');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $chiTietModel = $this->model('ChiTietSuaChua');

        $phieu = $phieuModel->find($maPhieu);
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

        $this->render('nhanvien/xemphieu', [
            'title' => 'Chi Tiết Phiếu #' . $maPhieu,
            'phieu' => $phieu,
            'chiTiet' => $chiTiet,
            'tenNVNhan' => $tenNVNhan,
            'tenKTV'    => $tenKTV,
            'tenNVTra'  => $tenNVTra,
            'binhLuan'   => $binhLuan
        ]);
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
            'LoaiTaiKhoan' => $user['LoaiTK'] ?? 'nhanvien',
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
        $binhLuanModel = $this->model('BinhLuan');
        $result = $binhLuanModel->xoaBinhLuan($maBinhLuan, $user['TenDangNhap'] ?? '', false);

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
        $binhLuanModel = $this->model('BinhLuan');
        $result = $binhLuanModel->suaBinhLuan($maBinhLuan, $noiDung, $user['TenDangNhap'] ?? '', false);

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Đã cập nhật bình luận' : 'Không thể sửa bình luận này'
        ]);
        exit;
    }
}
