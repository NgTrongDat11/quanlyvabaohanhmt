<?php
/**
 * Model PhieuSuaChua
 */

class PhieuSuaChua
{
    private $db;
    private $table = 'phieusuachua';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả phiếu sửa chữa với thông tin liên quan
     */
    public function all()
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial,
                n1.TenNhanVien as TenNVNhan,
                n2.TenNhanVien as TenKTV,
                n3.TenNhanVien as TenNVTra
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                LEFT JOIN nhanvien n1 ON p.MaNhanVien = n1.MaNhanVien
                LEFT JOIN nhanvien n2 ON p.MaKTV = n2.MaNhanVien
                LEFT JOIN nhanvien n3 ON p.MaNhanVienTra = n3.MaNhanVien
                ORDER BY p.MaPhieu DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Tìm phiếu sửa chữa theo mã
     */
    public function find($maPhieu)
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang, k.DiaChi as DiaChi_KH,
                s.TenSanPham, s.MaSerial, s.LoaiSanPham, s.HangSanXuat, s.ThuongHieu, s.HinhAnh, s.GhiChu as GhiChuSP,
                n1.TenNhanVien as TenNVNhan,
                n2.TenNhanVien as TenKTV,
                n3.TenNhanVien as TenNVTra
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                LEFT JOIN nhanvien n1 ON p.MaNhanVien = n1.MaNhanVien
                LEFT JOIN nhanvien n2 ON p.MaKTV = n2.MaNhanVien
                LEFT JOIN nhanvien n3 ON p.MaNhanVienTra = n3.MaNhanVien
                WHERE p.MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhieu]);
        return $stmt->fetch();
    }

    /**
     * Lấy phiếu theo khách hàng
     */
    public function getByKhachHang($maKhachHang)
    {
        $sql = "SELECT p.*, s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.MaKhachHang = ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKhachHang]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy phiếu theo tài khoản khách hàng
     */
    public function getByTaiKhoanKH($taiKhoan)
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.TaiKhoanKH = ?
                   OR k.GhiChu LIKE CONCAT('Tài khoản: ', ?)
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$taiKhoan, $taiKhoan]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm phiếu sửa chữa mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (MaKhachHang, MaSanPham, MaNhanVien, MaKTV, MaNhanVienTra, 
                NgayNhan, NgayTra, LoaiDichVu, TinhTrang, GhiChuTinhTrang, PhuKienKemTheo, TongTien, TaiKhoanKH) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        // Xử lý NgayTra: empty string → null
        $ngayTra = $data['NgayTra'] ?? null;
        if ($ngayTra === '' || $ngayTra === '0000-00-00' || $ngayTra === '0000-00-00 00:00:00') {
            $ngayTra = null;
        }

        $result = $stmt->execute([
            intval($data['MaKhachHang'] ?? 0),
            intval($data['MaSanPham'] ?? 0),
            intval($data['MaNhanVien'] ?? 0),
            intval($data['MaKTV'] ?? 0),
            intval($data['MaNhanVienTra'] ?? 0),
            $data['NgayNhan'] ?: date('Y-m-d H:i:s'),
            $ngayTra,
            $data['LoaiDichVu'] ?? '',
            $data['TinhTrang'] ?? 'Chờ xử lý',
            $data['GhiChuTinhTrang'] ?? '',
            $data['PhuKienKemTheo'] ?? '',
            floatval($data['TongTien'] ?? 0),
            $data['TaiKhoanKH'] ?? ''
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Cập nhật phiếu sửa chữa
     */
    public function update($maPhieu, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET MaKhachHang = ?, MaSanPham = ?, MaNhanVien = ?, 
                    MaKTV = ?, MaNhanVienTra = ?, NgayNhan = ?, NgayTra = ?,
                    LoaiDichVu = ?, TinhTrang = ?, GhiChuTinhTrang = ?,
                    PhuKienKemTheo = ?, TongTien = ?, TaiKhoanKH = ?
                WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);

        // Xử lý NgayTra: empty string → null
        $ngayTra = $data['NgayTra'] ?? null;
        if ($ngayTra === '' || $ngayTra === '0000-00-00' || $ngayTra === '0000-00-00 00:00:00') {
            $ngayTra = null;
        }

        return $stmt->execute([
            intval($data['MaKhachHang'] ?? 0),
            intval($data['MaSanPham'] ?? 0),
            intval($data['MaNhanVien'] ?? 0),
            intval($data['MaKTV'] ?? 0),
            intval($data['MaNhanVienTra'] ?? 0),
            $data['NgayNhan'] ?: date('Y-m-d H:i:s'),
            $ngayTra,
            $data['LoaiDichVu'] ?? '',
            $data['TinhTrang'] ?? '',
            $data['GhiChuTinhTrang'] ?? '',
            $data['PhuKienKemTheo'] ?? '',
            floatval($data['TongTien'] ?? 0),
            $data['TaiKhoanKH'] ?? '',
            $maPhieu
        ]);
    }

    /**
     * Xóa phiếu sửa chữa
     */
    public function delete($maPhieu)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaPhieu = ?");
        return $stmt->execute([$maPhieu]);
    }

    /**
     * Đếm tổng số phiếu
     */
    public function count()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }

    /**
     * Thống kê theo trạng thái
     */
    public function thongKeTrangThai()
    {
        $sql = "SELECT TinhTrang, COUNT(*) as SoLuong, SUM(TongTien) as TongTien
                FROM {$this->table}
                GROUP BY TinhTrang";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Lấy phiếu theo trạng thái
     */
    public function getByTrangThai($trangThai)
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.TinhTrang = ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$trangThai]);
        return $stmt->fetchAll();
    }

    /**
     * Cập nhật trạng thái phiếu
     */
    public function updateTrangThai($maPhieu, $trangThai)
    {
        $sql = "UPDATE {$this->table} SET TinhTrang = ? WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$trangThai, $maPhieu]);
    }

    /**
     * Lấy phiếu theo ngày
     */
    public function getByDate($date)
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, s.TenSanPham
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE DATE(p.NgayNhan) = ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    /**
     * Cập nhật tổng tiền
     */
    public function updateTongTien($maPhieu)
    {
        $sql = "UPDATE {$this->table} p 
                SET TongTien = (
                    SELECT COALESCE(SUM(ThanhTien), 0) 
                    FROM chitietsuachua 
                    WHERE MaPhieu = ?
                )
                WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maPhieu, $maPhieu]);
    }

    /**
     * Phân công KTV cho phiếu
     */
    public function phancongKTV($maPhieu, $tenDangNhapKTV)
    {
        $sql = "UPDATE {$this->table} SET TenDangNhapKTV = ?, TinhTrang = 'Đã phân công' WHERE MaPhieu = ? AND TinhTrang = 'Chờ xử lý'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tenDangNhapKTV, $maPhieu]);
        
        // Nếu phiếu đã ở trạng thái khác (đang sửa, etc.), chỉ đổi KTV không đổi trạng thái
        if ($stmt->rowCount() === 0) {
            $sql2 = "UPDATE {$this->table} SET TenDangNhapKTV = ? WHERE MaPhieu = ?";
            $stmt2 = $this->db->prepare($sql2);
            return $stmt2->execute([$tenDangNhapKTV, $maPhieu]);
        }
        return true;
    }

    /**
     * Cập nhật TenDangNhapNVNhan
     */
    public function setNVNhan($maPhieu, $tenDangNhap)
    {
        $sql = "UPDATE {$this->table} SET TenDangNhapNVNhan = ? WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tenDangNhap, $maPhieu]);
    }

    /**
     * Cập nhật TenDangNhapNVTra (không thay đổi NgayTra)
     */
    public function setNVTra($maPhieu, $tenDangNhap)
    {
        $sql = "UPDATE {$this->table} SET TenDangNhapNVTra = ? WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tenDangNhap, $maPhieu]);
    }

    /**
     * Cập nhật NgayTra = NOW() khi thực sự trả thiết bị
     */
    public function setNgayTra($maPhieu)
    {
        $sql = "UPDATE {$this->table} SET NgayTra = NOW() WHERE MaPhieu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maPhieu]);
    }

    /**
     * Lấy phiếu theo KTV (TenDangNhapKTV)
     */
    public function getByKTV($tenDangNhap)
    {
        $sql = "SELECT p.*,
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.TenDangNhapKTV = ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tenDangNhap]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy phiếu theo KTV + trạng thái
     */
    public function getByKTVAndTrangThai($tenDangNhap, $trangThai)
    {
        $sql = "SELECT p.*,
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.TenDangNhapKTV = ? AND p.TinhTrang = ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tenDangNhap, $trangThai]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy phiếu theo KTV + nhiều trạng thái
     */
    public function getByKTVAndTrangThaiMulti($tenDangNhap, array $trangThais)
    {
        $placeholders = implode(',', array_fill(0, count($trangThais), '?'));
        $sql = "SELECT p.*,
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.TenDangNhapKTV = ? AND p.TinhTrang IN ({$placeholders})
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge([$tenDangNhap], $trangThais));
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm phiếu theo mã phiếu hoặc SĐT khách hàng
     */
    public function search($keyword)
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang, k.DiaChi,
                s.TenSanPham
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                WHERE p.MaPhieu = ? 
                   OR k.SoDienThoai LIKE ? 
                   OR k.TenKhachHang LIKE ?
                ORDER BY p.NgayNhan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$keyword, "%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll();
    }

    /**
     * Lọc phiếu theo nhiều tiêu chí
     */
    public function filter($filters = [])
    {
        $sql = "SELECT p.*, 
                k.TenKhachHang, k.SoDienThoai as SDT_KhachHang,
                s.TenSanPham, s.MaSerial,
                n1.TenNhanVien as TenNVNhan,
                n2.TenNhanVien as TenKTV,
                n3.TenNhanVien as TenNVTra
                FROM {$this->table} p
                LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
                LEFT JOIN sanpham s ON p.MaSanPham = s.MaSanPham
                LEFT JOIN nhanvien n1 ON p.MaNhanVien = n1.MaNhanVien
                LEFT JOIN nhanvien n2 ON p.MaKTV = n2.MaNhanVien
                LEFT JOIN nhanvien n3 ON p.MaNhanVienTra = n3.MaNhanVien
                WHERE 1=1";
        $params = [];

        if (!empty($filters['tu_ngay'])) {
            $sql .= " AND DATE(p.NgayNhan) >= ?";
            $params[] = $filters['tu_ngay'];
        }
        if (!empty($filters['den_ngay'])) {
            $sql .= " AND DATE(p.NgayNhan) <= ?";
            $params[] = $filters['den_ngay'];
        }
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND p.TinhTrang = ?";
            $params[] = $filters['trang_thai'];
        }
        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $sql .= " AND (p.MaPhieu = ? OR k.TenKhachHang LIKE ? OR k.SoDienThoai LIKE ? OR s.TenSanPham LIKE ?)";
            $params[] = intval($q);
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
        }

        $sql .= " ORDER BY p.MaPhieu DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
