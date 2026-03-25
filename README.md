# Cao Hùng Tech - Hệ thống quản lý sửa chữa thiết bị

Ứng dụng web PHP MVC thuần dùng để quản lý tiếp nhận, sửa chữa, theo dõi và trả thiết bị cho khách hàng.

## Tính năng chính

- Quản lý tài khoản theo vai trò: `admin`, `nhanvien`, `ktv`, `khachhang`.
- Đăng nhập, đăng ký, đổi mật khẩu, đăng nhập Google OAuth.
- Tạo và quản lý phiếu sửa chữa, cập nhật trạng thái theo quy trình.
- Nhân viên tiếp nhận/trả phiếu, kỹ thuật viên xử lý và cập nhật chi tiết sửa chữa.
- Khách hàng tra cứu đơn, xem tiến độ và thông tin phiếu của mình.
- Khu vực admin: thống kê, danh sách khách hàng, sản phẩm, nhân viên, tài khoản.

## Công nghệ

- PHP (MVC thuần, không framework)
- MySQL/MariaDB
- Apache (mod_rewrite)
- HTML/CSS/JavaScript

## Cấu trúc thư mục (rút gọn)

```text
app/
  controllers/   # Xử lý theo vai trò và nghiệp vụ
  models/        # Tương tác dữ liệu
  views/         # Giao diện
config/          # Cấu hình app và database
core/            # App, Controller, Database
public/          # Entry point, assets
database/        # SQL khởi tạo
data/            # Dữ liệu tài khoản mẫu (JSON)
storage/         # Logs, cache, uploads
```

## Cài đặt nhanh

1. Copy project vào `htdocs` (XAMPP), ví dụ: `xampp/htdocs/TEST`.
2. Tạo file `.env` từ `.env.example` và cập nhật thông số môi trường.
3. Tạo database `qlbhmt` trong MySQL.
4. Import file `database/QLBHMT.sql`.
5. Truy cập: `http://localhost/TEST/`.

## Tài khoản mẫu

Dữ liệu tài khoản mẫu nằm trong `data/accounts.json`.

- Admin: `admin1 / 123456`
- Nhân viên: `huakhanhdang / 123456`
- Kỹ thuật viên: `nguyentrongdat / 123456`
- Khách hàng: `khachhang1 / 123456`

## Định dạng route

Mẫu URL:

```text
http://localhost/TEST/controller/method/param1/param2
```

Ví dụ:

- `http://localhost/TEST/`
- `http://localhost/TEST/auth/login`
- `http://localhost/TEST/khach/tracuu`

## Lưu ý bảo mật

- Không commit thông tin nhạy cảm (client secret, mật khẩu thật) lên public repository.
- Nên đổi mật khẩu tài khoản mẫu trước khi đưa vào môi trường thật.
- Dùng file `.env` cho cấu hình thật; chỉ commit `.env.example`.

## License

Free to use and modify.
