<?php
/**
 * Model KhachHang
 */

class KhachHang
{
    private $db;
    private $table = 'khachhang';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả khách hàng
     */
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY MaKhachHang DESC");
        return $stmt->fetchAll();
    }

    /**
     * Tìm khách hàng theo mã
     */
    public function find($maKhachHang)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaKhachHang = ?");
        $stmt->execute([$maKhachHang]);
        return $stmt->fetch();
    }

    /**
     * Tìm khách hàng theo số điện thoại
     */
    public function findByPhone($soDienThoai)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE SoDienThoai = ?");
        $stmt->execute([$soDienThoai]);
        return $stmt->fetch();
    }

    /**
     * Tìm kiếm khách hàng
     */
    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE TenKhachHang LIKE ? 
                OR SoDienThoai LIKE ? 
                OR DiaChi LIKE ?
                ORDER BY MaKhachHang DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    /**
     * Thêm khách hàng mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (TenKhachHang, DiaChi, SoDienThoai, GhiChu) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['TenKhachHang'] ?? '',
            $data['DiaChi'] ?? '',
            $data['SoDienThoai'] ?? '',
            $data['GhiChu'] ?? ''
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Cập nhật khách hàng
     */
    public function update($maKhachHang, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET TenKhachHang = ?, DiaChi = ?, SoDienThoai = ?, GhiChu = ?
                WHERE MaKhachHang = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenKhachHang'] ?? '',
            $data['DiaChi'] ?? '',
            $data['SoDienThoai'] ?? '',
            $data['GhiChu'] ?? '',
            $maKhachHang
        ]);
    }

    /**
     * Xóa khách hàng
     */
    public function delete($maKhachHang)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaKhachHang = ?");
        return $stmt->execute([$maKhachHang]);
    }

    /**
     * Đếm tổng số khách hàng
     */
    public function count()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }
}
