<?php
/**
 * KhachController - Dành cho Khách hàng
 */

class KhachController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
        if (!$this->hasRole(['khachhang', 'Khách hàng'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Trang chủ khách hàng
     */
    public function index()
    {
        $user = $_SESSION['user'] ?? [];
        $taiKhoan = $user['TenDangNhap'] ?? '';

        $phieuModel = $this->model('PhieuSuaChua');
        $donHang = $taiKhoan ? $phieuModel->getByTaiKhoanKH($taiKhoan) : [];

        $this->render('khach/index', [
            'title' => 'Chào Mừng Khách Hàng',
            'donHang' => $donHang
        ]);
    }

    /**
     * Đơn hàng của tôi
     */
    public function donhang()
    {
        $user = $_SESSION['user'] ?? [];
        $taiKhoan = $user['TenDangNhap'] ?? '';

        $phieuModel = $this->model('PhieuSuaChua');
        $bhModel = $this->model('GuiBaoHanh');
        $dtModel = $this->model('GuiDoiTac');
        $tatCaDonHang = $taiKhoan ? $phieuModel->getByTaiKhoanKH($taiKhoan) : [];
        $maPhieuBH = $bhModel->getMaPhieuDaGui();
        $maPhieuDT = $dtModel->getMaPhieuDaGui();
        $donHang = $tatCaDonHang;

        // Lọc theo trạng thái nếu có
        $trangThai = $_GET['trang_thai'] ?? '';
        if ($trangThai) {
            $donHang = array_filter($tatCaDonHang, function($p) use ($trangThai) {
                $tt = $p['TinhTrang'] ?? '';
                // Map filter: "Chờ xử lý" cũng bao gồm "Đã phân công" và "Đang kiểm tra"
                if ($trangThai === 'Chờ xử lý') {
                    return in_array($tt, ['Chờ xử lý', 'Đã phân công', 'Đang kiểm tra']);
                }
                return $tt === $trangThai;
            });
        }

        $this->render('khach/donhang', [
            'title' => 'Đơn Hàng Của Tôi',
            'donHang' => $donHang,
            'tongDonHang' => count($tatCaDonHang),
            'maPhieuBH' => $maPhieuBH,
            'maPhieuDT' => $maPhieuDT
        ]);
    }

    /**
     * Tra cứu đơn hàng
     */
    public function tracuu()
    {
        $ketQua = [];
        $q = $_GET['q'] ?? '';

        if ($q) {
            $user = $_SESSION['user'] ?? [];
            $taiKhoan = $user['TenDangNhap'] ?? '';
            $phieuModel = $this->model('PhieuSuaChua');
            $tatCa = $phieuModel->search($q);
            // Chỉ hiện đơn của chính khách hàng đang đăng nhập
            $ketQua = array_filter($tatCa, function($p) use ($taiKhoan) {
                return ($p['TaiKhoanKH'] ?? '') === $taiKhoan;
            });
        }

        $this->render('khach/tracuu', [
            'title' => 'Tra Cứu Đơn Hàng',
            'ketQua' => $ketQua
        ]);
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function xemdon($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('khach/donhang');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $chiTietModel = $this->model('ChiTietSuaChua');
        $bhModel = $this->model('GuiBaoHanh');
        $dtModel = $this->model('GuiDoiTac');

        $phieu = $phieuModel->find($maPhieu);

        // Kiểm tra phiếu thuộc về khách hàng đang đăng nhập
        $taiKhoan = $_SESSION['user']['TenDangNhap'] ?? '';
        if (!$phieu || ($phieu['TaiKhoanKH'] ?? '') !== $taiKhoan) {
            flash('error', 'Không tìm thấy đơn hàng!');
            $this->redirect('khach/donhang');
            return;
        }

        $chiTiet = $chiTietModel->getByPhieu($maPhieu);
        $coBH = !empty($bhModel->getByPhieu($maPhieu));
        $coDT = !empty($dtModel->getByPhieu($maPhieu));

        $this->render('khach/xemdon', [
            'title' => 'Chi Tiết Đơn Hàng #' . $maPhieu,
            'phieu' => $phieu,
            'chiTiet' => $chiTiet,
            'coBH' => $coBH,
            'coDT' => $coDT
        ]);
    }

    /**
     * Form tạo phiếu sửa chữa (khách hàng tự gửi yêu cầu)
     */
    public function taophieu()
    {
        $this->render('khach/taophieu', [
            'title' => 'Gửi Yêu Cầu Sửa Chữa'
        ]);
    }

    /**
     * Lưu phiếu sửa chữa từ khách hàng
     */
    public function luuphieu()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('khach/taophieu');
        }

        $user = $_SESSION['user'] ?? [];

        // 1. Tìm hoặc tạo khách hàng
        $khModel = $this->model('KhachHang');
        $tenKH   = trim($_POST['TenKhachHang'] ?? ($user['HoTen'] ?? $user['TenNhanVien'] ?? ''));
        $sdtKH   = trim($_POST['SoDienThoai'] ?? '');
        $diaChiKH = trim($_POST['DiaChi'] ?? '');

        // Tìm khách hàng theo SĐT
        $kh = $khModel->findByPhone($sdtKH);
        if (!$kh) {
            // Tạo mới
            $maKH = $khModel->create([
                'TenKhachHang' => $tenKH,
                'SoDienThoai'  => $sdtKH,
                'DiaChi'       => $diaChiKH,
                'GhiChu'       => 'Khách tự tạo phiếu online'
            ]);
        } else {
            $maKH = $kh['MaKhachHang'];
        }

        // 2. Tạo sản phẩm
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
        $maPhieu = $phieuModel->create([
            'MaKhachHang'    => $maKH,
            'MaSanPham'      => $maSP ?: 0,
            'MaNhanVien'     => 0,
            'MaKTV'          => 0,
            'MaNhanVienTra'  => 0,
            'NgayNhan'       => date('Y-m-d H:i:s'),
            'NgayTra'        => null,
            'LoaiDichVu'     => trim($_POST['LoaiDichVu'] ?? 'Tại Cao Hùng'),
            'TinhTrang'      => 'Chờ xử lý',
            'GhiChuTinhTrang' => trim($_POST['GhiChuTinhTrang'] ?? ''),
            'PhuKienKemTheo' => trim($_POST['PhuKienKemTheo'] ?? ''),
            'TongTien'       => 0,
            'TaiKhoanKH'     => $user['TenDangNhap'] ?? ''
        ]);

        if ($maPhieu) {
            flash('success', 'Đã gửi yêu cầu sửa chữa thành công! Mã phiếu: #' . $maPhieu . '. Nhân viên sẽ liên hệ bạn sớm.');
            $this->redirect('khach/donhang');
        } else {
            flash('error', 'Có lỗi xảy ra, vui lòng thử lại!');
            $this->redirect('khach/taophieu');
        }
    }
}
