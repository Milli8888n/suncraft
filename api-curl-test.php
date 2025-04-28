<?php
/**
 * File kiểm tra kết nối API bằng cURL
 * 
 * Hướng dẫn sử dụng:
 * 1. Upload file này lên server trong thư mục public_html/suncraft
 * 2. Truy cập file qua URL: https://suncraft.com.vn/api-curl-test.php
 * 3. Xem kết quả kiểm tra kết nối API
 */

// Đặt header để hiển thị văn bản đúng cách
header('Content-Type: text/html; charset=utf-8');

// Danh sách các endpoint cần kiểm tra
$endpoints = [
    'stats' => 'https://suncraft.com.vn/api/stats',
    'test' => 'https://suncraft.com.vn/api/test.php',
    'categories' => 'https://suncraft.com.vn/api/categories', 
    'products' => 'https://suncraft.com.vn/api/products'
];

echo "<h1>Kiểm tra kết nối API bằng cURL</h1>";
echo "<pre>";

foreach ($endpoints as $name => $url) {
    echo "\nKiểm tra endpoint: $name ($url)\n";
    echo "--------------------------------\n";
    
    // Tạo cURL request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // Thực hiện request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    // Kiểm tra kết quả
    if ($response === false) {
        echo "LỖI: " . curl_error($ch) . "\n";
    } else {
        echo "Mã HTTP: $http_code\n";
        echo "Headers:\n$headers\n";
        
        // Chỉ hiển thị 200 ký tự đầu tiên của response body
        if (strlen($body) > 200) {
            $body = substr($body, 0, 200) . "... (còn nữa)";
        }
        
        echo "Body:\n$body\n";
    }
    
    curl_close($ch);
    echo "--------------------------------\n";
}

echo "</pre>";
echo "<p><strong>Gợi ý:</strong> Nếu bạn gặp lỗi 404, hãy kiểm tra các file trong thư mục API và cấu hình .htaccess</p>";
?> 