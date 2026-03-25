<?php
/**
 * KhachHang Controller
 */

class KhachHangController extends Controller
{
    private $khachHangModel;

    public function __construct()
    {
        $this->requireLogin();
        // Chỉ admin và nhân viên mới xem được danh sách khách hàng
        if (!$this->hasRole(['admin', 'Quản lý', 'nhanvien', 'Nhân viên tiếp nhận'])) {
            $this->redirect('auth/login');
        }
        $this->khachHangModel = $this->model('KhachHang');
    }

    /**
     * Danh sách khách hàng
     */
    public function index()
    {
        $data = [
            'title' => 'Danh sách khách hàng',
            'khachHangs' => $this->khachHangModel->all()
        ];

        $this->render('khachhang/index', $data);
    }

    /**
     * Xem chi tiết khách hàng
     */
    public function show($maKhachHang)
    {
        $khachHang = $this->khachHangModel->find($maKhachHang);
        
        if (!$khachHang) {
            flash('error', 'Không tìm thấy khách hàng!');
            $this->redirect('khachhang');
            return;
        }

        // Lấy lịch sử sửa chữa
        $phieuModel = $this->model('PhieuSuaChua');
        $lichSu = $phieuModel->getByKhachHang($maKhachHang);

        $data = [
            'title' => 'Chi tiết khách hàng',
            'khachHang' => $khachHang,
            'lichSu' => $lichSu
        ];

        $this->render('khachhang/show', $data);
    }

    /**
     * Tìm kiếm khách hàng
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        
        $data = [
            'title' => 'Tìm kiếm khách hàng',
            'keyword' => $keyword,
            'khachHangs' => $this->khachHangModel->search($keyword)
        ];

        $this->render('khachhang/index', $data);
    }
}
