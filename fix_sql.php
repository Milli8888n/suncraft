<?php
/**
 * Script điều chỉnh file SQL cho môi trường Namecheap
 * Chuyển đổi các tham chiếu từ 'suncraft_db' sang 'suncpsdo_suncraft_db'
 */

// Cấu hình
$input_file = 'database_setup.sql';
$output_file = 'database_setup_namecheap.sql';
$original_db = 'suncraft_db';
$namecheap_db = 'suncpsdo_suncraft_db';

// Header HTML
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Điều chỉnh file SQL cho Namecheap</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }
        code { font-family: monospace; }
    </style>
</head>
<body>
    <h1>Điều chỉnh file SQL cho Namecheap</h1>";

// Kiểm tra file đầu vào
if (!file_exists($input_file)) {
    echo "<p class='error'>❌ Không tìm thấy file $input_file. Vui lòng đảm bảo file này nằm trong cùng thư mục với script.</p>";
    exit;
}

// Đọc nội dung file SQL
$sql_content = file_get_contents($input_file);
if (!$sql_content) {
    echo "<p class='error'>❌ Không thể đọc nội dung file $input_file.</p>";
    exit;
}

// Thay đổi các tham chiếu database
$modified_sql = str_replace(
    ["CREATE DATABASE IF NOT EXISTS `$original_db`", "USE `$original_db`"],
    ["CREATE DATABASE IF NOT EXISTS `$namecheap_db`", "USE `$namecheap_db`"],
    $sql_content
);

// Điều chỉnh các câu lệnh khác liên quan đến database
$pattern = "/`$original_db`\./";
$replacement = "`$namecheap_db`.";
$modified_sql = preg_replace($pattern, $replacement, $modified_sql);

// Xóa dòng CREATE DATABASE vì người dùng Namecheap thường không có quyền này
$modified_sql = preg_replace("/CREATE DATABASE IF NOT EXISTS.+?;/", "-- CREATE DATABASE đã bị xóa - database đã được tạo trong cPanel", $modified_sql);

// Ghi nội dung đã sửa vào file mới
if (file_put_contents($output_file, $modified_sql)) {
    echo "<p class='success'>✅ Đã tạo file SQL mới: $output_file</p>";
    
    echo "<h2>Các thay đổi đã thực hiện:</h2>";
    echo "<ul>";
    echo "<li>Thay đổi tên database từ <code>$original_db</code> thành <code>$namecheap_db</code></li>";
    echo "<li>Xóa câu lệnh CREATE DATABASE (không cần thiết vì database đã được tạo trong cPanel)</li>";
    echo "<li>Điều chỉnh các tham chiếu database trong các câu lệnh</li>";
    echo "</ul>";
    
    echo "<h2>Hướng dẫn sử dụng:</h2>";
    echo "<ol>";
    echo "<li>Tải file <strong>$output_file</strong> về máy (nhấp chuột phải vào <a href='$output_file'>đây</a> và chọn 'Lưu liên kết thành...')</li>";
    echo "<li>Vào phpMyAdmin của cPanel và chọn database <code>$namecheap_db</code></li>";
    echo "<li>Chọn tab 'Import' và tải lên file đã tải về</li>";
    echo "<li>Đảm bảo bộ ký tự file là 'utf8mb4'</li>";
    echo "<li>Nhấp vào 'Go' để nhập dữ liệu</li>";
    echo "</ol>";
    
    // Tạo liên kết tải xuống
    echo "<p><a href='$output_file' download style='display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Tải xuống file SQL đã sửa</a></p>";
    
} else {
    echo "<p class='error'>❌ Không thể ghi file $output_file. Vui lòng kiểm tra quyền ghi.</p>";
}

// Tạo SQL trực tiếp để có thể sao chép và dán
echo "<h2>Hoặc sao chép SQL dưới đây và dán trực tiếp vào phpMyAdmin:</h2>";
echo "<pre><code>" . htmlspecialchars($modified_sql) . "</code></pre>";

echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='check_import.php'>Kiểm tra cơ sở dữ liệu</a></p>";
echo "</body></html>";
?> 
/**
 * Script điều chỉnh file SQL cho môi trường Namecheap
 * Chuyển đổi các tham chiếu từ 'suncraft_db' sang 'suncpsdo_suncraft_db'
 */

// Cấu hình
$input_file = 'database_setup.sql';
$output_file = 'database_setup_namecheap.sql';
$original_db = 'suncraft_db';
$namecheap_db = 'suncpsdo_suncraft_db';

// Header HTML
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Điều chỉnh file SQL cho Namecheap</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }
        code { font-family: monospace; }
    </style>
</head>
<body>
    <h1>Điều chỉnh file SQL cho Namecheap</h1>";

// Kiểm tra file đầu vào
if (!file_exists($input_file)) {
    echo "<p class='error'>❌ Không tìm thấy file $input_file. Vui lòng đảm bảo file này nằm trong cùng thư mục với script.</p>";
    exit;
}

// Đọc nội dung file SQL
$sql_content = file_get_contents($input_file);
if (!$sql_content) {
    echo "<p class='error'>❌ Không thể đọc nội dung file $input_file.</p>";
    exit;
}

// Thay đổi các tham chiếu database
$modified_sql = str_replace(
    ["CREATE DATABASE IF NOT EXISTS `$original_db`", "USE `$original_db`"],
    ["CREATE DATABASE IF NOT EXISTS `$namecheap_db`", "USE `$namecheap_db`"],
    $sql_content
);

// Điều chỉnh các câu lệnh khác liên quan đến database
$pattern = "/`$original_db`\./";
$replacement = "`$namecheap_db`.";
$modified_sql = preg_replace($pattern, $replacement, $modified_sql);

// Xóa dòng CREATE DATABASE vì người dùng Namecheap thường không có quyền này
$modified_sql = preg_replace("/CREATE DATABASE IF NOT EXISTS.+?;/", "-- CREATE DATABASE đã bị xóa - database đã được tạo trong cPanel", $modified_sql);

// Ghi nội dung đã sửa vào file mới
if (file_put_contents($output_file, $modified_sql)) {
    echo "<p class='success'>✅ Đã tạo file SQL mới: $output_file</p>";
    
    echo "<h2>Các thay đổi đã thực hiện:</h2>";
    echo "<ul>";
    echo "<li>Thay đổi tên database từ <code>$original_db</code> thành <code>$namecheap_db</code></li>";
    echo "<li>Xóa câu lệnh CREATE DATABASE (không cần thiết vì database đã được tạo trong cPanel)</li>";
    echo "<li>Điều chỉnh các tham chiếu database trong các câu lệnh</li>";
    echo "</ul>";
    
    echo "<h2>Hướng dẫn sử dụng:</h2>";
    echo "<ol>";
    echo "<li>Tải file <strong>$output_file</strong> về máy (nhấp chuột phải vào <a href='$output_file'>đây</a> và chọn 'Lưu liên kết thành...')</li>";
    echo "<li>Vào phpMyAdmin của cPanel và chọn database <code>$namecheap_db</code></li>";
    echo "<li>Chọn tab 'Import' và tải lên file đã tải về</li>";
    echo "<li>Đảm bảo bộ ký tự file là 'utf8mb4'</li>";
    echo "<li>Nhấp vào 'Go' để nhập dữ liệu</li>";
    echo "</ol>";
    
    // Tạo liên kết tải xuống
    echo "<p><a href='$output_file' download style='display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Tải xuống file SQL đã sửa</a></p>";
    
} else {
    echo "<p class='error'>❌ Không thể ghi file $output_file. Vui lòng kiểm tra quyền ghi.</p>";
}

// Tạo SQL trực tiếp để có thể sao chép và dán
echo "<h2>Hoặc sao chép SQL dưới đây và dán trực tiếp vào phpMyAdmin:</h2>";
echo "<pre><code>" . htmlspecialchars($modified_sql) . "</code></pre>";

echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='check_import.php'>Kiểm tra cơ sở dữ liệu</a></p>";
echo "</body></html>";
?> 
 
 