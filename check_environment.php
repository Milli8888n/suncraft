<?php
echo "===========================================\n";
echo "KIỂM TRA MÔI TRƯỜNG PHP & MYSQL\n";
echo "===========================================\n\n";

// Kiểm tra phiên bản PHP
echo "PHP Version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    echo "❌ PHP phiên bản 7.0 trở lên là yêu cầu bắt buộc.\n";
} else {
    echo "✅ Phiên bản PHP đã đáp ứng yêu cầu.\n";
}

echo "\n";

// Kiểm tra các extension bắt buộc
$required_extensions = [
    'pdo',
    'pdo_mysql',
    'mysqli',
    'json',
    'mbstring',
    'gd',
    'curl',
    'zip'
];

echo "Kiểm tra các extension cần thiết:\n";
echo "-------------------------------------------\n";
$all_extensions_ok = true;

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ {$ext}: Đã cài đặt\n";
    } else {
        echo "❌ {$ext}: Chưa cài đặt (Bắt buộc)\n";
        $all_extensions_ok = false;
    }
}

echo "\n";

// Kiểm tra MySQL
echo "Kiểm tra kết nối MySQL:\n";
echo "-------------------------------------------\n";

$host = 'localhost';
$port = '3307';
$username = 'suncraft_user';
$password = 'StrongPassword123';
$dbname = 'suncraft_db';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối MySQL thành công\n";
    
    // Lấy thông tin MySQL server
    $server_info = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
    echo "   - MySQL Version: $server_info\n";
    
    // Kiểm tra các bảng
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "   - Số lượng bảng: " . count($tables) . "\n";
    
} catch (PDOException $e) {
    echo "❌ Kết nối MySQL thất bại: " . $e->getMessage() . "\n";
    echo "   Gợi ý: Kiểm tra thông tin kết nối:\n";
    echo "   - MySQL chạy trên cổng: $port?\n";
    echo "   - User '$username' đã được tạo?\n";
    echo "   - Database '$dbname' đã được tạo?\n";
    echo "   - Kiểm tra password\n";
}

echo "\n";

// Kiểm tra quyền ghi file
echo "Kiểm tra quyền ghi file:\n";
echo "-------------------------------------------\n";
$upload_dir = __DIR__ . '/uploads';

// Tạo thư mục nếu chưa tồn tại
if (!file_exists($upload_dir)) {
    if (mkdir($upload_dir, 0755, true)) {
        echo "✅ Đã tạo thư mục uploads\n";
    } else {
        echo "❌ Không thể tạo thư mục uploads\n";
    }
} 

// Kiểm tra quyền ghi
if (is_writable($upload_dir)) {
    echo "✅ Thư mục uploads có quyền ghi\n";
} else {
    echo "❌ Thư mục uploads không có quyền ghi\n";
}

// Kiểm tra thư mục backup
$backup_dir = __DIR__ . '/suncraft_backup';
if (!file_exists($backup_dir)) {
    echo "❌ Thư mục backup không tồn tại: $backup_dir\n";
    echo "   Hãy tạo thư mục và đặt file SQL backup vào đó\n";
} else {
    echo "✅ Thư mục backup tồn tại\n";
    $sql_files = glob($backup_dir . '/*.sql');
    if (empty($sql_files)) {
        echo "❌ Không tìm thấy file SQL backup trong thư mục\n";
    } else {
        echo "✅ Đã tìm thấy " . count($sql_files) . " file SQL backup\n";
    }
}

echo "\n===========================================\n";
if ($all_extensions_ok) {
    echo "✅ MÔI TRƯỜNG ĐÃ SẴN SÀNG\n";
} else {
    echo "❌ MỘT SỐ YÊU CẦU CHƯA ĐƯỢC ĐÁP ỨNG\n";
}
echo "===========================================\n";

echo "\nCác bước tiếp theo:\n";
echo "1. Tạo thư mục 'suncraft_backup' và đặt file SQL backup vào đó\n";
echo "2. Chạy 'php test_db_connection.php' để kiểm tra kết nối database\n";
echo "3. Chạy 'php import_database.php' để nhập dữ liệu từ file backup\n";
?> 
echo "===========================================\n";
echo "KIỂM TRA MÔI TRƯỜNG PHP & MYSQL\n";
echo "===========================================\n\n";

// Kiểm tra phiên bản PHP
echo "PHP Version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    echo "❌ PHP phiên bản 7.0 trở lên là yêu cầu bắt buộc.\n";
} else {
    echo "✅ Phiên bản PHP đã đáp ứng yêu cầu.\n";
}

echo "\n";

// Kiểm tra các extension bắt buộc
$required_extensions = [
    'pdo',
    'pdo_mysql',
    'mysqli',
    'json',
    'mbstring',
    'gd',
    'curl',
    'zip'
];

echo "Kiểm tra các extension cần thiết:\n";
echo "-------------------------------------------\n";
$all_extensions_ok = true;

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ {$ext}: Đã cài đặt\n";
    } else {
        echo "❌ {$ext}: Chưa cài đặt (Bắt buộc)\n";
        $all_extensions_ok = false;
    }
}

echo "\n";

// Kiểm tra MySQL
echo "Kiểm tra kết nối MySQL:\n";
echo "-------------------------------------------\n";

$host = 'localhost';
$port = '3307';
$username = 'suncraft_user';
$password = 'StrongPassword123';
$dbname = 'suncraft_db';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối MySQL thành công\n";
    
    // Lấy thông tin MySQL server
    $server_info = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
    echo "   - MySQL Version: $server_info\n";
    
    // Kiểm tra các bảng
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "   - Số lượng bảng: " . count($tables) . "\n";
    
} catch (PDOException $e) {
    echo "❌ Kết nối MySQL thất bại: " . $e->getMessage() . "\n";
    echo "   Gợi ý: Kiểm tra thông tin kết nối:\n";
    echo "   - MySQL chạy trên cổng: $port?\n";
    echo "   - User '$username' đã được tạo?\n";
    echo "   - Database '$dbname' đã được tạo?\n";
    echo "   - Kiểm tra password\n";
}

echo "\n";

// Kiểm tra quyền ghi file
echo "Kiểm tra quyền ghi file:\n";
echo "-------------------------------------------\n";
$upload_dir = __DIR__ . '/uploads';

// Tạo thư mục nếu chưa tồn tại
if (!file_exists($upload_dir)) {
    if (mkdir($upload_dir, 0755, true)) {
        echo "✅ Đã tạo thư mục uploads\n";
    } else {
        echo "❌ Không thể tạo thư mục uploads\n";
    }
} 

// Kiểm tra quyền ghi
if (is_writable($upload_dir)) {
    echo "✅ Thư mục uploads có quyền ghi\n";
} else {
    echo "❌ Thư mục uploads không có quyền ghi\n";
}

// Kiểm tra thư mục backup
$backup_dir = __DIR__ . '/suncraft_backup';
if (!file_exists($backup_dir)) {
    echo "❌ Thư mục backup không tồn tại: $backup_dir\n";
    echo "   Hãy tạo thư mục và đặt file SQL backup vào đó\n";
} else {
    echo "✅ Thư mục backup tồn tại\n";
    $sql_files = glob($backup_dir . '/*.sql');
    if (empty($sql_files)) {
        echo "❌ Không tìm thấy file SQL backup trong thư mục\n";
    } else {
        echo "✅ Đã tìm thấy " . count($sql_files) . " file SQL backup\n";
    }
}

echo "\n===========================================\n";
if ($all_extensions_ok) {
    echo "✅ MÔI TRƯỜNG ĐÃ SẴN SÀNG\n";
} else {
    echo "❌ MỘT SỐ YÊU CẦU CHƯA ĐƯỢC ĐÁP ỨNG\n";
}
echo "===========================================\n";

echo "\nCác bước tiếp theo:\n";
echo "1. Tạo thư mục 'suncraft_backup' và đặt file SQL backup vào đó\n";
echo "2. Chạy 'php test_db_connection.php' để kiểm tra kết nối database\n";
echo "3. Chạy 'php import_database.php' để nhập dữ liệu từ file backup\n";
?> 
 
 