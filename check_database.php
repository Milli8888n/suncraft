<?php
// Script kiểm tra kết nối cơ sở dữ liệu cho Suncraft
header('Content-Type: text/html; charset=utf-8');

// Thông tin kết nối - thay đổi cho phù hợp với máy local hoặc hosting
$host = 'localhost';
$port = 3306;
$database = 'suncraft_db';
$username = 'suncraft_user';
$password = 'StrongPassword123';

echo "<h1>Kiểm tra kết nối cơ sở dữ liệu Suncraft</h1>";

// Kiểm tra kết nối
try {
    $conn = new PDO("mysql:host=$host;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Kết nối MySQL thành công!</p>";
    
    // Kiểm tra database có tồn tại không
    $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
    $dbExists = $stmt->fetchColumn();
    
    if ($dbExists) {
        echo "<p style='color:green'>✅ Đã tìm thấy database '$database'</p>";
        
        // Kết nối đến database cụ thể
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Liệt kê các bảng
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<p style='color:green'>✅ Số bảng đã tạo: " . count($tables) . "</p>";
            echo "<h2>Danh sách bảng:</h2>";
            echo "<ul>";
            
            foreach ($tables as $table) {
                // Đếm số bản ghi trong mỗi bảng
                $countStmt = $conn->query("SELECT COUNT(*) FROM `$table`");
                $recordCount = $countStmt->fetchColumn();
                
                echo "<li>$table - <strong>$recordCount</strong> bản ghi</li>";
            }
            
            echo "</ul>";
        } else {
            echo "<p style='color:orange'>⚠️ Database '$database' chưa có bảng nào</p>";
            echo "<p>Bạn cần nhập dữ liệu từ tệp SQL hoặc thực hiện migration</p>";
        }
    } else {
        echo "<p style='color:orange'>⚠️ Database '$database' chưa tồn tại</p>";
        echo "<p>Bạn có muốn tạo database này? <a href='?create_db=true'>Tạo ngay</a></p>";
    }
    
    // Xử lý tạo database nếu được yêu cầu
    if (isset($_GET['create_db']) && $_GET['create_db'] == 'true') {
        try {
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "<p style='color:green'>✅ Đã tạo database '$database' thành công!</p>";
            echo "<p>Vui lòng <a href='check_database.php'>tải lại trang</a> để tiếp tục</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red'>❌ Lỗi khi tạo database: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Lỗi kết nối: " . $e->getMessage() . "</p>";
    
    // Kiểm tra lỗi cụ thể
    $errorMessage = $e->getMessage();
    if (strpos($errorMessage, "Access denied for user") !== false) {
        echo "<p>Có thể tài khoản người dùng '$username' không tồn tại hoặc không có quyền truy cập.</p>";
        echo "<p>Vui lòng kiểm tra lại thông tin đăng nhập trong file này.</p>";
    } elseif (strpos($errorMessage, "Connection refused") !== false) {
        echo "<p>Không thể kết nối đến máy chủ MySQL. Vui lòng kiểm tra:</p>";
        echo "<ul>";
        echo "<li>MySQL đã được khởi động chưa?</li>";
        echo "<li>Host và Port có chính xác không?</li>";
        echo "<li>Firewall có chặn kết nối không?</li>";
        echo "</ul>";
    }
}

// Thông tin về PHP và môi trường
echo "<hr/>";
echo "<h2>Thông tin môi trường:</h2>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>MySQL Extensions: " . (extension_loaded('mysqli') ? 'mysqli ✅' : 'mysqli ❌') . 
                          " | " . (extension_loaded('pdo_mysql') ? 'PDO MySQL ✅' : 'PDO MySQL ❌') . "</li>";
echo "<li>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "</ul>";

// Kiểm tra quyền ghi vào thư mục
$directoriesToCheck = [
    './',
    './uploads',
    './assets',
    './cache'
];

echo "<h2>Kiểm tra quyền ghi thư mục:</h2>";
echo "<ul>";

foreach ($directoriesToCheck as $dir) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<li>$dir - <span style='color:green'>✅ Có quyền ghi</span></li>";
        } else {
            echo "<li>$dir - <span style='color:red'>❌ Không có quyền ghi</span></li>";
        }
    } else {
        echo "<li>$dir - <span style='color:orange'>⚠️ Thư mục không tồn tại</span></li>";
    }
}

echo "</ul>";
?> 
// Script kiểm tra kết nối cơ sở dữ liệu cho Suncraft
header('Content-Type: text/html; charset=utf-8');

// Thông tin kết nối - thay đổi cho phù hợp với máy local hoặc hosting
$host = 'localhost';
$port = 3306;
$database = 'suncraft_db';
$username = 'suncraft_user';
$password = 'StrongPassword123';

echo "<h1>Kiểm tra kết nối cơ sở dữ liệu Suncraft</h1>";

// Kiểm tra kết nối
try {
    $conn = new PDO("mysql:host=$host;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Kết nối MySQL thành công!</p>";
    
    // Kiểm tra database có tồn tại không
    $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
    $dbExists = $stmt->fetchColumn();
    
    if ($dbExists) {
        echo "<p style='color:green'>✅ Đã tìm thấy database '$database'</p>";
        
        // Kết nối đến database cụ thể
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Liệt kê các bảng
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<p style='color:green'>✅ Số bảng đã tạo: " . count($tables) . "</p>";
            echo "<h2>Danh sách bảng:</h2>";
            echo "<ul>";
            
            foreach ($tables as $table) {
                // Đếm số bản ghi trong mỗi bảng
                $countStmt = $conn->query("SELECT COUNT(*) FROM `$table`");
                $recordCount = $countStmt->fetchColumn();
                
                echo "<li>$table - <strong>$recordCount</strong> bản ghi</li>";
            }
            
            echo "</ul>";
        } else {
            echo "<p style='color:orange'>⚠️ Database '$database' chưa có bảng nào</p>";
            echo "<p>Bạn cần nhập dữ liệu từ tệp SQL hoặc thực hiện migration</p>";
        }
    } else {
        echo "<p style='color:orange'>⚠️ Database '$database' chưa tồn tại</p>";
        echo "<p>Bạn có muốn tạo database này? <a href='?create_db=true'>Tạo ngay</a></p>";
    }
    
    // Xử lý tạo database nếu được yêu cầu
    if (isset($_GET['create_db']) && $_GET['create_db'] == 'true') {
        try {
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "<p style='color:green'>✅ Đã tạo database '$database' thành công!</p>";
            echo "<p>Vui lòng <a href='check_database.php'>tải lại trang</a> để tiếp tục</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red'>❌ Lỗi khi tạo database: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Lỗi kết nối: " . $e->getMessage() . "</p>";
    
    // Kiểm tra lỗi cụ thể
    $errorMessage = $e->getMessage();
    if (strpos($errorMessage, "Access denied for user") !== false) {
        echo "<p>Có thể tài khoản người dùng '$username' không tồn tại hoặc không có quyền truy cập.</p>";
        echo "<p>Vui lòng kiểm tra lại thông tin đăng nhập trong file này.</p>";
    } elseif (strpos($errorMessage, "Connection refused") !== false) {
        echo "<p>Không thể kết nối đến máy chủ MySQL. Vui lòng kiểm tra:</p>";
        echo "<ul>";
        echo "<li>MySQL đã được khởi động chưa?</li>";
        echo "<li>Host và Port có chính xác không?</li>";
        echo "<li>Firewall có chặn kết nối không?</li>";
        echo "</ul>";
    }
}

// Thông tin về PHP và môi trường
echo "<hr/>";
echo "<h2>Thông tin môi trường:</h2>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>MySQL Extensions: " . (extension_loaded('mysqli') ? 'mysqli ✅' : 'mysqli ❌') . 
                          " | " . (extension_loaded('pdo_mysql') ? 'PDO MySQL ✅' : 'PDO MySQL ❌') . "</li>";
echo "<li>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "</ul>";

// Kiểm tra quyền ghi vào thư mục
$directoriesToCheck = [
    './',
    './uploads',
    './assets',
    './cache'
];

echo "<h2>Kiểm tra quyền ghi thư mục:</h2>";
echo "<ul>";

foreach ($directoriesToCheck as $dir) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<li>$dir - <span style='color:green'>✅ Có quyền ghi</span></li>";
        } else {
            echo "<li>$dir - <span style='color:red'>❌ Không có quyền ghi</span></li>";
        }
    } else {
        echo "<li>$dir - <span style='color:orange'>⚠️ Thư mục không tồn tại</span></li>";
    }
}

echo "</ul>";
?> 
 
 