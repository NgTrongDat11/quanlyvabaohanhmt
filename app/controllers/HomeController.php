<?php
/**
 * Home Controller - Trang chủ public & Dashboard redirect
 */

class HomeController extends Controller
{
    /**
     * Trang chủ công khai (landing page)
     */
    public function index()
    {
        // Hiển thị landing page cho tất cả (kể cả đã đăng nhập)
        $this->render('home/index', [
            'title' => 'Cao Hùng Tech - Dịch Vụ Sửa Chữa Máy Tính Uy Tín'
        ]);
    }

}
