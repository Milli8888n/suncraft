<?php
// Kiểm tra tất cả các file trong thư mục API để tìm endpoint 'stats'
$api_dir = '/home/suncpsdo/public_html/suncraft/api';

echo "Tìm kiếm endpoint 'stats' trong thư mục API:\n\n";

// Nếu thư mục tồn tại
if (is_dir($api_dir)) {
    $files = scandir($api_dir);
    $found = false;
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $file_path = $api_dir . '/' . $file;
        
        // Nếu là file PHP
        if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($file_path);
            
            // Tìm kiếm từ khóa 'stats' hoặc các pattern liên quan đến endpoint
            if (strpos($content, 'stats') !== false) {
                echo "Tìm thấy từ khóa 'stats' trong file: $file\n";
                $found = true;
            }
        }
    }
    
    if (!$found) {
        echo "Không tìm thấy endpoint 'stats' trong bất kỳ file PHP nào trong thư mục API.";
    }
} else {
    echo "Thư mục API không tồn tại tại đường dẫn: $api_dir";
}
?> 