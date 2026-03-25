<?php
/**
 * Auth Controller - Xử lý đăng nhập, đăng ký, đăng xuất
 */

class AuthController extends Controller
{
    private $nhanVienModel;

    public function __construct()
    {
        $this->nhanVienModel = $this->model('NhanVien');
    }

    /**
     * Trang đăng nhập
     */
    public function login()
    {
        // Nếu đã đăng nhập, chuyển về dashboard theo loại tài khoản
        if ($this->isLoggedIn()) {
            $this->redirectByRole();
            return;
        }

        // Xử lý POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDangNhap = trim($_POST['username'] ?? '');
            $matKhau = $_POST['password'] ?? '';

            // Validate
            if (empty($tenDangNhap) || empty($matKhau)) {
                $this->render('auth/login', [
                    'title' => 'Đăng nhập',
                    'error' => 'Vui lòng nhập đầy đủ thông tin!'
                ]);
                return;
            }

            // Kiểm tra đăng nhập
            $user = $this->nhanVienModel->login($tenDangNhap, $matKhau);

            if ($user) {
                // Lưu session
                $_SESSION['user'] = $user;
                flash('success', 'Đăng nhập thành công! Chào mừng ' . ($user['TenNhanVien'] ?? $user['HoTen'] ?? ''));
                $this->redirectByRole();
            } else {
                $this->render('auth/login', [
                    'title' => 'Đăng nhập',
                    'error' => 'Tên đăng nhập hoặc mật khẩu không đúng!'
                ]);
                return;
            }
        }

        // Hiển thị form
        $this->render('auth/login', ['title' => 'Đăng nhập']);
    }

    /**
     * Redirect theo loại tài khoản
     */
    private function redirectByRole()
    {
        $loaiTK = $_SESSION['user']['LoaiTK'] ?? '';
        
        switch ($loaiTK) {
            case 'admin':
                $this->redirect('admin');
                break;
            case 'ktv':
                $this->redirect('ktv');
                break;
            case 'nhanvien':
                $this->redirect('nhanvien');
                break;
            case 'khachhang':
                $this->redirect('khach');
                break;
            default:
                $this->redirect('auth/login');
        }
    }

    /**
     * Trang đăng ký khách hàng
     */
    public function dangky()
    {
        if ($this->isLoggedIn()) {
            $this->redirectByRole();
            return;
        }
        $this->render('auth/dangky', ['title' => 'Đăng Ký Tài Khoản']);
    }

    /**
     * Xử lý đăng ký khách hàng
     */
    public function luudangky()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/dangky');
            return;
        }

        $username  = trim($_POST['username'] ?? '');
        $hoTen     = trim($_POST['HoTen'] ?? '');
        $sdt       = trim($_POST['SoDienThoai'] ?? '');
        $diaChi    = trim($_POST['DiaChi'] ?? '');
        $matKhau   = trim($_POST['MatKhau'] ?? '');
        $xacNhan   = trim($_POST['XacNhanMatKhau'] ?? '');

        // Validate
        if (empty($username) || empty($hoTen) || empty($sdt) || empty($matKhau)) {
            $this->render('auth/dangky', [
                'title' => 'Đăng Ký Tài Khoản',
                'error' => 'Vui lòng nhập đầy đủ thông tin bắt buộc!',
                'old'   => $_POST
            ]);
            return;
        }

        if (strlen($matKhau) < 6) {
            $this->render('auth/dangky', [
                'title' => 'Đăng Ký Tài Khoản',
                'error' => 'Mật khẩu phải có ít nhất 6 ký tự!',
                'old'   => $_POST
            ]);
            return;
        }

        if ($matKhau !== $xacNhan) {
            $this->render('auth/dangky', [
                'title' => 'Đăng Ký Tài Khoản',
                'error' => 'Mật khẩu xác nhận không khớp!',
                'old'   => $_POST
            ]);
            return;
        }

        // Tạo tài khoản khách hàng
        $ok = $this->nhanVienModel->createAccount($username, [
            'HoTen'  => $hoTen,
            'ChucVu' => 'Khách hàng',
            'MatKhau'=> $matKhau,
            'LoaiTK' => 'khachhang'
        ]);

        if (!$ok) {
            $this->render('auth/dangky', [
                'title' => 'Đăng Ký Tài Khoản',
                'error' => 'Tên đăng nhập "' . htmlspecialchars($username) . '" đã tồn tại, vui lòng chọn tên khác!',
                'old'   => $_POST
            ]);
            return;
        }

        // Tạo thêm bản ghi trong bảng khachhang DB nếu có SĐT
        try {
            $khachModel = $this->model('KhachHang');
            $khachModel->create([
                'TenKhachHang' => $hoTen,
                'SoDienThoai'  => $sdt,
                'DiaChi'       => $diaChi,
                'GhiChu'       => 'Tài khoản: ' . $username
            ]);
        } catch (Exception $e) {
            // Không bắt buộc, bỏ qua nếu lỗi DB
        }

        $this->render('auth/dangky', [
            'title'   => 'Đăng Ký Tài Khoản',
            'success' => 'Đăng ký thành công! Tài khoản "' . htmlspecialchars($username) . '" đã được tạo. Bạn có thể đăng nhập ngay.'
        ]);
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: ' . url(''));
        exit;
    }

    /**
     * Đổi mật khẩu
     */
    public function changepassword()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matKhauCu = $_POST['matkhaucu'] ?? '';
            $matKhauMoi = $_POST['matkhaumoi'] ?? '';
            $xacNhanMatKhau = $_POST['xacnhanmatkhau'] ?? '';

            // Validate
            if (empty($matKhauCu) || empty($matKhauMoi) || empty($xacNhanMatKhau)) {
                flash('error', 'Vui lòng nhập đầy đủ thông tin!');
                $this->render('auth/changepassword', ['title' => 'Đổi mật khẩu']);
                return;
            }

            if ($matKhauMoi !== $xacNhanMatKhau) {
                flash('error', 'Mật khẩu xác nhận không khớp!');
                $this->render('auth/changepassword', ['title' => 'Đổi mật khẩu']);
                return;
            }

            if (strlen($matKhauMoi) < 6) {
                flash('error', 'Mật khẩu mới phải có ít nhất 6 ký tự!');
                $this->render('auth/changepassword', ['title' => 'Đổi mật khẩu']);
                return;
            }

            // Kiểm tra mật khẩu cũ
            $user = $this->currentUser();
            $username = $user['TenDangNhap'] ?? '';
            $check = $this->nhanVienModel->verifyPassword($username, $matKhauCu);

            if (!$check) {
                flash('error', 'Mật khẩu cũ không đúng!');
                $this->render('auth/changepassword', ['title' => 'Đổi mật khẩu']);
                return;
            }

            // Đổi mật khẩu
            $result = $this->nhanVienModel->changePassword($username, $matKhauMoi);

            if ($result) {
                flash('success', 'Đổi mật khẩu thành công!');
                $this->redirectByRole();
            } else {
                flash('error', 'Có lỗi xảy ra, vui lòng thử lại!');
            }
        }

        $this->render('auth/changepassword', ['title' => 'Đổi mật khẩu']);
    }

    /**
     * Redirect tới Google OAuth
     */
    public function google()
    {
        if (empty(GOOGLE_CLIENT_ID)) {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Chưa cấu hình Google OAuth! Vui lòng liên hệ admin.'
            ]);
            return;
        }

        // Tạo state token chống CSRF
        $state = bin2hex(random_bytes(16));
        $_SESSION['google_oauth_state'] = $state;

        $params = [
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'prompt'        => 'select_account'
        ];

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Google OAuth callback
     */
    public function googleCallback()
    {
        // Kiểm tra lỗi từ Google
        if (!empty($_GET['error'])) {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Đăng nhập Google bị từ chối hoặc lỗi.'
            ]);
            return;
        }

        $code  = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';

        // Kiểm tra CSRF state
        if (empty($code) || empty($state) || $state !== ($_SESSION['google_oauth_state'] ?? '')) {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Yêu cầu không hợp lệ. Vui lòng thử lại.'
            ]);
            return;
        }
        unset($_SESSION['google_oauth_state']);

        // Đổi code lấy access token
        $tokenData = $this->googleExchangeCode($code);
        if (!$tokenData || empty($tokenData['access_token'])) {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Không thể xác thực với Google. Vui lòng thử lại.'
            ]);
            return;
        }

        // Lấy thông tin user từ Google
        $googleUser = $this->googleGetUserInfo($tokenData['access_token']);
        if (!$googleUser || empty($googleUser['email'])) {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Không lấy được thông tin từ Google.'
            ]);
            return;
        }

        // Tìm hoặc tạo tài khoản khách hàng
        $user = $this->nhanVienModel->findOrCreateGoogleAccount($googleUser);

        if ($user) {
            $_SESSION['user'] = $user;
            flash('success', 'Đăng nhập Google thành công! Chào mừng ' . ($user['HoTen'] ?? $user['TenNhanVien'] ?? ''));
            $this->redirectByRole();
        } else {
            $this->render('auth/login', [
                'title' => 'Đăng nhập',
                'error' => 'Không thể tạo tài khoản. Vui lòng thử lại.'
            ]);
        }
    }

    /**
     * Đổi authorization code lấy access token
     */
    private function googleExchangeCode($code)
    {
        $postData = [
            'code'          => $code,
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code'
        ];

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 10
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Lấy thông tin user từ Google API
     */
    private function googleGetUserInfo($accessToken)
    {
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT        => 10
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
