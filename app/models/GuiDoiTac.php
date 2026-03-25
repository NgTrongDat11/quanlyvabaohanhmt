<?php
/**
 * Model GuiDoiTac - Quản lý gửi đối tác
 */

class GuiDoiTac
{
    private $db;
    private $table = 'guidoitac';

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
     * Tìm theo MaGuiDT
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT g.*, p.LoaiDichVu, p.TinhTrang AS TinhTrangPhieu,
                   k.TenKhachHang, k.SoDienThoai
            FROM {$this->table} g
            LEFT JOIN phieusuachua p ON g.MaPhieu = p.MaPhieu
            LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
            WHERE g.MaGuiDT = ?
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
                (MaPhieu, TenDoiTac, NgayGui, NgayNhanLai, ChiPhi, TrangThai, GhiChu, DiaChi, SoDienThoai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
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
            intval($data['MaPhieu'] ?? 0),
            $data['TenDoiTac'] ?? '',
            $ngayGui,
            $ngayNhanLai,
            floatval($data['ChiPhi'] ?? 0),
            $data['TrangThai'] ?? 'Đang xử lý',
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
            SET TenDoiTac = ?, NgayGui = ?, NgayNhanLai = ?,
                ChiPhi = ?, TrangThai = ?, GhiChu = ?, DiaChi = ?, SoDienThoai = ?
            WHERE MaGuiDT = ?
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
            $data['TenDoiTac'] ?? '',
            $ngayGui,
            $ngayNhanLai,
            floatval($data['ChiPhi'] ?? 0),
            $data['TrangThai'] ?? 'Đang xử lý',
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
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE MaGuiDT = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Lấy danh sách MaPhieu đã có bản ghi gửi đối tác
     */
    public function getMaPhieuDaGui()
    {
        $stmt = $this->db->query("SELECT DISTINCT MaPhieu FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Lọc theo trạng thái
     */
    public function getByTrangThai($tt)
    {
        $stmt = $this->db->prepare("
            SELECT g.*, k.TenKhachHang
            FROM {$this->table} g
            LEFT JOIN phieusuachua p ON g.MaPhieu = p.MaPhieu
            LEFT JOIN khachhang k ON p.MaKhachHang = k.MaKhachHang
            WHERE g.TrangThai = ?
            ORDER BY g.NgayGui DESC
        ");
        $stmt->execute([$tt]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng
     */
    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
