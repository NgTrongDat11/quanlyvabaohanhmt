<?php
/**
 * Model ChiTietSuaChua
 */

class ChiTietSuaChua
{
    private $db;
    private $table = 'chitietsuachua';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy chi tiết theo mã phiếu
     */
    public function getByPhieu($maPhieu)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaPhieu = ?");
        $stmt->execute([$maPhieu]);
        return $stmt->fetchAll();
    }

    /**
     * Tìm chi tiết theo mã
     */
    public function find($maChiTiet)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE MaChiTiet = ?");
        $stmt->execute([$maChiTiet]);
        return $stmt->fetch();
    }

    /**
     * Thêm chi tiết sửa chữa
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (MaPhieu, HangMuc, SoLuong, DonGia, ThanhTien) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $donGia = floatval($data['DonGia'] ?? 0);
        $soLuong = intval($data['SoLuong'] ?? 1);
        $thanhTien = floatval($data['ThanhTien'] ?? ($donGia * $soLuong));
        $result = $stmt->execute([
            intval($data['MaPhieu'] ?? 0),
            $data['HangMuc'] ?? '',
            $soLuong,
            $donGia,
            $thanhTien
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Cập nhật chi tiết
     */
    public function update($maChiTiet, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET HangMuc = ?, SoLuong = ?, DonGia = ?, ThanhTien = ?
                WHERE MaChiTiet = ?";
        $stmt = $this->db->prepare($sql);
        $donGia = floatval($data['DonGia'] ?? 0);
        $soLuong = intval($data['SoLuong'] ?? 1);
        $thanhTien = floatval($data['ThanhTien'] ?? ($donGia * $soLuong));
        return $stmt->execute([
            $data['HangMuc'] ?? '',
            $soLuong,
            $donGia,
            $thanhTien,
            $maChiTiet
        ]);
    }

    /**
     * Xóa chi tiết
     */
    public function delete($maChiTiet)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaChiTiet = ?");
        return $stmt->execute([$maChiTiet]);
    }

    /**
     * Xóa tất cả chi tiết của phiếu
     */
    public function deleteByPhieu($maPhieu)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaPhieu = ?");
        return $stmt->execute([$maPhieu]);
    }

    /**
     * Tính tổng tiền của phiếu
     */
    public function getTongTien($maPhieu)
    {
        $stmt = $this->db->prepare("SELECT SUM(ThanhTien) as total FROM {$this->table} WHERE MaPhieu = ?");
        $stmt->execute([$maPhieu]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
