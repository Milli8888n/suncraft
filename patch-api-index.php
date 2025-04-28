<?php
/**
 * File hướng dẫn sửa code index.php
 */

echo "<h1>Sửa file index.php để thêm endpoint stats</h1>";
echo "<p>Mở file index.php trong thư mục API với editor và tìm đoạn code switch-case.</p>";
echo "<p>Thêm xử lý cho endpoint 'stats' như sau:</p>";

echo "<pre>";
echo htmlspecialchars('// Phân tích yêu cầu đến endpoint cụ thể
switch ($endpoint) {
    case \'categories\':
        require_once __DIR__ . \'/categories.php\';
        break;
    case \'products\':
        require_once __DIR__ . \'/products.php\';
        break;
    // Thêm endpoint stats ở đây
    case \'stats\':
        require_once __DIR__ . \'/stats.php\';
        break;
    // Thêm các endpoints khác nếu cần
    case \'posts\':
        require_once __DIR__ . \'/posts.php\';
        break;
    case \'images\':
        require_once __DIR__ . \'/images.php\';
        break;
    default:
        // Trả về 404 nếu không tìm thấy endpoint
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Endpoint không tồn tại"]);
        exit;
}');
echo "</pre>";

echo "<h2>Hướng dẫn thực hiện:</h2>";
echo "<ol>
    <li>Đăng nhập vào cPanel > File Manager</li>
    <li>Mở file `/home/suncpsdo/public_html/suncraft/api/index.php`</li>
    <li>Tìm đoạn code switch-case</li>
    <li>Thêm case cho 'stats' như trên</li>
    <li>Lưu file</li>
    <li>Kiểm tra lại bằng cách truy cập: https://suncraft.com.vn/api/stats</li>
</ol>";
?> 