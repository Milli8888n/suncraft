<?php
echo "===========================================\n";
echo "NHẬP DỮ LIỆU TỪ FILE SQL\n";
echo "===========================================\n\n";

// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Thay đổi cổng nếu MySQL của bạn dùng cổng khác
$dbname = 'suncraft_db';
$username = 'suncraft_user';
$password = 'StrongPassword123';

// Thư mục chứa file SQL backup
$backup_dir = './suncraft_backup';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
    echo "✅ Đã tạo thư mục backup: $backup_dir\n";
}

// Kiểm tra có file SQL trong thư mục backup không
$sql_files = glob("$backup_dir/*.sql");

if (empty($sql_files)) {
    echo "❌ Không tìm thấy file SQL trong thư mục $backup_dir\n";
    echo "Vui lòng đặt file SQL vào thư mục $backup_dir trước khi chạy script này\n";
    exit(1);
}

// Hiển thị danh sách file SQL
echo "Danh sách file SQL tìm thấy:\n";
foreach ($sql_files as $idx => $file) {
    echo ($idx + 1) . ". " . basename($file) . " (" . round(filesize($file) / 1024, 2) . " KB)\n";
}

// Nếu có nhiều file, hỏi người dùng chọn file
$file_to_import = $sql_files[0]; // Mặc định lấy file đầu tiên
if (count($sql_files) > 1) {
    echo "\nCó nhiều file SQL. Nhập số thứ tự file bạn muốn nhập (1-" . count($sql_files) . "): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    if (is_numeric($line) && (int)$line >= 1 && (int)$line <= count($sql_files)) {
        $file_to_import = $sql_files[(int)$line - 1];
    }
    fclose($handle);
}

echo "\nĐang nhập dữ liệu từ file: " . basename($file_to_import) . "\n";

// Kết nối đến database
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối database thành công\n";
    
    // Đọc nội dung file SQL
    $sql = file_get_contents($file_to_import);
    if (!$sql) {
        echo "❌ Không thể đọc file SQL\n";
        exit(1);
    }
    
    // Tách các câu lệnh SQL
    $queries = explode(';', $sql);
    $count = 0;
    $total = count($queries);
    
    echo "Đang thực thi các câu lệnh SQL...\n";
    
    // Thực thi từng câu lệnh SQL
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $conn->exec($query);
                $count++;
                // Hiển thị tiến trình
                echo "\rTiến trình: " . round(($count / $total) * 100) . "% (" . $count . "/" . $total . " câu lệnh)";
            } catch (PDOException $e) {
                echo "\n❌ Lỗi khi thực thi câu lệnh: " . substr($query, 0, 50) . "...\n";
                echo "   Chi tiết lỗi: " . $e->getMessage() . "\n";
                // Tiếp tục thực thi các câu lệnh khác
            }
        }
    }
    
    echo "\n\n✅ Đã nhập dữ liệu thành công!\n";
    
    // Kiểm tra các bảng đã được tạo
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Số bảng đã tạo: " . count($tables) . "\n";
    
    echo "\nDanh sách bảng và số bản ghi:\n";
    foreach ($tables as $table) {
        $count = $conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "- $table: $count bản ghi\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n===========================================\n";
echo "HOÀN THÀNH NHẬP DỮ LIỆU\n";
echo "===========================================\n";
echo "Bạn đã nhập dữ liệu thành công vào database!\n";
echo "Bây giờ bạn có thể truy cập website để kiểm tra.\n";
echo "===========================================\n";
?> 
echo "===========================================\n";
echo "NHẬP DỮ LIỆU TỪ FILE SQL\n";
echo "===========================================\n\n";

// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Thay đổi cổng nếu MySQL của bạn dùng cổng khác
$dbname = 'suncraft_db';
$username = 'suncraft_user';
$password = 'StrongPassword123';

// Thư mục chứa file SQL backup
$backup_dir = './suncraft_backup';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
    echo "✅ Đã tạo thư mục backup: $backup_dir\n";
}

// Kiểm tra có file SQL trong thư mục backup không
$sql_files = glob("$backup_dir/*.sql");

if (empty($sql_files)) {
    echo "❌ Không tìm thấy file SQL trong thư mục $backup_dir\n";
    echo "Vui lòng đặt file SQL vào thư mục $backup_dir trước khi chạy script này\n";
    exit(1);
}

// Hiển thị danh sách file SQL
echo "Danh sách file SQL tìm thấy:\n";
foreach ($sql_files as $idx => $file) {
    echo ($idx + 1) . ". " . basename($file) . " (" . round(filesize($file) / 1024, 2) . " KB)\n";
}

// Nếu có nhiều file, hỏi người dùng chọn file
$file_to_import = $sql_files[0]; // Mặc định lấy file đầu tiên
if (count($sql_files) > 1) {
    echo "\nCó nhiều file SQL. Nhập số thứ tự file bạn muốn nhập (1-" . count($sql_files) . "): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    if (is_numeric($line) && (int)$line >= 1 && (int)$line <= count($sql_files)) {
        $file_to_import = $sql_files[(int)$line - 1];
    }
    fclose($handle);
}

echo "\nĐang nhập dữ liệu từ file: " . basename($file_to_import) . "\n";

// Kết nối đến database
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối database thành công\n";
    
    // Đọc nội dung file SQL
    $sql = file_get_contents($file_to_import);
    if (!$sql) {
        echo "❌ Không thể đọc file SQL\n";
        exit(1);
    }
    
    // Tách các câu lệnh SQL
    $queries = explode(';', $sql);
    $count = 0;
    $total = count($queries);
    
    echo "Đang thực thi các câu lệnh SQL...\n";
    
    // Thực thi từng câu lệnh SQL
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $conn->exec($query);
                $count++;
                // Hiển thị tiến trình
                echo "\rTiến trình: " . round(($count / $total) * 100) . "% (" . $count . "/" . $total . " câu lệnh)";
            } catch (PDOException $e) {
                echo "\n❌ Lỗi khi thực thi câu lệnh: " . substr($query, 0, 50) . "...\n";
                echo "   Chi tiết lỗi: " . $e->getMessage() . "\n";
                // Tiếp tục thực thi các câu lệnh khác
            }
        }
    }
    
    echo "\n\n✅ Đã nhập dữ liệu thành công!\n";
    
    // Kiểm tra các bảng đã được tạo
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Số bảng đã tạo: " . count($tables) . "\n";
    
    echo "\nDanh sách bảng và số bản ghi:\n";
    foreach ($tables as $table) {
        $count = $conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "- $table: $count bản ghi\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n===========================================\n";
echo "HOÀN THÀNH NHẬP DỮ LIỆU\n";
echo "===========================================\n";
echo "Bạn đã nhập dữ liệu thành công vào database!\n";
echo "Bây giờ bạn có thể truy cập website để kiểm tra.\n";
echo "===========================================\n";
?> 
 
 