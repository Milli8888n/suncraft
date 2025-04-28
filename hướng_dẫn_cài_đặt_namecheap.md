# Hướng dẫn triển khai website lên Namecheap

## 1. Chuẩn bị tên miền và hosting Namecheap

- Đăng nhập vào tài khoản Namecheap của bạn
- Đảm bảo đã mua gói hosting có hỗ trợ PHP và MySQL
- Đảm bảo tên miền đã được liên kết với hosting

## 2. Truy cập cPanel

- Đăng nhập vào cPanel bằng thông tin Namecheap cung cấp
- URL thường có dạng: `https://cpanel.namecheap.com` hoặc `https://serverIP:2083`
- Sử dụng thông tin đăng nhập được Namecheap cung cấp qua email

## 3. Tạo cơ sở dữ liệu MySQL

1. Trong cPanel, tìm phần **Databases** và nhấp vào **MySQL Databases**
2. Tạo cơ sở dữ liệu mới:
   - Nhập tên cơ sở dữ liệu (ví dụ: `suncraft_db`)
   - Nhấp vào **Create Database**
3. Tạo người dùng cơ sở dữ liệu:
   - Quay lại trang MySQL Databases
   - Trong phần **MySQL Users**, nhập tên người dùng (ví dụ: `suncraft_user`)
   - Nhập mật khẩu an toàn và ghi nhớ lại (ví dụ: `StrongPassword123`)
   - Nhấp vào **Create User**
4. Liên kết người dùng với cơ sở dữ liệu:
   - Trong phần **Add User To Database**
   - Chọn người dùng và cơ sở dữ liệu vừa tạo
   - Nhấp vào **Add**
   - Trong trang quyền hạn, chọn **ALL PRIVILEGES**
   - Nhấp vào **Make Changes**

## 4. Tải lên tệp website

1. Trong cPanel, tìm phần **Files** và nhấp vào **File Manager**
2. Điều hướng đến thư mục `public_html` (hoặc `www` hoặc `htdocs`)
3. Nhấp vào **Upload** và tải lên tất cả tệp từ dự án Suncraft
4. Sau khi tải lên, đảm bảo phân quyền chính xác:
   - Thư mục: 755 (rwxr-xr-x)
   - Tệp: 644 (rw-r--r--)
   - Tệp PHP thực thi: 755 (rwxr-xr-x)

## 5. Cập nhật cấu hình kết nối cơ sở dữ liệu

1. Điều chỉnh tệp cấu hình kết nối:
   - Tìm tệp cấu hình chứa thông tin kết nối cơ sở dữ liệu (thường là `config.php` hoặc tương tự)
   - Chỉnh sửa các thông số kết nối:
     ```php
     $host = 'localhost';
     $dbname = 'username_suncraft_db'; // Namecheap thường thêm prefix username vào tên DB
     $username = 'username_suncraft_user'; // Thay username bằng tên người dùng cPanel của bạn
     $password = 'StrongPassword123'; // Mật khẩu bạn đã tạo
     ```

## 6. Nhập dữ liệu vào cơ sở dữ liệu

1. Trong cPanel, tìm phần **Databases** và nhấp vào **phpMyAdmin**
2. Chọn cơ sở dữ liệu đã tạo từ danh sách bên trái
3. Nhấp vào tab **Import**
4. Nhấp vào **Choose File** và chọn tệp SQL backup
5. Bảo đảm mã hóa là **UTF-8**
6. Nhấp vào **Go** để nhập dữ liệu

## 7. Kiểm tra website

1. Truy cập tên miền của bạn trong trình duyệt
2. Kiểm tra các chức năng chính:
   - Trang chủ hiển thị đúng
   - Xem sản phẩm hoạt động
   - Đăng nhập admin được
   - Thêm/sửa/xóa bài viết hoặc sản phẩm
   
## 8. Cấu hình HTTPS

1. Trong cPanel, tìm phần **Security** và nhấp vào **SSL/TLS Status**
2. Chọn tên miền của bạn
3. Nhấp vào **Install Certificate**
4. Chọn **Let's Encrypt** hoặc chứng chỉ mà Namecheap cung cấp
5. Làm theo hướng dẫn để hoàn tất quá trình

## 9. Cấu hình email (nếu cần)

1. Trong cPanel, tìm phần **Email** và nhấp vào **Email Accounts**
2. Nhấp vào **Create**
3. Nhập thông tin tài khoản email mới
4. Nhấp vào **Create Account**

## 10. Bảo mật và sao lưu

1. Cài đặt sao lưu tự động:
   - Trong cPanel, tìm **Backup** hoặc **Backup Wizard**
   - Cấu hình sao lưu định kỳ
   
2. Bảo mật website:
   - Đảm bảo script, plugin và CMS luôn được cập nhật
   - Sử dụng mật khẩu mạnh cho tất cả tài khoản
   - Xem xét cài đặt tường lửa hoặc dịch vụ bảo vệ từ Namecheap

## Xử lý sự cố

### Lỗi kết nối cơ sở dữ liệu
- Kiểm tra thông tin kết nối (host, tên người dùng, mật khẩu)
- Đảm bảo người dùng có quyền truy cập cơ sở dữ liệu
- Kiểm tra cơ sở dữ liệu đã được tạo và có dữ liệu

### Lỗi 500 Internal Server Error
- Kiểm tra file .htaccess
- Kiểm tra quyền truy cập tệp và thư mục
- Xem log lỗi PHP trong cPanel (Error Log)

### Lỗi hình ảnh không hiển thị
- Kiểm tra đường dẫn tới hình ảnh
- Đảm bảo thư mục hình ảnh có quyền truy cập đúng 

## 1. Chuẩn bị tên miền và hosting Namecheap

- Đăng nhập vào tài khoản Namecheap của bạn
- Đảm bảo đã mua gói hosting có hỗ trợ PHP và MySQL
- Đảm bảo tên miền đã được liên kết với hosting

## 2. Truy cập cPanel

- Đăng nhập vào cPanel bằng thông tin Namecheap cung cấp
- URL thường có dạng: `https://cpanel.namecheap.com` hoặc `https://serverIP:2083`
- Sử dụng thông tin đăng nhập được Namecheap cung cấp qua email

## 3. Tạo cơ sở dữ liệu MySQL

1. Trong cPanel, tìm phần **Databases** và nhấp vào **MySQL Databases**
2. Tạo cơ sở dữ liệu mới:
   - Nhập tên cơ sở dữ liệu (ví dụ: `suncraft_db`)
   - Nhấp vào **Create Database**
3. Tạo người dùng cơ sở dữ liệu:
   - Quay lại trang MySQL Databases
   - Trong phần **MySQL Users**, nhập tên người dùng (ví dụ: `suncraft_user`)
   - Nhập mật khẩu an toàn và ghi nhớ lại (ví dụ: `StrongPassword123`)
   - Nhấp vào **Create User**
4. Liên kết người dùng với cơ sở dữ liệu:
   - Trong phần **Add User To Database**
   - Chọn người dùng và cơ sở dữ liệu vừa tạo
   - Nhấp vào **Add**
   - Trong trang quyền hạn, chọn **ALL PRIVILEGES**
   - Nhấp vào **Make Changes**

## 4. Tải lên tệp website

1. Trong cPanel, tìm phần **Files** và nhấp vào **File Manager**
2. Điều hướng đến thư mục `public_html` (hoặc `www` hoặc `htdocs`)
3. Nhấp vào **Upload** và tải lên tất cả tệp từ dự án Suncraft
4. Sau khi tải lên, đảm bảo phân quyền chính xác:
   - Thư mục: 755 (rwxr-xr-x)
   - Tệp: 644 (rw-r--r--)
   - Tệp PHP thực thi: 755 (rwxr-xr-x)

## 5. Cập nhật cấu hình kết nối cơ sở dữ liệu

1. Điều chỉnh tệp cấu hình kết nối:
   - Tìm tệp cấu hình chứa thông tin kết nối cơ sở dữ liệu (thường là `config.php` hoặc tương tự)
   - Chỉnh sửa các thông số kết nối:
     ```php
     $host = 'localhost';
     $dbname = 'username_suncraft_db'; // Namecheap thường thêm prefix username vào tên DB
     $username = 'username_suncraft_user'; // Thay username bằng tên người dùng cPanel của bạn
     $password = 'StrongPassword123'; // Mật khẩu bạn đã tạo
     ```

## 6. Nhập dữ liệu vào cơ sở dữ liệu

1. Trong cPanel, tìm phần **Databases** và nhấp vào **phpMyAdmin**
2. Chọn cơ sở dữ liệu đã tạo từ danh sách bên trái
3. Nhấp vào tab **Import**
4. Nhấp vào **Choose File** và chọn tệp SQL backup
5. Bảo đảm mã hóa là **UTF-8**
6. Nhấp vào **Go** để nhập dữ liệu

## 7. Kiểm tra website

1. Truy cập tên miền của bạn trong trình duyệt
2. Kiểm tra các chức năng chính:
   - Trang chủ hiển thị đúng
   - Xem sản phẩm hoạt động
   - Đăng nhập admin được
   - Thêm/sửa/xóa bài viết hoặc sản phẩm
   
## 8. Cấu hình HTTPS

1. Trong cPanel, tìm phần **Security** và nhấp vào **SSL/TLS Status**
2. Chọn tên miền của bạn
3. Nhấp vào **Install Certificate**
4. Chọn **Let's Encrypt** hoặc chứng chỉ mà Namecheap cung cấp
5. Làm theo hướng dẫn để hoàn tất quá trình

## 9. Cấu hình email (nếu cần)

1. Trong cPanel, tìm phần **Email** và nhấp vào **Email Accounts**
2. Nhấp vào **Create**
3. Nhập thông tin tài khoản email mới
4. Nhấp vào **Create Account**

## 10. Bảo mật và sao lưu

1. Cài đặt sao lưu tự động:
   - Trong cPanel, tìm **Backup** hoặc **Backup Wizard**
   - Cấu hình sao lưu định kỳ
   
2. Bảo mật website:
   - Đảm bảo script, plugin và CMS luôn được cập nhật
   - Sử dụng mật khẩu mạnh cho tất cả tài khoản
   - Xem xét cài đặt tường lửa hoặc dịch vụ bảo vệ từ Namecheap

## Xử lý sự cố

### Lỗi kết nối cơ sở dữ liệu
- Kiểm tra thông tin kết nối (host, tên người dùng, mật khẩu)
- Đảm bảo người dùng có quyền truy cập cơ sở dữ liệu
- Kiểm tra cơ sở dữ liệu đã được tạo và có dữ liệu

### Lỗi 500 Internal Server Error
- Kiểm tra file .htaccess
- Kiểm tra quyền truy cập tệp và thư mục
- Xem log lỗi PHP trong cPanel (Error Log)

### Lỗi hình ảnh không hiển thị
- Kiểm tra đường dẫn tới hình ảnh
- Đảm bảo thư mục hình ảnh có quyền truy cập đúng 
 
 