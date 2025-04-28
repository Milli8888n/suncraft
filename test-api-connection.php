<?php
/**
 * Test kết nối đến API stats
 * Sử dụng file này để kiểm tra local xem có kết nối được đến server không
 */

header('Content-Type: text/html; charset=utf-8');

// URL API cần kiểm tra - có thể thay đổi thành URL thực tế của bạn
$apiUrl = 'https://suncraft.com.vn/suncraft/api/stats';

echo "<h1>Kiểm tra kết nối đến API Stats</h1>";

// Kiểm tra với curl
if (function_exists('curl_version')) {
    echo "<h2>Kiểm tra bằng cURL:</h2>";
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bỏ qua xác minh SSL chỉ để test
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false) {
        echo "<p style='color:red'>Lỗi: " . curl_error($ch) . "</p>";
    } else {
        echo "<p>Mã HTTP: " . $httpCode . "</p>";
        if ($httpCode == 200) {
            echo "<p style='color:green'>Kết nối thành công!</p>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        } else {
            echo "<p style='color:red'>Lỗi: HTTP code " . $httpCode . "</p>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
    }
    
    curl_close($ch);
} else {
    echo "<p style='color:red'>cURL không được kích hoạt. Không thể kiểm tra kết nối.</p>";
}

// Kiểm tra với file_get_contents
echo "<h2>Kiểm tra bằng file_get_contents:</h2>";

$context = stream_context_create([
    'http' => [
        'timeout' => 30,
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

try {
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response !== false) {
        echo "<p style='color:green'>Kết nối thành công!</p>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    } else {
        echo "<p style='color:red'>Không thể kết nối tới API.</p>";
        
        if (isset($http_response_header)) {
            echo "<p>HTTP Response Headers:</p>";
            echo "<pre>" . print_r($http_response_header, true) . "</pre>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Lỗi: " . $e->getMessage() . "</p>";
}

echo "<h2>Kiểm tra index.php trong API:</h2>";
echo "<p>Đảm bảo file index.php trong thư mục API có case xử lý cho 'stats':</p>";
echo "<pre>
switch (\$endpoint) {
    case 'categories':
        require_once __DIR__ . '/categories.php';
        break;
    case 'products':
        require_once __DIR__ . '/products.php';
        break;
    case 'stats':
        require_once __DIR__ . '/stats.php';
        break;
    // ...
}
</pre>";

echo "<h2>URL API hiện tại:</h2>";
echo "<p>$apiUrl</p>";
echo "<p>Nếu URL không đúng, hãy sửa lại \$apiUrl trong file này.</p>";
?> 