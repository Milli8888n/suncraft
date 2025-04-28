<?php
// File kiểm tra kết nối database
echo "<h1>Kiểm tra kết nối cơ sở dữ liệu</h1>";

// Thông tin kết nối
$host = 'localhost';
$port = '3306';
$dbname = 'suncpsdo_suncraft_db';
$username = 'suncpsdo_suncraft_user';
$password = 'StrongPassword123';

try {
    // Tạo kết nối
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $conn = new PDO($dsn, $username, $password, $options);
    
    echo "<div style='color: green; font-weight: bold;'>✅ Kết nối cơ sở dữ liệu thành công!</div>";
    
    // Kiểm tra các bảng trong database
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Danh sách bảng trong cơ sở dữ liệu:</h2>";
    echo "<ul>";
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            $countStmt = $conn->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "<li>$table - $count bản ghi</li>";
        }
    } else {
        echo "<li>Không tìm thấy bảng nào trong cơ sở dữ liệu.</li>";
    }
    
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Lỗi kết nối: " . $e->getMessage() . "</div>";
    
    echo "<h2>Thông tin cấu hình:</h2>";
    echo "<ul>";
    echo "<li>Host: $host</li>";
    echo "<li>Port: $port</li>";
    echo "<li>Database: $dbname</li>";
    echo "<li>Username: $username</li>";
    echo "<li>Password: " . str_repeat("*", strlen($password)) . "</li>";
    echo "</ul>";
    
    echo "<h2>Giải pháp có thể:</h2>";
    echo "<ol>";
    echo "<li>Kiểm tra lại tên database và username trên Namecheap</li>";
    echo "<li>Kiểm tra lại mật khẩu đã nhập</li>";
    echo "<li>Đảm bảo user có quyền truy cập vào database</li>";
    echo "<li>Kiểm tra xem database đã được tạo trên server chưa</li>";
    echo "</ol>";
}
?> 