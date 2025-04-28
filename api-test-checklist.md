# Danh sách kiểm tra API Suncraft

## API đã được cấu hình thành công
- [x] API `/stats` hoạt động và trả về dữ liệu thống kê
- [x] API `/products` hoạt động và trả về danh sách sản phẩm
- [ ] API `/categories` hoạt động với CRUD
- [ ] API `/posts` hoạt động với CRUD
- [ ] API Upload hình ảnh

## Kiểm tra trang quản trị (Adm)
Truy cập trang quản trị: `/suncraft/Adm/index.html` và kiểm tra các chức năng:

### Thống kê Dashboard
- [ ] Hiển thị đúng số lượng bài viết, danh mục, sản phẩm
- [ ] Hiển thị danh sách bài viết gần đây

### Quản lý danh mục
- [ ] Xem danh sách danh mục
- [ ] Thêm danh mục mới
- [ ] Sửa danh mục đã có
- [ ] Xóa danh mục

### Quản lý sản phẩm
- [ ] Xem danh sách sản phẩm
- [ ] Thêm sản phẩm mới
- [ ] Sửa sản phẩm đã có
- [ ] Xóa sản phẩm
- [ ] Upload hình ảnh sản phẩm

### Quản lý bài viết
- [ ] Xem danh sách bài viết
- [ ] Thêm bài viết mới
- [ ] Sửa bài viết đã có
- [ ] Xóa bài viết

## Kiểm tra trang chủ
- [ ] Trang chủ hiển thị sản phẩm từ API
- [ ] Trang sản phẩm hoạt động đúng
- [ ] Trang blog hiển thị bài viết từ API

## Ghi chú:
- Nếu gặp lỗi CORS, kiểm tra lại cấu hình trong file `.htaccess`
- Đảm bảo biến `USE_MOCK_API = false` trong `Adm/api-functions.js`
- Đảm bảo đường dẫn API đúng là `/suncraft/api` trong tất cả các file 