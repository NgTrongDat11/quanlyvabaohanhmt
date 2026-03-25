<?php
/**
 * Model SanPham
 */

class SanPham
{
    private $db;
    private $table = 'sanpham';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả sản phẩm
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY MaSanPham ASC");
        return $stmt->fetchAll();
    }

    /**
     * Lấy sản phẩm đang tiếp nhận (chưa trả)
     */
    public function allDangTiepNhan()
    {
        $sql = "SELECT s.*, p.TinhTrang, p.MaPhieu
                FROM {$this->table} s
                INNER JOIN phieusuachua p ON p.MaSanPham = s.MaSanPham
                ORDER BY s.MaSanPham DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Tìm sản phẩm theo mã
     */
    public function find($maSanPham)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaSanPham = ?");
        $stmt->execute([$maSanPham]);
        return $stmt->fetch();
    }

    /**
     * Tìm sản phẩm theo Serial
     */
    public function findBySerial($maSerial)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaSerial = ?");
        $stmt->execute([$maSerial]);
        return $stmt->fetch();
    }

    /**
     * Lấy sản phẩm theo loại
     */
    public function getByLoai($loaiSanPham)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE LoaiSanPham = ?");
        $stmt->execute([$loaiSanPham]);
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE TenSanPham LIKE ? 
                OR LoaiSanPham LIKE ? 
                OR HangSanXuat LIKE ?
                OR ThuongHieu LIKE ?
                OR MaSerial LIKE ?
                ORDER BY MaSanPham DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm sản phẩm mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (TenSanPham, LoaiSanPham, HangSanXuat, ThuongHieu, MaSerial, HinhAnh, GhiChu) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['TenSanPham'] ?? '',
            $data['LoaiSanPham'] ?? '',
            $data['HangSanXuat'] ?? '',
            $data['ThuongHieu'] ?? '',
            $data['MaSerial'] ?? '',
            $data['HinhAnh'] ?? '',
            $data['GhiChu'] ?? ''
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update($maSanPham, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET TenSanPham = ?, LoaiSanPham = ?, HangSanXuat = ?, 
                    ThuongHieu = ?, MaSerial = ?, HinhAnh = ?, GhiChu = ?
                WHERE MaSanPham = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenSanPham'] ?? '',
            $data['LoaiSanPham'] ?? '',
            $data['HangSanXuat'] ?? '',
            $data['ThuongHieu'] ?? '',
            $data['MaSerial'] ?? '',
            $data['HinhAnh'] ?? '',
            $data['GhiChu'] ?? '',
            $maSanPham
        ]);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete($maSanPham)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaSanPham = ?");
        return $stmt->execute([$maSanPham]);
    }

    /**
     * Đếm tổng số sản phẩm
     */
    public function count()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }

    /**
     * Đếm sản phẩm đang tiếp nhận (chưa trả)
     */
    public function countDangTiepNhan()
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table} s
                INNER JOIN phieusuachua p ON p.MaSanPham = s.MaSanPham
                WHERE p.TinhTrang NOT IN ('Đã trả')";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'];
    }
}
