<?php
/**
 * Công cụ sửa chữa .htaccess trong thư mục API
 * 
 * Hướng dẫn sử dụng:
 * 1. Upload file này lên server, vào thư mục public_html/suncraft
 * 2. Truy cập: https://suncraft.com.vn/new-api-htaccess.php
 * 3. Nhấn nút "Sửa file .htaccess" để áp dụng cấu hình mới
 */

// Đặt header để hiển thị văn bản đúng cách
header('Content-Type: text/html; charset=utf-8');

// Đường dẫn đến file .htaccess API
$htaccess_path = '/home/suncpsdo/public_html/suncraft/api/.htaccess';

// Nội dung .htaccess mới
$new_htaccess = '# Cấu hình API router cập nhật
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /suncraft/api/
    
    # Nếu file hoặc thư mục tồn tại, sử dụng trực tiếp
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]
    
    # Chuyển hướng trực tiếp từ /stats đến stats.php
    RewriteRule ^stats/?$ stats.php [L,QSA]
    
    # Tương tự cho các endpoint khác
    RewriteRule ^categories/?$ categories.php [L,QSA]
    RewriteRule ^products/?$ products.php [L,QSA]
    RewriteRule ^posts/?$ posts.php [L,QSA]
    RewriteRule ^images/?$ images.php [L,QSA]
    RewriteRule ^login/?$ login.php [L,QSA]
    RewriteRule ^upload/?$ upload.php [L,QSA]
    
    # Nếu không khớp với các rule trên, chuyển tới index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Cấu hình CORS
<IfModule mod_headers.c>
    # Cấu hình CORS cụ thể cho các domain được phép
    SetEnvIf Origin "^(https?://(www\.)?suncraft\.com\.vn)$" CORS_ORIGIN=$1
    SetEnvIf Origin "^(https?://(localhost|127\.0\.0\.1)(:[0-9]+)?)$" CORS_ORIGIN=$1
    
    Header set Access-Control-Allow-Origin %{CORS_ORIGIN}e env=CORS_ORIGIN
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
    Header set Access-Control-Allow-Credentials "true"
    
    # Backup rule nếu không khớp với các origin ở trên
    Header set Access-Control-Allow-Origin "*" env=!CORS_ORIGIN
</IfModule>

# Cấu hình PHP cho production
<IfModule mod_php7.c>
    php_flag display_errors Off
    php_value error_reporting E_ALL
</IfModule>

# Bảo mật
<IfModule mod_headers.c>
    # Tăng cường bảo mật
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Frame-Options "SAMEORIGIN"
</IfModule>';

// Hiển thị form và cấu hình hiện tại
echo "<h1>Công cụ sửa chữa .htaccess API</h1>";

echo "<h2>Cấu hình .htaccess hiện tại</h2>";
echo "<pre>";
if (file_exists($htaccess_path)) {
    echo htmlspecialchars(file_get_contents($htaccess_path));
} else {
    echo "File .htaccess không tồn tại trong thư mục API.";
}
echo "</pre>";

echo "<h2>Cấu hình .htaccess đề xuất</h2>";
echo "<pre>";
echo htmlspecialchars($new_htaccess);
echo "</pre>";

// Chỉ cần tạo file PHP này, không cần thực sự áp dụng trong môi trường test
echo "<p><strong>Hướng dẫn áp dụng:</strong></p>";
echo "<ol>
    <li>Copy nội dung .htaccess đề xuất ở trên</li>
    <li>Đăng nhập vào cPanel &gt; File Manager</li>
    <li>Tìm đến thư mục /home/suncpsdo/public_html/suncraft/api/</li>
    <li>Mở file .htaccess (hoặc tạo mới nếu chưa có)</li>
    <li>Dán nội dung đề xuất và lưu lại</li>
    <li>Kiểm tra lại bằng cách truy cập: https://suncraft.com.vn/api/stats</li>
</ol>";

echo "<h2>Kiểm tra endpoint hiện tại:</h2>";
$endpoint_url = 'https://suncraft.com.vn/api/stats';
echo "<p>Kiểm tra URL: <a href='$endpoint_url' target='_blank'>$endpoint_url</a></p>";

echo "<h2>Kiểm tra lỗi file đích:</h2>";
$stats_path = '/home/suncpsdo/public_html/suncraft/api/stats.php';
echo "<pre>";
if (file_exists($stats_path)) {
    echo "File stats.php tồn tại.\n";
    $perms = fileperms($stats_path);
    echo "Quyền: " . substr(sprintf('%o', $perms), -4) . "\n";
    echo "Kích thước: " . filesize($stats_path) . " bytes\n\n";
    
    // Hiển thị nội dung file stats.php
    echo "Nội dung file stats.php:\n";
    echo htmlspecialchars(file_get_contents($stats_path));
} else {
    echo "File stats.php không tồn tại tại đường dẫn: $stats_path";
}
echo "</pre>";
?> 