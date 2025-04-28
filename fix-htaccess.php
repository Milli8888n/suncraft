<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kiểm tra xem có quyền ghi vào tệp .htaccess không
$main_htaccess = __DIR__ . '/.htaccess';
$api_htaccess = __DIR__ . '/api/.htaccess';

echo "<h1>Công cụ sửa tệp .htaccess</h1>";

// Kiểm tra sự tồn tại và quyền ghi của các tệp .htaccess
echo "<h2>Kiểm tra tệp .htaccess</h2>";
echo "<pre>";
foreach ([$main_htaccess => 'Tệp .htaccess chính', $api_htaccess => 'Tệp .htaccess API'] as $file => $desc) {
    echo "$desc: ";
    if (file_exists($file)) {
        echo "TỒN TẠI";
        echo " - Có thể đọc: " . (is_readable($file) ? "Có" : "Không");
        echo " - Có thể ghi: " . (is_writable($file) ? "Có" : "Không");
        echo " - Kích thước: " . filesize($file) . " bytes";
        
        // Tạo bản sao lưu
        if (is_readable($file)) {
            $backup_file = $file . '.bak.' . time();
            if (copy($file, $backup_file)) {
                echo " - Đã tạo bản sao lưu: " . basename($backup_file);
            } else {
                echo " - Không thể tạo bản sao lưu!";
            }
        }
    } else {
        echo "KHÔNG TỒN TẠI";
    }
    echo "\n";
}
echo "</pre>";

// Cấu hình .htaccess đơn giản cho thư mục gốc
$simple_main_htaccess = <<<EOT
# Cấu hình đơn giản cho Suncraft
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Cho phép API xử lý riêng
    RewriteRule ^api(/.*)?$ - [L]
    
    # Không áp dụng rewrite cho tệp/thư mục thực
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Điều hướng mọi request khác đến index.php
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>

# Bật CORS cơ bản
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
</IfModule>

# Cho phép PHP xử lý tệp .php
<FilesMatch "\.php$">
    SetHandler application/x-httpd-php
</FilesMatch>

# Ngăn chặn truy cập vào danh sách thư mục
Options -Indexes
EOT;

// Cấu hình .htaccess đơn giản cho thư mục API
$simple_api_htaccess = <<<EOT
# Cấu hình đơn giản cho API
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /api/
    
    # Cho phép truy cập trực tiếp các tệp thực
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Chuyển hướng tất cả request đến index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Bật CORS đơn giản
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
</IfModule>

# Cấu hình PHP
<IfModule mod_php7.c>
    php_flag display_errors Off
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
</IfModule>
EOT;

// Cập nhật tệp .htaccess
function update_htaccess($file, $content) {
    if (file_exists($file) && is_writable($file)) {
        if (file_put_contents($file, $content)) {
            return true;
        }
    }
    return false;
}

// Form cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Kết quả cập nhật</h2>";
    echo "<pre>";
    
    if (isset($_POST['update_main']) && $_POST['update_main'] === 'yes') {
        $result = update_htaccess($main_htaccess, $simple_main_htaccess);
        echo "Cập nhật tệp .htaccess chính: " . ($result ? "THÀNH CÔNG" : "THẤT BẠI") . "\n";
    }
    
    if (isset($_POST['update_api']) && $_POST['update_api'] === 'yes') {
        $result = update_htaccess($api_htaccess, $simple_api_htaccess);
        echo "Cập nhật tệp .htaccess API: " . ($result ? "THÀNH CÔNG" : "THẤT BẠI") . "\n";
    }
    
    echo "</pre>";
}

// Hiển thị form
echo "<h2>Cập nhật tệp .htaccess</h2>";
echo "<form method='post'>";
echo "<p><input type='checkbox' name='update_main' value='yes'> Cập nhật tệp .htaccess chính với cấu hình đơn giản</p>";
echo "<p><input type='checkbox' name='update_api' value='yes'> Cập nhật tệp .htaccess API với cấu hình đơn giản</p>";
echo "<p><input type='submit' value='Cập nhật ngay'></p>";
echo "</form>";

// Hiển thị nội dung tệp .htaccess hiện tại
echo "<h2>Nội dung tệp .htaccess hiện tại</h2>";

foreach ([$main_htaccess => 'Tệp .htaccess chính', $api_htaccess => 'Tệp .htaccess API'] as $file => $desc) {
    echo "<h3>$desc</h3>";
    echo "<pre style='max-height: 300px; overflow: auto; background-color: #f0f0f0; padding: 10px;'>";
    if (file_exists($file) && is_readable($file)) {
        echo htmlspecialchars(file_get_contents($file));
    } else {
        echo "Không thể đọc tệp";
    }
    echo "</pre>";
}

// Hiển thị cấu hình đề xuất
echo "<h2>Cấu hình .htaccess đề xuất</h2>";

echo "<h3>Tệp .htaccess chính (đơn giản)</h3>";
echo "<pre style='max-height: 300px; overflow: auto; background-color: #f0f0f0; padding: 10px;'>";
echo htmlspecialchars($simple_main_htaccess);
echo "</pre>";

echo "<h3>Tệp .htaccess API (đơn giản)</h3>";
echo "<pre style='max-height: 300px; overflow: auto; background-color: #f0f0f0; padding: 10px;'>";
echo htmlspecialchars($simple_api_htaccess);
echo "</pre>"; 