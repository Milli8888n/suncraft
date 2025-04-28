<?php
/**
 * File kiểm tra nội dung index.php trong thư mục API
 * 
 * Hướng dẫn sử dụng:
 * 1. Upload file này lên thư mục public_html/suncraft
 * 2. Truy cập: https://suncraft.com.vn/check-api-index.php
 */

// Đặt header để hiển thị văn bản đúng cách
header('Content-Type: text/html; charset=utf-8');

// Đường dẫn đến file index.php của API
$api_index_path = '/home/suncpsdo/public_html/suncraft/api/index.php';

echo "<h1>Kiểm tra file index.php của API</h1>";

if (file_exists($api_index_path)) {
    $content = file_get_contents($api_index_path);
    
    echo "<h2>Nội dung file index.php</h2>";
    echo "<pre>";
    echo htmlspecialchars($content);
    echo "</pre>";
    
    // Tìm kiếm các đoạn code quan trọng
    echo "<h2>Phân tích router API</h2>";
    
    $has_router = strpos($content, 'router') !== false || strpos($content, 'route') !== false;
    $has_stats = strpos($content, 'stats') !== false;
    $includes_files = preg_match_all('/include|require/', $content, $matches);
    
    echo "<ul>";
    echo "<li>Có router/routing: " . ($has_router ? "Có" : "Không") . "</li>";
    echo "<li>Có xử lý endpoint stats: " . ($has_stats ? "Có" : "Không") . "</li>";
    echo "<li>Số lệnh include/require: " . $includes_files . "</li>";
    echo "</ul>";
    
    // Kiểm tra file router.php nếu có
    $router_path = '/home/suncpsdo/public_html/suncraft/api/router.php';
    if (file_exists($router_path)) {
        echo "<h2>Nội dung file router.php</h2>";
        echo "<pre>";
        echo htmlspecialchars(file_get_contents($router_path));
        echo "</pre>";
    }
} else {
    echo "<p>File index.php không tồn tại tại đường dẫn: $api_index_path</p>";
}

// Gợi ý sửa lỗi
echo "<h2>Gợi ý khắc phục lỗi API 404</h2>";
echo "<p>Dựa trên cấu hình .htaccess hiện tại, mọi request tới /api/ đều chuyển hướng tới index.php. Để khắc phục, có thể:</p>";

echo "<ol>";
echo "<li>Kiểm tra xem index.php có đang xử lý các endpoint API đúng cách không</li>";
echo "<li>Sửa file .htaccess trong thư mục API để chuyển hướng trực tiếp tới file tương ứng:</li>";
echo "</ol>";

echo "<pre>";
echo htmlspecialchars('
# Sửa file .htaccess trong thư mục API
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /suncraft/api/
    
    # Nếu file hoặc thư mục tồn tại, sử dụng trực tiếp
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]
    
    # Chuyển hướng trực tiếp từ /stats đến stats.php
    RewriteRule ^stats$ stats.php [L,QSA]
    
    # Tương tự cho các endpoint khác
    RewriteRule ^categories$ categories.php [L,QSA]
    RewriteRule ^products$ products.php [L,QSA]
    
    # Nếu không khớp với các rule trên, chuyển tới index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
');
echo "</pre>";

echo "<p>Hoặc, tạo file stats.php đơn giản để kiểm tra:</p>";
echo "<pre>";
echo htmlspecialchars('
<?php
// File: stats.php - Upload vào thư mục API
header("Content-Type: application/json");

// Dữ liệu JSON mẫu
$data = [
    "status" => "success",
    "stats" => [
        "total_products" => 100,
        "total_categories" => 20,
        "total_users" => 50
    ]
];

echo json_encode($data);
?>
');
echo "</pre>";
?> 