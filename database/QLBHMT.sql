Hello. Yeah. Player. Going up to bum. -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 03, 2026 lúc 08:23 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlbhmt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietsuachua`
--

CREATE TABLE `chitietsuachua` (
  `MaChiTiet` int(100) NOT NULL,
  `MaPhieu` int(100) NOT NULL,
  `HangMuc` varchar(100) NOT NULL,
  `SoLuong` varchar(100) NOT NULL,
  `DonGia` decimal(18,2) NOT NULL,
  `ThanhTien` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guibaohanh`
--

CREATE TABLE `guibaohanh` (
  `MaBaoHanh` int(11) NOT NULL,
  `MaPhieu` int(11) NOT NULL,
  `TenTrungTamBH` varchar(200) NOT NULL,
  `NgayGui` datetime NOT NULL,
  `NgayNhanLai` datetime NOT NULL,
  `KetQuaBaoHanh` varchar(200) NOT NULL,
  `GhiChu` text NOT NULL,
  `DiaChi` varchar(200) NOT NULL,
  `SoDienThoai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guidoitac`
--

CREATE TABLE `guidoitac` (
  `MaGuiDT` int(11) NOT NULL,
  `MaPhieu` int(11) NOT NULL,
  `TenDoiTac` varchar(200) NOT NULL,
  `NgayGui` datetime NOT NULL,
  `NgayNhanLai` datetime NOT NULL,
  `ChiPhi` decimal(18,2) NOT NULL,
  `TrangThai` varchar(200) NOT NULL,
  `GhiChu` text NOT NULL,
  `DiaChi` varchar(200) NOT NULL,
  `SoDienThoai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` int(100) NOT NULL,
  `TenKhachHang` varchar(100) NOT NULL,
  `DiaChi` varchar(120) NOT NULL,
  `SoDienThoai` varchar(100) NOT NULL,
  `GhiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `TenKhachHang`, `DiaChi`, `SoDienThoai`, `GhiChu`) VALUES
(1, 'Nguyen Van A', 'Tra Vinh', '0123456789', 'Test'),
(2, 'Nguyễn Trọng Đạt', '123 Trà Vinh', '0782929512', 'Tài khoản: nguyentrongdat1@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNhanVien` int(100) NOT NULL,
  `TenNhanVien` varchar(120) NOT NULL,
  `ChucVu` varchar(100) NOT NULL,
  `SoDienThoai` varchar(50) NOT NULL,
  `DiaChi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieusuachua`
--

CREATE TABLE `phieusuachua` (
  `MaPhieu` int(100) NOT NULL,
  `MaKhachHang` int(100) NOT NULL,
  `MaSanPham` int(100) NOT NULL,
  `MaNhanVien` int(100) NOT NULL,
  `MaKTV` int(100) NOT NULL,
  `MaNhanVienTra` int(100) NOT NULL,
  `NgayNhan` datetime NOT NULL,
  `NgayTra` datetime NOT NULL,
  `LoaiDichVu` varchar(100) NOT NULL,
  `TinhTrang` text NOT NULL,
  `PhuKienKemTheo` text NOT NULL,
  `TongTien` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int(100) NOT NULL,
  `TenSanPham` varchar(100) NOT NULL,
  `LoaiSanPham` varchar(100) NOT NULL,
  `HangSanXuat` varchar(100) NOT NULL,
  `ThuongHieu` varchar(100) NOT NULL,
  `MaSerial` varchar(100) NOT NULL,
  `GhiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD KEY `FK_ChiTietSuaChua_MaPhieu` (`MaPhieu`);

--
-- Chỉ mục cho bảng `guibaohanh`
--
ALTER TABLE `guibaohanh`
  ADD PRIMARY KEY (`MaBaoHanh`),
  ADD KEY `MaPhieu` (`MaPhieu`);

--
-- Chỉ mục cho bảng `guidoitac`
--
ALTER TABLE `guidoitac`
  ADD PRIMARY KEY (`MaGuiDT`),
  ADD KEY `MaPhieu` (`MaPhieu`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNhanVien`);

--
-- Chỉ mục cho bảng `phieusuachua`
--
ALTER TABLE `phieusuachua`
  ADD PRIMARY KEY (`MaPhieu`),
  ADD KEY `MaKhachHang` (`MaKhachHang`),
  ADD KEY `MaSanPham` (`MaSanPham`),
  ADD KEY `MaNhanVien` (`MaNhanVien`),
  ADD KEY `MaKTV` (`MaKTV`),
  ADD KEY `FK_PhieuSuaChua_MaNhanVienTra` (`MaNhanVienTra`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  MODIFY `MaChiTiet` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `guibaohanh`
--
ALTER TABLE `guibaohanh`
  MODIFY `MaBaoHanh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `guidoitac`
--
ALTER TABLE `guidoitac`
  MODIFY `MaGuiDT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKhachHang` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNhanVien` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phieusuachua`
--
ALTER TABLE `phieusuachua`
  MODIFY `MaPhieu` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int(100) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  ADD CONSTRAINT `FK_ChiTietSuaChua_MaPhieu` FOREIGN KEY (`MaPhieu`) REFERENCES `phieusuachua` (`MaPhieu`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `guibaohanh`
--
ALTER TABLE `guibaohanh`
  ADD CONSTRAINT `FK_GuiBaoHanh_MaPhieu` FOREIGN KEY (`MaPhieu`) REFERENCES `phieusuachua` (`MaPhieu`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `guidoitac`
--
ALTER TABLE `guidoitac`
  ADD CONSTRAINT `FK_GuiDoiTac_MaPhieu` FOREIGN KEY (`MaPhieu`) REFERENCES `phieusuachua` (`MaPhieu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `phieusuachua`
--
ALTER TABLE `phieusuachua`
  ADD CONSTRAINT `FK_PhieuSuaChua_MaKTV` FOREIGN KEY (`MaKTV`) REFERENCES `nhanvien` (`MaNhanVien`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PhieuSuaChua_MaKhachHang` FOREIGN KEY (`MaKhachHang`) REFERENCES `khachhang` (`MaKhachHang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PhieuSuaChua_MaNhanVien` FOREIGN KEY (`MaNhanVien`) REFERENCES `nhanvien` (`MaNhanVien`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PhieuSuaChua_MaNhanVienTra` FOREIGN KEY (`MaNhanVienTra`) REFERENCES `nhanvien` (`MaNhanVien`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PhieuSuaChua_MaSanPham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
