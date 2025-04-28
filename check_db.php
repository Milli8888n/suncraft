<?php
// File kiểm tra cấu trúc cơ sở dữ liệu
require_once 'api/config.php';

echo "=== KIỂM TRA CƠ SỞ DỮ LIỆU ===\n";
echo "Thông tin cấu hình:\n";
echo "- USE_SQLITE: " . (USE_SQLITE ? "Có" : "Không") . "\n";
echo "- DB_HOST: " . DB_HOST . "\n";
echo "- DB_PORT: " . DB_PORT . "\n";
echo "- DB_NAME: " . DB_NAME . "\n";
echo "- DB_USER: " . DB_USER . "\n";
echo "- DB_PASS: " . (empty(DB_PASS) ? "(trống)" : "(đã cấu hình)") . "\n";

try {
    // Tạo kết nối PDO trực tiếp
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "\nKết nối cơ sở dữ liệu: THÀNH CÔNG\n";
    
    // Lấy danh sách bảng
    try {
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "\nDanh sách bảng:\n";
        if (count($tables) > 0) {
            foreach ($tables as $table) {
                echo "- " . $table . "\n";
                
                // Đếm số lượng bản ghi
                $count = $pdo->query("SELECT COUNT(*) FROM " . $table)->fetchColumn();
                echo "  Số bản ghi: " . $count . "\n";
            }
        } else {
            echo "Không có bảng nào trong cơ sở dữ liệu\n";
        }
    } catch (PDOException $e) {
        echo "Lỗi khi liệt kê bảng: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "\nLỗi kết nối cơ sở dữ liệu: " . $e->getMessage() . "\n";
}

// Kiểm tra file create_tables.php
echo "\n=== KIỂM TRA FILE create_tables.php ===\n";
if (file_exists('api/create_tables.php')) {
    echo "File create_tables.php tồn tại.\n";
    $content = file_get_contents('api/create_tables.php');
    
    // Tìm tên bảng trong file
    preg_match_all('/CREATE TABLE IF NOT EXISTS ([a-zA-Z_]+)/i', $content, $matches);
    
    if (!empty($matches[1])) {
        echo "Các bảng được định nghĩa trong file:\n";
        foreach ($matches[1] as $table) {
            echo "- " . $table . "\n";
        }
    }
} else {
    echo "File create_tables.php không tồn tại.\n";
}

// Kiểm tra file API nào đang gọi các bảng
echo "\n=== KIỂM TRA CÁC FILE API ===\n";
$apiDirectory = 'api/';
$apiFiles = scandir($apiDirectory);
echo "Danh sách file API:\n";
foreach ($apiFiles as $file) {
    if (is_file($apiDirectory . $file) && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        echo "- " . $file . "\n";
    }
}

echo "\n=== KIỂM TRA FILE LOG ===\n";
// Kiểm tra các file log phổ biến
$logFiles = [
    'C:/xampp/apache/logs/error.log',
    'C:/xampp/apache/logs/access.log',
    'C:/xampp/php/logs/php_error_log',
    './logs/error.log',
    './php_error.log',
    './error_log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        echo "File log tồn tại: " . $logFile . "\n";
        
        // Đọc các dòng cuối cùng của file log
        $lines = [];
        $fp = fopen($logFile, 'r');
        fseek($fp, -8192, SEEK_END); // Đọc 8KB cuối cùng
        $chunk = fread($fp, 8192);
        fclose($fp);
        
        $lines = explode("\n", $chunk);
        $lines = array_slice($lines, max(0, count($lines) - 20)); // 20 dòng cuối
        
        echo "Nội dung 20 dòng cuối:\n";
        foreach ($lines as $line) {
            if (strpos($line, 'error') !== false || strpos($line, 'stats.php') !== false) {
                echo $line . "\n";
            }
        }
    }
} 