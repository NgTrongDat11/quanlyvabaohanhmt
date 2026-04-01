<?php
/**
 * Model NhanVien
 */

class NhanVien
{
    private $db;
    private $table = 'nhanvien';
    
    // Tài khoản mẫu (hardcode - không cần sửa database)
    private $accounts = [
        'admin' => [
            'MaNhanVien' => 0,
            'TenNhanVien' => 'Admin Cao Hùng',
            'ChucVu' => 'Quản lý',
            'MatKhau' => '123456',
            'LoaiTK' => 'admin'
        ],
        'kythuatvien' => [
            'MaNhanVien' => 0,
            'TenNhanVien' => 'Kỹ Thuật Viên',
            'ChucVu' => 'Kỹ thuật viên',
            'MatKhau' => '123456',
            'LoaiTK' => 'ktv'
        ],
        'nhanvien' => [
            'MaNhanVien' => 0,
            'TenNhanVien' => 'Nhân Viên Tiếp Nhận',
            'ChucVu' => 'Nhân viên tiếp nhận',
            'MatKhau' => '123456',
            'LoaiTK' => 'nhanvien'
        ],
        'khachhang' => [
            'MaNhanVien' => 0,
            'TenNhanVien' => 'Khách Hàng',
            'ChucVu' => 'Khách hàng',
            'MatKhau' => '123456',
            'LoaiTK' => 'khachhang'
        ]
    ];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả tài khoản (hardcode + JSON)
     */
    public function getAllAccounts()
    {
        $all = $this->accounts;

        $jsonFile = ROOT_PATH . '/data/accounts.json';
        if (file_exists($jsonFile)) {
            $json = json_decode(file_get_contents($jsonFile), true) ?? [];
            foreach ($json as $username => $acc) {
                $all[$username] = $acc;
            }
        }
        return $all;
    }

    /**
     * Tạo tài khoản mới (lưu vào JSON)
     */
    public function createAccount($username, $data)
    {
        $jsonFile = ROOT_PATH . '/data/accounts.json';
        $accounts = file_exists($jsonFile)
            ? (json_decode(file_get_contents($jsonFile), true) ?? [])
            : [];

        // Kiểm tra trùng username
        if (isset($this->accounts[$username]) || isset($accounts[$username])) {
            return false;
        }

        $accounts[$username] = [
            'MaNhanVien' => 0,
            'TenNhanVien' => $data['HoTen'],
            'HoTen'       => $data['HoTen'],
            'ChucVu'      => $data['ChucVu'],
            'MatKhau'     => $data['MatKhau'],
            'LoaiTK'      => $data['LoaiTK']
        ];

        return file_put_contents($jsonFile, json_encode($accounts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Xóa tài khoản (chỉ xóa được tài khoản trong JSON)
     */
    public function deleteAccount($username)
    {
        $jsonFile = ROOT_PATH . '/data/accounts.json';
        if (!file_exists($jsonFile)) return false;

        $accounts = json_decode(file_get_contents($jsonFile), true) ?? [];
        if (!isset($accounts[$username])) return false;

        unset($accounts[$username]);
        return file_put_contents($jsonFile, json_encode($accounts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateAccount($username, $data)
    {
        $jsonFile = ROOT_PATH . '/data/accounts.json';
        if (!file_exists($jsonFile)) return false;

        $accounts = json_decode(file_get_contents($jsonFile), true) ?? [];
        if (!isset($accounts[$username])) return false;

        // Cập nhật họ tên
        if (!empty($data['HoTen'])) {
            $accounts[$username]['HoTen'] = $data['HoTen'];
            $accounts[$username]['TenNhanVien'] = $data['HoTen'];
        }

        // Cập nhật loại tài khoản
        if (!empty($data['LoaiTK'])) {
            $accounts[$username]['LoaiTK'] = $data['LoaiTK'];
        }

        // Cập nhật mật khẩu (chỉ khi có điền) - lưu plaintext giống createAccount
        if (!empty($data['MatKhau'])) {
            $accounts[$username]['MatKhau'] = $data['MatKhau'];
        }

        return file_put_contents($jsonFile, json_encode($accounts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Đăng nhập (dùng tài khoản hardcode + JSON)
     */
    public function login($tenDangNhap, $matKhau)
    {
        $allAccounts = $this->getAllAccounts();

        if (isset($allAccounts[$tenDangNhap])) {
            $account = $allAccounts[$tenDangNhap];
            if ($account['MatKhau'] === $matKhau) {
                return [
                    'MaNhanVien'  => $account['MaNhanVien'] ?? 0,
                    'TenNhanVien' => $account['TenNhanVien'],
                    'HoTen'       => $account['HoTen'] ?? $account['TenNhanVien'],
                    'ChucVu'      => $account['ChucVu'],
                    'TenDangNhap' => $tenDangNhap,
                    'LoaiTK'      => $account['LoaiTK']
                ];
            }
        }
        return false;
    }

    /**
     * Lấy tất cả nhân viên từ database
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY MaNhanVien DESC");
        return $stmt->fetchAll();
    }

    /**
     * Tìm nhân viên theo mã
     */
    public function find($maNhanVien)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaNhanVien = ?");
        $stmt->execute([$maNhanVien]);
        return $stmt->fetch();
    }

    /**
     * Lấy nhân viên theo chức vụ
     */
    public function getByChucVu($chucVu)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE ChucVu = ?");
        $stmt->execute([$chucVu]);
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm nhân viên
     */
    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE TenNhanVien LIKE ? 
                OR ChucVu LIKE ? 
                OR SoDienThoai LIKE ?
                ORDER BY MaNhanVien DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm nhân viên mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (TenNhanVien, ChucVu, SoDienThoai, DiaChi, TenDangNhap, MatKhau, TrangThai) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $matKhau = $data['MatKhau'] ?? '123456';
        // Hash mật khẩu nếu chưa được hash
        if (strlen($matKhau) < 60) {
            $matKhau = password_hash($matKhau, PASSWORD_DEFAULT);
        }
        $tenDangNhap = $data['TenDangNhap'] ?? null;
        if ($tenDangNhap === '') {
            $tenDangNhap = null;
        }
        $result = $stmt->execute([
            $data['TenNhanVien'] ?? '',
            $data['ChucVu'] ?? '',
            $data['SoDienThoai'] ?? '',
            $data['DiaChi'] ?? '',
            $tenDangNhap,
            $matKhau,
            intval($data['TrangThai'] ?? 1)
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Cập nhật nhân viên
     */
    public function update($maNhanVien, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET TenNhanVien = ?, ChucVu = ?, SoDienThoai = ?, DiaChi = ?
                WHERE MaNhanVien = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenNhanVien'] ?? '',
            $data['ChucVu'] ?? '',
            $data['SoDienThoai'] ?? '',
            $data['DiaChi'] ?? '',
            $maNhanVien
        ]);
    }

    /**
     * Xóa nhân viên
     */
    public function delete($maNhanVien)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaNhanVien = ?");
        return $stmt->execute([$maNhanVien]);
    }

    /**
     * Đếm tổng số nhân viên
     */
    public function count()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }

    /**
     * Xác minh mật khẩu theo username
     */
    public function verifyPassword($username, $matKhau)
    {
        $allAccounts = $this->getAllAccounts();
        if (!isset($allAccounts[$username])) return false;
        return $allAccounts[$username]['MatKhau'] === $matKhau;
    }

    /**
     * Đổi mật khẩu theo username
     */
    public function changePassword($username, $matKhauMoi)
    {
        $jsonFile = ROOT_PATH . '/data/accounts.json';
        $accounts = file_exists($jsonFile)
            ? (json_decode(file_get_contents($jsonFile), true) ?? [])
            : [];

        if (isset($accounts[$username])) {
            $accounts[$username]['MatKhau'] = $matKhauMoi;
        } elseif (isset($this->accounts[$username])) {
            $accounts[$username] = $this->accounts[$username];
            $accounts[$username]['MatKhau'] = $matKhauMoi;
        } else {
            return false;
        }

        return file_put_contents($jsonFile, json_encode($accounts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Tìm hoặc tạo tài khoản Google (khách hàng)
     */
    public function findOrCreateGoogleAccount($googleUser)
    {
        $email = $googleUser['email'] ?? '';
        $name  = $googleUser['name'] ?? $email;
        $googleId = $googleUser['id'] ?? '';

        if (empty($email)) return false;

        // Username = 'gg_' + phần trước @ của email
        $username = 'gg_' . explode('@', $email)[0];

        $allAccounts = $this->getAllAccounts();

        // Nếu đã có tài khoản Google này
        if (isset($allAccounts[$username])) {
            $acc = $allAccounts[$username];
            return [
                'MaNhanVien'  => $acc['MaNhanVien'] ?? 0,
                'TenNhanVien' => $acc['TenNhanVien'],
                'HoTen'       => $acc['HoTen'] ?? $acc['TenNhanVien'],
                'ChucVu'      => $acc['ChucVu'],
                'TenDangNhap' => $username,
                'LoaiTK'      => $acc['LoaiTK'],
                'Email'       => $email,
                'GoogleId'    => $googleId
            ];
        }

        // Tạo tài khoản mới
        $newAccount = [
            'MaNhanVien'  => 0,
            'TenNhanVien' => $name,
            'HoTen'       => $name,
            'ChucVu'      => 'Khách hàng',
            'MatKhau'     => bin2hex(random_bytes(16)),
            'LoaiTK'      => 'khachhang',
            'Email'       => $email,
            'GoogleId'    => $googleId
        ];

        $jsonFile = ROOT_PATH . '/data/accounts.json';
        $accounts = file_exists($jsonFile)
            ? (json_decode(file_get_contents($jsonFile), true) ?? [])
            : [];

        $accounts[$username] = $newAccount;
        $ok = file_put_contents($jsonFile, json_encode($accounts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        if ($ok === false) return false;

        return [
            'MaNhanVien'  => 0,
            'TenNhanVien' => $name,
            'HoTen'       => $name,
            'ChucVu'      => 'Khách hàng',
            'TenDangNhap' => $username,
            'LoaiTK'      => 'khachhang',
            'Email'       => $email,
            'GoogleId'    => $googleId
        ];
    }
}
