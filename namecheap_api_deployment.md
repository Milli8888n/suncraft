# Hướng dẫn triển khai API lên Namecheap

## 1. Chuẩn bị

- Đảm bảo đã tải lên toàn bộ code của dự án lên Namecheap (theo hướng dẫn trong file `hướng_dẫn_cài_đặt_namecheap.md`)
- Đảm bảo đã tạo cơ sở dữ liệu và người dùng cơ sở dữ liệu trong cPanel

## 2. Cập nhật thông tin kết nối cơ sở dữ liệu

1. Mở file `api/config.php` và cập nhật các thông tin sau:
   ```php
   define('DB_HOST', 'localhost'); // Giữ nguyên
   define('DB_PORT', '3306'); // Namecheap thường sử dụng cổng mặc định 3306
   define('DB_NAME', 'username_suncraft_db'); // Thay username bằng username cPanel
   define('DB_USER', 'username_suncraft_user'); // Thay username bằng username cPanel
   define('DB_PASS', 'YourStrongPassword'); // Mật khẩu đã tạo cho người dùng cơ sở dữ liệu
   ```

2. Cập nhật cấu hình CORS để phù hợp với tên miền của bạn:
   ```php
   define('ALLOWED_ORIGINS', 'https://yourdomain.com,http://yourdomain.com,https://www.yourdomain.com,http://www.yourdomain.com');
   ```

3. Vô hiệu hóa hiển thị lỗi PHP trong môi trường production:
   ```php
   ini_set('display_errors', 0);
   error_reporting(E_ALL);
   ```

## 3. Kiểm tra và chỉnh sửa file .htaccess

1. Đảm bảo file `api/.htaccess` có chứa cấu hình đúng cho Namecheap:
   ```apache
   # Detect production environment
   RewriteCond %{HTTP_HOST} ^(www\.)?yourdomain\.com
   RewriteRule .* - [E=ENVIRONMENT:production]
   
   # Set RewriteBase cho production
   RewriteCond %{ENV:ENVIRONMENT} production
   RewriteRule .* - [E=REWRITE_BASE:/api/]
   ```

2. Đảm bảo cấu hình CORS phù hợp với tên miền của bạn:
   ```apache
   SetEnvIf Origin "^(https?://(www\.)?yourdomain\.com)$" CORS_ORIGIN=$1
   Header set Access-Control-Allow-Origin %{CORS_ORIGIN}e env=CORS_ORIGIN
   ```

## 4. Cập nhật quyền truy cập thư mục và file

1. Thư mục:
   ```
   chmod 755 /path/to/public_html/api
   chmod 755 /path/to/public_html/uploads
   ```

2. File PHP:
   ```
   chmod 644 /path/to/public_html/api/*.php
   ```

3. Đặc biệt đảm bảo file `config.php` không thể truy cập từ bên ngoài:
   ```
   chmod 600 /path/to/public_html/api/config.php
   ```

## 5. Nhập dữ liệu vào cơ sở dữ liệu

1. Truy cập phpMyAdmin từ cPanel
2. Chọn cơ sở dữ liệu đã tạo
3. Nhấp vào tab "Import"
4. Chọn file SQL (database_setup_namecheap.sql)
5. Nhấp vào "Go" để nhập dữ liệu

## 6. Kiểm tra kết nối API

1. Truy cập URL kiểm tra:
   ```
   https://yourdomain.com/api/namecheap_check.php
   ```

2. Đảm bảo tất cả các bước kiểm tra đều thành công

3. Kiểm tra API endpoint chính:
   ```
   https://yourdomain.com/api/
   ```

4. Kiểm tra API endpoint cụ thể:
   ```
   https://yourdomain.com/api/products
   https://yourdomain.com/api/categories
   https://yourdomain.com/api/posts
   ```

## 7. Xử lý sự cố thường gặp

### 7.1. Lỗi 500 Internal Server Error

- Kiểm tra file `.htaccess` (sai cú pháp)
- Kiểm tra quyền thư mục và file
- Kiểm tra logs trong cPanel (Error Log)

### 7.2. Lỗi kết nối cơ sở dữ liệu

- Xác minh thông tin kết nối trong `config.php`
- Đảm bảo người dùng cơ sở dữ liệu có quyền đúng
- Kiểm tra cơ sở dữ liệu đã được tạo
- Kiểm tra cổng MySQL (thường là 3306 trên Namecheap)

### 7.3. Lỗi CORS

- Kiểm tra cấu hình `ALLOWED_ORIGINS` trong `config.php`
- Kiểm tra cấu hình CORS trong `.htaccess`
- Đảm bảo request từ frontend chứa header Origin hợp lệ

### 7.4. Lỗi upload file

- Kiểm tra quyền thư mục `uploads`
- Kiểm tra giới hạn kích thước file trong `php.ini` và `.htaccess`
- Xác minh đường dẫn upload trong code

## 8. Tối ưu hóa và bảo mật

### 8.1. Kích hoạt cache

Thêm cấu hình cache vào `.htaccess`:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
</IfModule>
```

### 8.2. Bảo vệ thông tin nhạy cảm

Đảm bảo các file cấu hình không thể truy cập từ web:
```apache
<FilesMatch "^(config\.php|db\.php|.*\.log)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 8.3. Kích hoạt HTTPS

1. Cài đặt SSL certificate từ Let's Encrypt trong cPanel
2. Chuyển hướng HTTP sang HTTPS trong file `.htaccess` gốc:
```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 9. Kiểm tra cuối cùng

Sau khi hoàn tất triển khai, thực hiện kiểm tra cuối cùng:

1. Truy cập trang chủ website
2. Đăng nhập quản trị
3. Thêm/sửa/xóa sản phẩm hoặc bài viết
4. Kiểm tra tính năng upload hình ảnh
5. Xác minh hiển thị sản phẩm và bài viết trên frontend 