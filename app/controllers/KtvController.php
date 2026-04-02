<?php
/**
 * KtvController - Dành cho Kỹ thuật viên
 */

class KtvController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
        if (!$this->hasRole(['ktv', 'Kỹ thuật viên'])) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Dashboard KTV - Danh sách công việc
     */
    public function index()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        $daPhanCong   = $phieuModel->getByKTVAndTrangThai($myUsername, 'Đã phân công');
        $dangKiemTra  = $phieuModel->getByKTVAndTrangThai($myUsername, 'Đang kiểm tra');
        $dangSua      = $phieuModel->getByKTVAndTrangThai($myUsername, 'Tiếp nhận');
        $hoanThanh    = $phieuModel->getByKTVAndTrangThai($myUsername, 'Hoàn thành');

        $this->render('ktv/index', [
            'title'        => 'Công Việc Của Tôi',
            'daPhanCong'   => $daPhanCong,
            'dangKiemTra'  => $dangKiemTra,
            'dangSua'      => $dangSua,
            'hoanThanh'    => $hoanThanh
        ]);
    }

    /**
     * Danh sách đang xử lý
     */
    public function danglam()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        $phieu = $phieuModel->getByKTVAndTrangThaiMulti($myUsername, ['Đang kiểm tra', 'Tiếp nhận']);

        $this->render('ktv/danglam', [
            'title' => 'Đang Xử Lý',
            'phieu' => $phieu
        ]);
    }

    /**
     * Danh sách hoàn thành
     */
    public function hoanthanh()
    {
        $phieuModel = $this->model('PhieuSuaChua');
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        $phieu = $phieuModel->getByKTVAndTrangThai($myUsername, 'Hoàn thành');

        $this->render('ktv/hoanthanh', [
            'title' => 'Đã Hoàn Thành',
            'phieu' => $phieu
        ]);
    }

    /**
     * Xem chi tiết phiếu
     */
    public function xemphieu($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $chiTietModel = $this->model('ChiTietSuaChua');

        $phieu = $phieuModel->find($maPhieu);

        // Kiểm tra phiếu thuộc về KTV này
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Không tìm thấy phiếu hoặc phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }

        $chiTiet = $chiTietModel->getByPhieu($maPhieu);
        
        // Lấy bình luận
        $binhLuanModel = $this->model('BinhLuan');
        $binhLuan = $binhLuanModel->getByPhieu($maPhieu);

        $this->render('ktv/xemphieu', [
            'title' => 'Chi Tiết Phiếu #' . $maPhieu,
            'phieu' => $phieu,
            'chiTiet' => $chiTiet,
            'binhLuan' => $binhLuan
        ]);
    }

    /**
     * Bắt đầu xử lý phiếu
     */
    public function batdau($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        if (($phieu['TinhTrang'] ?? '') !== 'Đã phân công') {
            flash('error', 'Không thể thực hiện thao tác này với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $phieuModel->updateTrangThai($maPhieu, 'Đang kiểm tra');
        
        flash('success', 'Đã bắt đầu kiểm tra phiếu #' . $maPhieu);
        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Gửi báo giá - chuyển thẳng sang Tiếp nhận
     */
    public function baogiahttp($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        if (!in_array($phieu['TinhTrang'] ?? '', ['Đang kiểm tra'])) {
            flash('error', 'Không thể thực hiện thao tác này với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $phieuModel->updateTrangThai($maPhieu, 'Tiếp nhận');

        flash('success', 'Bắt đầu sửa chữa phiếu #' . $maPhieu);
        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Bắt đầu sửa chữa (sau báo giá)
     */
    public function batsua($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        if (!in_array($phieu['TinhTrang'] ?? '', ['Đang kiểm tra'])) {
            flash('error', 'Không thể thực hiện thao tác này với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $phieuModel->updateTrangThai($maPhieu, 'Tiếp nhận');

        flash('success', 'Bắt đầu sửa chữa phiếu #' . $maPhieu);
        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Hoàn thành phiếu
     */
    public function hoantat($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        if (($phieu['TinhTrang'] ?? '') !== 'Tiếp nhận') {
            flash('error', 'Không thể hoàn thành phiếu với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        // Không cho hoàn thành nếu chưa có chi phí sửa chữa
        if (floatval($phieu['TongTien'] ?? 0) <= 0) {
            flash('error', 'Chưa có chi phí sửa chữa! Vui lòng thêm hạng mục trước khi hoàn thành.');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $phieuModel->updateTrangThai($maPhieu, 'Hoàn thành');
        
        flash('success', 'Đã hoàn thành phiếu #' . $maPhieu);
        $this->redirect('ktv');
    }

    /**
     * Quay lại trạng thái Tiếp nhận (từ Hoàn thành)
     */
    public function quaylai($maPhieu = null)
    {
        if (!$maPhieu) {
            $this->redirect('ktv');
        }

        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';

        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        if (($phieu['TinhTrang'] ?? '') !== 'Hoàn thành') {
            flash('error', 'Chỉ có thể quay lại tiếp nhận từ trạng thái Hoàn thành!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $phieuModel->updateTrangThai($maPhieu, 'Tiếp nhận');

        flash('success', 'Phiếu #' . $maPhieu . ' đã quay lại trạng thái Tiếp nhận để sửa chữa tiếp.');
        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Xóa hạng mục sửa chữa
     */
    public function xoachitiet($maChiTiet = null)
    {
        if (!$maChiTiet) {
            $this->redirect('ktv');
        }

        $chiTietModel = $this->model('ChiTietSuaChua');
        $chiTiet = $chiTietModel->find($maChiTiet);
        if (!$chiTiet) {
            flash('error', 'Không tìm thấy hạng mục!');
            $this->redirect('ktv');
            return;
        }

        $maPhieu = $chiTiet['MaPhieu'];

        // Kiểm tra quyền sở hữu phiếu
        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }

        // Chỉ cho xóa khi đang kiểm tra, Tiếp nhận hoặc Hoàn thành
        if (!in_array($phieu['TinhTrang'] ?? '', ['Đang kiểm tra', 'Tiếp nhận', 'Hoàn thành'])) {
            flash('error', 'Không thể xóa hạng mục với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        if ($chiTietModel->delete($maChiTiet)) {
            $phieuModel->updateTongTien($maPhieu);
            flash('success', 'Đã xóa hạng mục!');
        } else {
            flash('error', 'Xóa thất bại!');
        }

        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Sửa hạng mục sửa chữa
     */
    public function suachitiet($maChiTiet = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('ktv');
            return;
        }
        if (!$maChiTiet) {
            $this->redirect('ktv');
        }

        $chiTietModel = $this->model('ChiTietSuaChua');
        $chiTiet = $chiTietModel->find($maChiTiet);
        if (!$chiTiet) {
            flash('error', 'Không tìm thấy hạng mục!');
            $this->redirect('ktv');
            return;
        }

        $maPhieu = $chiTiet['MaPhieu'];

        // Kiểm tra quyền sở hữu phiếu
        $phieuModel = $this->model('PhieuSuaChua');
        $phieu = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        if (!$phieu || ($phieu['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }

        // Chỉ cho sửa khi đang kiểm tra, Tiếp nhận hoặc Hoàn thành
        if (!in_array($phieu['TinhTrang'] ?? '', ['Đang kiểm tra', 'Tiếp nhận', 'Hoàn thành'])) {
            flash('error', 'Không thể sửa hạng mục với trạng thái hiện tại!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $donGia = floatval(str_replace([',', '.'], '', $_POST['DonGia'] ?? 0));
        $soLuong = intval($_POST['SoLuong'] ?? 1);
        $thanhTien = $donGia * $soLuong;

        $chiTietModel->update($maChiTiet, [
            'HangMuc'   => trim($_POST['HangMuc'] ?? ''),
            'SoLuong'   => $soLuong,
            'DonGia'    => $donGia,
            'ThanhTien' => $thanhTien
        ]);

        // Cập nhật tổng tiền phiếu
        $phieuModel->updateTongTien($maPhieu);

        flash('success', 'Cập nhật hạng mục thành công!');
        $this->redirect('ktv/xemphieu/' . $maPhieu);
    }

    /**
     * Thêm chi tiết sửa chữa
     */
    public function themchitiet($maPhieu = null)
    {
        if (!$maPhieu || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('ktv');
        }

        // Kiểm tra quyền sở hữu phiếu
        $phieuModel = $this->model('PhieuSuaChua');
        $phieuCheck = $phieuModel->find($maPhieu);
        $myUsername = $_SESSION['user']['TenDangNhap'] ?? '';
        if (!$phieuCheck || ($phieuCheck['TenDangNhapKTV'] ?? '') !== $myUsername) {
            flash('error', 'Phiếu không thuộc về bạn!');
            $this->redirect('ktv');
            return;
        }
        // Không cho sửa phiếu đã trả
        if (($phieuCheck['TinhTrang'] ?? '') === 'Đã trả') {
            flash('error', 'Phiếu đã trả, không thể chỉnh sửa!');
            $this->redirect('ktv/xemphieu/' . $maPhieu);
            return;
        }

        $chiTietModel = $this->model('ChiTietSuaChua');
        
        $donGia = floatval(str_replace([',', '.'], '', $_POST['DonGia'] ?? 0));
        $soLuong = intval($_POST['SoLuong'] ?? 1);

        $data = [
            'MaPhieu' => $maPhieu,
            'HangMuc' => $_POST['HangMuc'] ?? '',
            'SoLuong' => $soLuong,
            'DonGia' => $donGia,
            'ThanhTien' => $soLuong * $donGia
        ];

        if ($chiTietModel->create($data)) {
            // Cập nhật tổng tiền
            $phieuModel = $this->model('PhieuSuaChua');
            $phieuModel->updateTongTien($maPhieu);
            
            flash('success', 'Đã thêm hạng mục!');
        }

        $this->redirect('ktv/xemphieu/' . $maPhieu);
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
            'LoaiTaiKhoan' => $user['LoaiTK'] ?? 'ktv',
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
