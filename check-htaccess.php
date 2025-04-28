<?php
/**
 * Kiểm tra nội dung file .htaccess trong các thư mục quan trọng
 * 
 * Hướng dẫn sử dụng:
 * 1. Upload file này lên server trong thư mục public_html/suncraft
 * 2. Truy cập file qua URL: https://suncraft.com.vn/check-htaccess.php
 */

// Đặt header để hiển thị văn bản đúng cách
header('Content-Type: text/html; charset=utf-8');

// Danh sách các thư mục cần kiểm tra
$directories = [
    'Root' => '/home/suncpsdo/public_html',
    'Suncraft' => '/home/suncpsdo/public_html/suncraft',
    'API' => '/home/suncpsdo/public_html/suncraft/api'
];

echo "<h1>Kiểm tra các file .htaccess</h1>";

foreach ($directories as $name => $dir) {
    $htaccess_file = $dir . '/.htaccess';
    
    echo "<h2>$name ($dir)</h2>";
    echo "<pre>";
    
    if (file_exists($htaccess_file)) {
        $content = file_get_contents($htaccess_file);
        echo htmlspecialchars($content);
    } else {
        echo "File .htaccess không tồn tại trong thư mục này.";
    }
    
    echo "</pre>";
    echo "<hr>";
}

// Gợi ý cấu hình .htaccess cho API
echo "<h2>Đề xuất cấu hình .htaccess cho API</h2>";
echo "<p>Nếu bạn không có file .htaccess trong thư mục API hoặc nó không hoạt động đúng, hãy tạo/sửa file .htaccess với nội dung sau:</p>";
echo "<pre>";
echo htmlspecialchars('
# Bật module rewrite
RewriteEngine On

# Cấu hình cơ bản cho API
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^/]+)/?$ $1.php [L]

# Cho phép CORS nếu cần
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization"
</IfModule>

# Chuyển hướng 404 nếu file không tồn tại
ErrorDocument 404 /api/404.php
');
echo "</pre>";

// Kiểm tra các quyền của thư mục API
$api_dir = '/home/suncpsdo/public_html/suncraft/api';
echo "<h2>Kiểm tra quyền thư mục API</h2>";
echo "<pre>";

if (is_dir($api_dir)) {
    $perms = fileperms($api_dir);
    $perms_octal = substr(sprintf('%o', $perms), -4);
    echo "Quyền thư mục API: $perms_octal\n";
    echo "Chủ sở hữu: " . fileowner($api_dir) . "\n";
    echo "Nhóm: " . filegroup($api_dir) . "\n";
    
    // Liệt kê các file trong thư mục API
    echo "\nDanh sách các file trong thư mục API:\n";
    $files = scandir($api_dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $file_path = $api_dir . '/' . $file;
        $file_perms = substr(sprintf('%o', fileperms($file_path)), -4);
        echo "$file ($file_perms)\n";
    }
} else {
    echo "Không thể truy cập thư mục API.";
}

echo "</pre>";
?> 