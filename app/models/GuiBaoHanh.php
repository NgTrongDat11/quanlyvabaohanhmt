<?php
/**
 * Model GuiBaoHanh - Quản lý gửi bảo hành
 */

class GuiBaoHanh
{
    private $db;
    private $table = 'guibaohanh';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả bản ghi, join phieusuachua + khachhang
     */
    public function all()
    {
        $stmt = $this->db->query("
            SELECT g.*, p.LoaiDichVu, p.TinhTrang AS TinhTrangPhieu,
                   k.TenKhachHang, k.SoDienThoai
            FROM {$this->table} g
            LEFT JOIN phieusuachua p ON g.MaPhieu = p.MaPhieu
            LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
            ORDER BY g.NgayGui DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm theo MaBaoHanh
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT g.*, p.LoaiDichVu, p.TinhTrang AS TinhTrangPhieu,
                   k.TenKhachHang, k.SoDienThoai
            FROM {$this->table} g
            LEFT JOIN phieusuachua p ON g.MaPhieu = p.MaPhieu
            LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
            WHERE g.MaBaoHanh = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy theo MaPhieu
     */
    public function getByPhieu($maPhieu)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} WHERE MaPhieu = ?
            ORDER BY NgayGui DESC
        ");
        $stmt->execute([$maPhieu]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo bản ghi mới
     */
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
                (MaPhieu, TenTrungTamBH, NgayGui, NgayNhanLai, KetQuaBaoHanh, GhiChu, DiaChi, SoDienThoai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Xử lý NgayNhanLai: empty string → null
        $ngayNhanLai = $data['NgayNhanLai'] ?? null;
        if ($ngayNhanLai === '' || $ngayNhanLai === '0000-00-00' || $ngayNhanLai === '0000-00-00 00:00:00') {
            $ngayNhanLai = null;
        }
        // Xử lý NgayGui: empty string → now
        $ngayGui = $data['NgayGui'] ?? '';
        if ($ngayGui === '') {
            $ngayGui = date('Y-m-d H:i:s');
        }

        return $stmt->execute([
            intval($data['MaPhieu'] ?? 0),
            $data['TenTrungTamBH'] ?? '',
            $ngayGui,
            $ngayNhanLai,
            $data['KetQuaBaoHanh'] ?? '',
            $data['GhiChu'] ?? '',
            $data['DiaChi'] ?? '',
            $data['SoDienThoai'] ?? ''
        ]);
    }

    /**
     * Cập nhật bản ghi
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET TenTrungTamBH = ?, NgayGui = ?, NgayNhanLai = ?,
                KetQuaBaoHanh = ?, GhiChu = ?, DiaChi = ?, SoDienThoai = ?
            WHERE MaBaoHanh = ?
        ");

        // Xử lý NgayNhanLai: empty string → null
        $ngayNhanLai = $data['NgayNhanLai'] ?? null;
        if ($ngayNhanLai === '' || $ngayNhanLai === '0000-00-00' || $ngayNhanLai === '0000-00-00 00:00:00') {
            $ngayNhanLai = null;
        }
        $ngayGui = $data['NgayGui'] ?? '';
        if ($ngayGui === '') {
            $ngayGui = date('Y-m-d H:i:s');
        }

        return $stmt->execute([
            $data['TenTrungTamBH'] ?? '',
            $ngayGui,
            $ngayNhanLai,
            $data['KetQuaBaoHanh'] ?? '',
            $data['GhiChu'] ?? '',
            $data['DiaChi'] ?? '',
            $data['SoDienThoai'] ?? '',
            $id
        ]);
    }

    /**
     * Xóa bản ghi
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaBaoHanh = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Lấy danh sách MaPhieu đã có bản ghi gửi bảo hành
     */
    public function getMaPhieuDaGui()
    {
        $stmt = $this->db->query("SELECT DISTINCT MaPhieu FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Đếm tổng
     */
    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
