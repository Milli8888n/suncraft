<?php
// Tạo file test API đơn giản
$api_dir = '/home/suncpsdo/public_html/suncraft/api';
$test_file = $api_dir . '/test.php';

$content = '<?php
header("Content-Type: application/json");

// Trả về dữ liệu JSON đơn giản
$data = [
    "status" => "success",
    "message" => "API test endpoint is working",
    "timestamp" => time()
];

echo json_encode($data);
?>';

// Kiểm tra nếu thư mục API tồn tại
if (is_dir($api_dir)) {
    // Tạo file test.php
    if (file_put_contents($test_file, $content)) {
        echo "Đã tạo file test API thành công tại: $test_file\n";
        echo "Bạn có thể kiểm tra API bằng cách truy cập: https://suncraft.com.vn/api/test";
    } else {
        echo "Không thể tạo file test API. Kiểm tra quyền ghi vào thư mục.";
    }
} else {
    echo "Thư mục API không tồn tại tại đường dẫn: $api_dir";
}
?> 