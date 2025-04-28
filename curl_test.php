<?php
// Đặt encoding để hiển thị đúng tiếng Việt
header('Content-Type: text/plain; charset=utf-8');

// Kiểm tra nếu cURL đã được kích hoạt
if (function_exists('curl_version')) {
    echo "cURL đã được kích hoạt!\n";
    $curl_info = curl_version();
    echo "Phiên bản cURL: " . $curl_info['version'] . "\n";
    echo "Phiên bản SSL: " . $curl_info['ssl_version'] . "\n";
} else {
    echo "cURL chưa được kích hoạt.\n";
}

// Thử nghiệm yêu cầu cURL đơn giản
$curl = curl_init("https://www.google.com");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Bỏ qua xác minh SSL (chỉ cho mục đích kiểm tra)
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Bỏ qua xác minh host
$response = curl_exec($curl);

if ($response === false) {
    echo "Lỗi cURL: " . curl_error($curl) . "\n";
} else {
    echo "Đã kết nối thành công đến google.com\n";
    echo "Độ dài phản hồi: " . strlen($response) . " bytes\n";
}

curl_close($curl);
?> 