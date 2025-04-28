<?php
// Hiển thị tất cả lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kiểm tra thông tin cấu hình PHP
echo "<h2>Kiểm tra cấu hình PHP</h2>";
echo "<p>PHP version: " . phpversion() . "</p>";
echo "<p>Post max size: " . ini_get('post_max_size') . "</p>";
echo "<p>Upload max filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>Memory limit: " . ini_get('memory_limit') . "</p>";
echo "<p>Max execution time: " . ini_get('max_execution_time') . " seconds</p>";

// Kiểm tra thư mục uploads
echo "<h2>Kiểm tra thư mục uploads</h2>";
$uploadsDir = __DIR__ . '/uploads';
echo "<p>Đường dẫn thư mục uploads: $uploadsDir</p>";

if (file_exists($uploadsDir)) {
    echo "<p style='color:green'>✓ Thư mục uploads tồn tại</p>";
    
    // Kiểm tra quyền truy cập
    if (is_readable($uploadsDir)) {
        echo "<p style='color:green'>✓ Thư mục uploads có quyền đọc</p>";
    } else {
        echo "<p style='color:red'>✗ Thư mục uploads không có quyền đọc</p>";
    }
    
    if (is_writable($uploadsDir)) {
        echo "<p style='color:green'>✓ Thư mục uploads có quyền ghi</p>";
    } else {
        echo "<p style='color:red'>✗ Thư mục uploads không có quyền ghi</p>";
    }
    
    // Liệt kê các file trong thư mục uploads
    echo "<h3>Các file trong thư mục uploads:</h3>";
    $files = scandir($uploadsDir);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>✗ Thư mục uploads không tồn tại</p>";
    
    // Thử tạo thư mục
    echo "<p>Đang thử tạo thư mục uploads...</p>";
    if (mkdir($uploadsDir, 0755, true)) {
        echo "<p style='color:green'>✓ Đã tạo thư mục uploads thành công</p>";
    } else {
        echo "<p style='color:red'>✗ Không thể tạo thư mục uploads</p>";
    }
}

// Form để test upload
echo "<h2>Kiểm tra chức năng upload</h2>";
echo "<form action='/api/upload' method='POST' enctype='multipart/form-data'>";
echo "<p><input type='file' name='file'></p>";
echo "<p><input type='submit' value='Upload'></p>";
echo "</form>";

// Hiển thị thông tin SERVER và REQUEST
echo "<h2>Thông tin SERVER</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
?> 