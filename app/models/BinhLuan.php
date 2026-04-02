<?php
/**
 * Model BinhLuan - Quản lý bình luận trong phiếu sửa chữa
 */
class BinhLuan
{
    private $db;
    private $table = 'binhluan';
    private $primaryKey = 'MaBinhLuan';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả bình luận của 1 phiếu
     */
    public function getByPhieu($maPhieu)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE MaPhieu = ?
            ORDER BY ThoiGian ASC
        ");
        $stmt->execute([$maPhieu]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm bình luận mới
     */
    public function themBinhLuan($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (MaPhieu, TenDangNhap, HoTen, LoaiTaiKhoan, NoiDung, ThoiGian)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $data['MaPhieu'],
            $data['TenDangNhap'],
            $data['HoTen'],
            $data['LoaiTaiKhoan'],
            $data['NoiDung']
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Xóa bình luận (chỉ admin hoặc người tạo)
     */
    public function xoaBinhLuan($maBinhLuan, $tenDangNhap, $isAdmin = false)
    {
        if ($isAdmin) {
            // Admin có thể xóa bất kỳ bình luận nào
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaBinhLuan = ?");
            return $stmt->execute([$maBinhLuan]);
        } else {
            // User chỉ có thể xóa bình luận của chính mình
            $stmt = $this->db->prepare("
                DELETE FROM {$this->table} 
                WHERE MaBinhLuan = ? AND TenDangNhap = ?
            ");
            return $stmt->execute([$maBinhLuan, $tenDangNhap]);
        }
    }

    /**
     * Sửa bình luận (chỉ admin hoặc người tạo)
     */
    public function suaBinhLuan($maBinhLuan, $noiDung, $tenDangNhap, $isAdmin = false)
    {
        if ($isAdmin) {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET NoiDung = ? WHERE MaBinhLuan = ?");
            return $stmt->execute([$noiDung, $maBinhLuan]);
        }

        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET NoiDung = ?
            WHERE MaBinhLuan = ? AND TenDangNhap = ?
        ");
        return $stmt->execute([$noiDung, $maBinhLuan, $tenDangNhap]);
    }

    /**
     * Đếm số bình luận của 1 phiếu
     */
    public function countByPhieu($maPhieu)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM {$this->table}
            WHERE MaPhieu = ?
        ");
        $stmt->execute([$maPhieu]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
