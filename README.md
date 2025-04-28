# Suncraft Website

Website chính thức của công ty Suncraft.

## Giới thiệu

Đây là mã nguồn cho trang web Suncraft, bao gồm các tính năng:
- Quản lý bài viết, danh mục
- Quản lý sản phẩm, danh mục sản phẩm
- Hệ thống quản trị nội dung
- Giao diện người dùng hiện đại, responsive

## Yêu cầu hệ thống

- PHP 7.4+ (khuyến nghị PHP 8.0+)
- MySQL 5.7+ hoặc MariaDB 10.3+
- Máy chủ web (Apache/Nginx)
- Các extension PHP: PDO, mysqli, mbstring, json, gd

## Cài đặt

### 1. Chuẩn bị hosting và cơ sở dữ liệu

- Tạo cơ sở dữ liệu MySQL mới trên máy chủ của bạn
- Tạo người dùng cơ sở dữ liệu với quyền truy cập đầy đủ

### 2. Cài đặt mã nguồn

#### Cách 1: Sử dụng Git

```bash
git clone https://github.com/yourusername/suncraft.git
cd suncraft
```

#### Cách 2: Tải về và giải nén thủ công

- Tải file ZIP từ repository
- Giải nén vào thư mục gốc của máy chủ web

### 3. Cấu hình

1. Chỉnh sửa file `config.php`:
   - Cập nhật thông tin kết nối cơ sở dữ liệu
   - Thiết lập các biến môi trường phù hợp
   - Trong môi trường production, đặt `DEBUG_MODE` thành `false`

2. Cấu hình máy chủ web:
   - File `.htaccess` đã được cấu hình cho Apache
   - Đảm bảo rằng `mod_rewrite` được bật trên Apache

### 4. Cài đặt cơ sở dữ liệu

1. Sử dụng công cụ phpMyAdmin hoặc MySQL CLI để nhập file `database_setup.sql`
   ```bash
   mysql -u username -p database_name < database_setup.sql
   ```

2. Hoặc truy cập file `check_database.php` trên trình duyệt để kiểm tra và tạo cơ sở dữ liệu tự động

### 5. Kiểm tra cài đặt

Truy cập trang web của bạn và đảm bảo mọi thứ hoạt động đúng:
- Trang chủ: `http://your-domain.com/`
- Trang quản trị: `http://your-domain.com/admin`
  - Đăng nhập với tài khoản mặc định:
    - Username: `admin`
    - Password: `admin123`

### 6. Bảo mật

Sau khi cài đặt thành công:
1. Thay đổi mật khẩu tài khoản admin mặc định
2. Cập nhật `AUTH_SECRET` trong `config.php`
3. Xóa hoặc giới hạn quyền truy cập vào các tệp cài đặt: `check_database.php`, `database_setup.sql`

## Cấu trúc thư mục

```
/
├── admin/           # Giao diện quản trị
├── api/             # API endpoints
├── assets/          # Tài nguyên tĩnh (CSS, JS, images)
├── cache/           # Thư mục cache (cần có quyền ghi)
├── includes/        # Các file PHP được include
├── templates/       # Template files
├── uploads/         # Thư mục upload (cần có quyền ghi)
├── .htaccess        # Cấu hình Apache
├── config.php       # Cấu hình ứng dụng
├── index.php        # Entry point
└── README.md        # File này
```

## Phát triển

### Môi trường phát triển

1. Clone repository và cài đặt theo hướng dẫn ở trên
2. Đặt `DEBUG_MODE` thành `true` trong `config.php`
3. Cấu hình môi trường phát triển của bạn

### Quy tắc code

- Tuân thủ PSR-12 cho code style
- Sử dụng PDO cho các truy vấn cơ sở dữ liệu
- Luôn sử dụng prepared statements
- Sử dụng `sanitize()` cho dữ liệu người dùng

## Hỗ trợ

Nếu bạn gặp vấn đề trong quá trình cài đặt hoặc sử dụng:
- Tạo issue trong repository
- Liên hệ qua email: contact@suncraft.com

## License

Bản quyền © 2023 Suncraft. Đã đăng ký bản quyền. 
   ```

2. Hoặc truy cập file `check_database.php` trên trình duyệt để kiểm tra và tạo cơ sở dữ liệu tự động

### 5. Kiểm tra cài đặt

Truy cập trang web của bạn và đảm bảo mọi thứ hoạt động đúng:
- Trang chủ: `http://your-domain.com/`
- Trang quản trị: `http://your-domain.com/admin`
  - Đăng nhập với tài khoản mặc định:
    - Username: `admin`
    - Password: `admin123`

### 6. Bảo mật

Sau khi cài đặt thành công:
1. Thay đổi mật khẩu tài khoản admin mặc định
2. Cập nhật `AUTH_SECRET` trong `config.php`
3. Xóa hoặc giới hạn quyền truy cập vào các tệp cài đặt: `check_database.php`, `database_setup.sql`

## Cấu trúc thư mục

```
/
├── admin/           # Giao diện quản trị
├── api/             # API endpoints
├── assets/          # Tài nguyên tĩnh (CSS, JS, images)
├── cache/           # Thư mục cache (cần có quyền ghi)
├── includes/        # Các file PHP được include
├── templates/       # Template files
├── uploads/         # Thư mục upload (cần có quyền ghi)
├── .htaccess        # Cấu hình Apache
├── config.php       # Cấu hình ứng dụng
├── index.php        # Entry point
└── README.md        # File này
```

## Phát triển

### Môi trường phát triển

1. Clone repository và cài đặt theo hướng dẫn ở trên
2. Đặt `DEBUG_MODE` thành `true` trong `config.php`
3. Cấu hình môi trường phát triển của bạn

### Quy tắc code

- Tuân thủ PSR-12 cho code style
- Sử dụng PDO cho các truy vấn cơ sở dữ liệu
- Luôn sử dụng prepared statements
- Sử dụng `sanitize()` cho dữ liệu người dùng

## Hỗ trợ

Nếu bạn gặp vấn đề trong quá trình cài đặt hoặc sử dụng:
- Tạo issue trong repository
- Liên hệ qua email: contact@suncraft.com

## License

Bản quyền © 2023 Suncraft. Đã đăng ký bản quyền. 
1. Trên trang admin.html, bạn sẽ thấy form "Tạo bài viết mới"
2. Điền các thông tin sau:
   - **Tiêu đề**: Tiêu đề của bài viết
   - **Nội dung**: Nội dung chi tiết của bài viết
   - **Danh mục**: Chọn danh mục phù hợp cho bài viết
   - **Hình ảnh bài viết**: Tải lên hình ảnh đại diện cho bài viết (tùy chọn)
   - **Video**: Tải lên video cho bài viết nếu có (tùy chọn)
3. Nhấn nút "Đăng bài" để lưu bài viết

## Quản lý bài viết đã có

1. Nhấn nút "Danh sách bài viết" ở góc trên bên phải để xem tất cả bài viết
2. Tại đây bạn có thể:
   - **Tìm kiếm bài viết**: Nhập từ khóa vào ô tìm kiếm
   - **Lọc theo danh mục**: Chọn danh mục từ dropdown
   - **Chỉnh sửa bài viết**: Nhấn biểu tượng bút chì bên cạnh bài viết
   - **Xóa bài viết**: Nhấn biểu tượng thùng rác bên cạnh bài viết

## Chỉnh sửa bài viết

1. Sau khi nhấn vào biểu tượng chỉnh sửa, form sẽ được điền với thông tin bài viết hiện tại
2. Thực hiện các thay đổi cần thiết
3. Nhấn "Cập nhật bài viết" để lưu thay đổi

## Các mẹo khi viết bài

1. **Tiêu đề** nên ngắn gọn, hấp dẫn và mô tả chính xác nội dung
2. **Slug** sẽ được tạo tự động từ tiêu đề, bạn có thể chỉnh sửa nếu muốn
3. **Nội dung** nên được trình bày rõ ràng, có cấu trúc
4. **Hình ảnh** nên có kích thước phù hợp, tối ưu cho web (khuyến nghị kích thước 1200x800px)
5. **Danh mục** giúp phân loại bài viết và giúp người dùng tìm kiếm dễ dàng hơn

## Cấu trúc bài viết hiệu quả

Một bài viết blog hiệu quả nên có cấu trúc sau:
1. **Mở đầu hấp dẫn**: Giới thiệu ngắn gọn về chủ đề
2. **Nội dung chính**: Chia thành các đoạn hoặc mục nhỏ với tiêu đề phụ
3. **Hình ảnh minh họa**: Kèm theo các hình ảnh liên quan
4. **Kết luận**: Tóm tắt hoặc kêu gọi hành động

## Xem bài viết trên trang blog

Sau khi tạo bài viết, bạn có thể xem bài viết của mình trên trang blog chính thức tại `/blog.html` 