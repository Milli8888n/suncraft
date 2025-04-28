<?php
echo "===========================================\n";
echo "KIỂM TRA VÀ TẠO CƠ SỞ DỮ LIỆU\n";
echo "===========================================\n\n";

// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Thay đổi cổng nếu MySQL của bạn dùng cổng khác
$root_username = 'root';
$root_password = '';

// Thông tin database và user mới
$new_db = 'suncraft_db';
$new_user = 'suncraft_user';
$new_password = 'StrongPassword123';

// Kiểm tra kết nối với tài khoản root
try {
    $root_dsn = "mysql:host=$host;port=$port";
    $root_conn = new PDO($root_dsn, $root_username, $root_password);
    $root_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối MySQL với tài khoản root thành công\n\n";
    
    // Kiểm tra database đã tồn tại chưa
    $stmt = $root_conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$new_db'");
    $db_exists = $stmt->fetchColumn();
    
    if ($db_exists) {
        echo "✅ Database '$new_db' đã tồn tại\n";
    } else {
        echo "❌ Database '$new_db' chưa tồn tại\n";
        echo "   Đang tạo database...\n";
        
        $root_conn->exec("CREATE DATABASE `$new_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Đã tạo database '$new_db' thành công\n";
    }
    
    // Kiểm tra user đã tồn tại chưa
    $stmt = $root_conn->query("SELECT User FROM mysql.user WHERE User = '$new_user'");
    $user_exists = $stmt->fetchColumn();
    
    if ($user_exists) {
        echo "✅ User '$new_user' đã tồn tại\n";
    } else {
        echo "❌ User '$new_user' chưa tồn tại\n";
        echo "   Đang tạo user...\n";
        
        // Tạo user mới và cấp quyền
        $root_conn->exec("CREATE USER '$new_user'@'%' IDENTIFIED BY '$new_password'");
        $root_conn->exec("GRANT ALL PRIVILEGES ON `$new_db`.* TO '$new_user'@'%'");
        $root_conn->exec("FLUSH PRIVILEGES");
        
        echo "✅ Đã tạo user '$new_user' và cấp quyền thành công\n";
    }
    
    echo "\n";
    
    // Kiểm tra kết nối với user mới
    try {
        $new_dsn = "mysql:host=$host;port=$port;dbname=$new_db";
        $new_conn = new PDO($new_dsn, $new_user, $new_password);
        $new_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Kết nối với user '$new_user' thành công\n";
        
        // Kiểm tra bảng trong database
        $tables = $new_conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✅ Đã có " . count($tables) . " bảng trong database\n";
            echo "   Danh sách bảng:\n";
            foreach ($tables as $table) {
                $count = $new_conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "   - $table: $count bản ghi\n";
            }
        } else {
            echo "❌ Chưa có bảng nào trong database\n";
            echo "   Bạn cần nhập dữ liệu từ file SQL backup\n";
        }
        
    } catch (PDOException $e) {
        echo "❌ Kết nối với user '$new_user' thất bại: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Kết nối MySQL với tài khoản root thất bại\n";
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "\nGợi ý:\n";
    echo "1. Kiểm tra MySQL đã được cài đặt và đang chạy\n";
    echo "2. Kiểm tra thông tin root username và password\n";
    echo "3. Kiểm tra cổng kết nối MySQL (hiện tại: $port)\n";
    echo "4. Nếu bạn không có quyền root, hãy tạo database và user thủ công:\n";
    echo "   - Tạo database '$new_db'\n";
    echo "   - Tạo user '$new_user' với password '$new_password'\n";
    echo "   - Cấp quyền cho user trên database\n";
}

echo "\n===========================================\n";
echo "Các bước tiếp theo:\n";
echo "1. Chạy 'php import_database.php' để nhập dữ liệu\n";
echo "2. Kiểm tra website trên trình duyệt\n";
echo "===========================================\n";
?> 
echo "===========================================\n";
echo "KIỂM TRA VÀ TẠO CƠ SỞ DỮ LIỆU\n";
echo "===========================================\n\n";

// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Thay đổi cổng nếu MySQL của bạn dùng cổng khác
$root_username = 'root';
$root_password = '';

// Thông tin database và user mới
$new_db = 'suncraft_db';
$new_user = 'suncraft_user';
$new_password = 'StrongPassword123';

// Kiểm tra kết nối với tài khoản root
try {
    $root_dsn = "mysql:host=$host;port=$port";
    $root_conn = new PDO($root_dsn, $root_username, $root_password);
    $root_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối MySQL với tài khoản root thành công\n\n";
    
    // Kiểm tra database đã tồn tại chưa
    $stmt = $root_conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$new_db'");
    $db_exists = $stmt->fetchColumn();
    
    if ($db_exists) {
        echo "✅ Database '$new_db' đã tồn tại\n";
    } else {
        echo "❌ Database '$new_db' chưa tồn tại\n";
        echo "   Đang tạo database...\n";
        
        $root_conn->exec("CREATE DATABASE `$new_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Đã tạo database '$new_db' thành công\n";
    }
    
    // Kiểm tra user đã tồn tại chưa
    $stmt = $root_conn->query("SELECT User FROM mysql.user WHERE User = '$new_user'");
    $user_exists = $stmt->fetchColumn();
    
    if ($user_exists) {
        echo "✅ User '$new_user' đã tồn tại\n";
    } else {
        echo "❌ User '$new_user' chưa tồn tại\n";
        echo "   Đang tạo user...\n";
        
        // Tạo user mới và cấp quyền
        $root_conn->exec("CREATE USER '$new_user'@'%' IDENTIFIED BY '$new_password'");
        $root_conn->exec("GRANT ALL PRIVILEGES ON `$new_db`.* TO '$new_user'@'%'");
        $root_conn->exec("FLUSH PRIVILEGES");
        
        echo "✅ Đã tạo user '$new_user' và cấp quyền thành công\n";
    }
    
    echo "\n";
    
    // Kiểm tra kết nối với user mới
    try {
        $new_dsn = "mysql:host=$host;port=$port;dbname=$new_db";
        $new_conn = new PDO($new_dsn, $new_user, $new_password);
        $new_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Kết nối với user '$new_user' thành công\n";
        
        // Kiểm tra bảng trong database
        $tables = $new_conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✅ Đã có " . count($tables) . " bảng trong database\n";
            echo "   Danh sách bảng:\n";
            foreach ($tables as $table) {
                $count = $new_conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "   - $table: $count bản ghi\n";
            }
        } else {
            echo "❌ Chưa có bảng nào trong database\n";
            echo "   Bạn cần nhập dữ liệu từ file SQL backup\n";
        }
        
    } catch (PDOException $e) {
        echo "❌ Kết nối với user '$new_user' thất bại: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Kết nối MySQL với tài khoản root thất bại\n";
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "\nGợi ý:\n";
    echo "1. Kiểm tra MySQL đã được cài đặt và đang chạy\n";
    echo "2. Kiểm tra thông tin root username và password\n";
    echo "3. Kiểm tra cổng kết nối MySQL (hiện tại: $port)\n";
    echo "4. Nếu bạn không có quyền root, hãy tạo database và user thủ công:\n";
    echo "   - Tạo database '$new_db'\n";
    echo "   - Tạo user '$new_user' với password '$new_password'\n";
    echo "   - Cấp quyền cho user trên database\n";
}

echo "\n===========================================\n";
echo "Các bước tiếp theo:\n";
echo "1. Chạy 'php import_database.php' để nhập dữ liệu\n";
echo "2. Kiểm tra website trên trình duyệt\n";
echo "===========================================\n";
?> 
 
 