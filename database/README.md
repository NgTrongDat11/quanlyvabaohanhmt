# Database - Cơ sở dữ liệu

## Hướng dẫn import database

### Cách 1: Sử dụng phpMyAdmin (Khuyến nghị)

1. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
2. Click vào tab **Import**
3. Click **Choose File** và chọn file `test_db.sql`
4. Click **Go** để import

### Cách 2: Sử dụng MySQL Command Line

```bash
mysql -u root -p < database/test_db.sql
```

### Cách 3: Copy-Paste trực tiếp

1. Mở phpMyAdmin
2. Click vào tab **SQL**
3. Copy toàn bộ nội dung file `test_db.sql`
4. Paste vào và click **Go**

## Cấu trúc Database

### Bảng `users` - Người dùng
- `id` - ID tự tăng
- `name` - Tên người dùng
- `email` - Email (unique)
- `password` - Mật khẩu đã hash
- `avatar` - Ảnh đại diện
- `role` - Vai trò (admin/user)
- `status` - Trạng thái (1: active, 0: inactive)
- `created_at` - Ngày tạo
- `updated_at` - Ngày cập nhật

**Tài khoản mẫu:**
- Email: `admin@example.com` | Password: `password` (admin)
- Email: `john@example.com` | Password: `password` (user)
- Email: `jane@example.com` | Password: `password` (user)

### Bảng `categories` - Danh mục
- `id` - ID tự tăng
- `name` - Tên danh mục
- `slug` - URL slug (unique)
- `description` - Mô tả
- `parent_id` - ID danh mục cha (nested categories)
- `order` - Thứ tự sắp xếp
- `status` - Trạng thái

### Bảng `posts` - Bài viết
- `id` - ID tự tăng
- `user_id` - ID người tạo (foreign key)
- `category_id` - ID danh mục (foreign key)
- `title` - Tiêu đề
- `slug` - URL slug (unique)
- `excerpt` - Trích đoạn
- `content` - Nội dung đầy đủ
- `thumbnail` - Ảnh đại diện
- `views` - Lượt xem
- `status` - Trạng thái (draft/published/archived)
- `published_at` - Ngày xuất bản
- `created_at` - Ngày tạo
- `updated_at` - Ngày cập nhật

### Bảng `settings` - Cấu hình
- `id` - ID tự tăng
- `key` - Khóa cấu hình (unique)
- `value` - Giá trị
- `type` - Loại dữ liệu
- `description` - Mô tả

## Migrations

Thư mục `migrations/` dùng để lưu các file migration (thay đổi cấu trúc database).

Đặt tên file theo format: `YYYY_MM_DD_HHMMSS_description.sql`

Ví dụ: `2026_03_03_120000_create_users_table.sql`

## Lưu ý

- Database name: `test_db`
- Charset: `utf8mb4_unicode_ci`
- Engine: `InnoDB`
- Tất cả bảng đều có `created_at` và `updated_at` tự động
