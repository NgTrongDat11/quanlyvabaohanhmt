<?php
/**
 * PhieuSuaChua Controller
 */

class PhieuSuaChuaController extends Controller
{
    private $phieuModel;
    private $chiTietModel;

    public function __construct()
    {
        $this->requireLogin();
        // Chỉ admin và nhân viên mới xem được
        if (!$this->hasRole(['admin', 'Quản lý', 'nhanvien', 'Nhân viên tiếp nhận'])) {
            $this->redirect('auth/login');
        }
        $this->phieuModel = $this->model('PhieuSuaChua');
        $this->chiTietModel = $this->model('ChiTietSuaChua');
    }

    /**
     * Danh sách phiếu sửa chữa
     */
    public function index()
    {
        $data = [
            'title' => 'Danh sách phiếu sửa chữa',
            'phieus' => $this->phieuModel->all()
        ];

        $this->render('phieusuachua/index', $data);
    }

    /**
     * Xem chi tiết phiếu
     */
    public function show($maPhieu)
    {
        $phieu = $this->phieuModel->find($maPhieu);
        
        if (!$phieu) {
            flash('error', 'Không tìm thấy phiếu sửa chữa!');
            $this->redirect('phieusuachua');
            return;
        }

        // Lấy chi tiết sửa chữa
        $chiTiet = $this->chiTietModel->getByPhieu($maPhieu);

        $data = [
            'title' => 'Chi tiết phiếu sửa chữa #' . $maPhieu,
            'phieu' => $phieu,
            'chiTiet' => $chiTiet
        ];

        $this->render('phieusuachua/show', $data);
    }

    /**
     * Thống kê
     */
    public function thongke()
    {
        $data = [
            'title' => 'Thống kê phiếu sửa chữa',
            'thongKe' => $this->phieuModel->thongKeTrangThai()
        ];

        $this->render('phieusuachua/thongke', $data);
    }
}
