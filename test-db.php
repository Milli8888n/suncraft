<?php
// Hiển thị tất cả lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/api/config.php';
require_once __DIR__ . '/api/db.php';

try {
    // Kiểm tra thư mục uploads
    if (!file_exists(UPLOAD_DIR)) {
        echo "Thư mục uploads không tồn tại. Đang tạo...<br>";
        mkdir(UPLOAD_DIR, 0755, true);
        echo "Đã tạo thư mục uploads.<br>";
    } else {
        echo "Thư mục uploads đã tồn tại.<br>";
    }
    
    // Kiểm tra kết nối MySQL
    echo "Đang kết nối MySQL trên " . DB_HOST . ":" . DB_PORT . "...<br>";
    $db = Database::getInstance();
    
    // Thử lấy dữ liệu từ bảng posts (số nhiều thay vì post)
    echo "Đang thử lấy dữ liệu từ bảng posts...<br>";
    $posts = $db->fetchAll("SELECT * FROM posts LIMIT 5");
    
    if ($posts) {
        echo "Kết nối thành công! Số lượng bài viết: " . count($posts) . "<br>";
    } else {
        echo "Kết nối thành công nhưng không tìm thấy bài viết nào.<br>";
    }
    
    // Thử lấy dữ liệu từ bảng categories (số nhiều thay vì category)
    echo "Đang thử lấy dữ liệu từ bảng categories...<br>";
    $categories = $db->fetchAll("SELECT * FROM categories LIMIT 5");
    
    if ($categories) {
        echo "Kết nối thành công! Số lượng danh mục: " . count($categories) . "<br>";
    } else {
        echo "Kết nối thành công nhưng không tìm thấy danh mục nào.<br>";
    }
    
    // Kiểm tra bảng products (số nhiều thay vì product)
    echo "Đang thử lấy dữ liệu từ bảng products...<br>";
    $products = $db->fetchAll("SELECT * FROM products LIMIT 5");
    
    if ($products) {
        echo "Kết nối thành công! Số lượng sản phẩm: " . count($products) . "<br>";
    } else {
        echo "Kết nối thành công nhưng không tìm thấy sản phẩm nào.<br>";
    }
    
} catch (Exception $e) {
    echo "Có lỗi xảy ra: " . $e->getMessage() . "<br>";
} 