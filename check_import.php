<?php
/**
 * Script kiểm tra cơ sở dữ liệu sau khi nhập dữ liệu
 * Sử dụng file này sau khi đã nhập dữ liệu từ phpMyAdmin
 */

// Bao gồm file cấu hình để lấy thông tin kết nối
require_once 'config.php';

// Header HTML
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Kiểm tra cơ sở dữ liệu Suncraft</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f0f0f0; }
        .status-good { background-color: #e8f5e9; }
        .status-warning { background-color: #fff8e1; }
        .status-error { background-color: #ffebee; }
    </style>
</head>
<body>
    <h1>Kiểm tra cơ sở dữ liệu Suncraft</h1>";

try {
    // Kết nối cơ sở dữ liệu
    $conn = getDbConnection();
    echo "<p class='success'>✅ Kết nối cơ sở dữ liệu thành công!</p>";
    
    // Danh sách các bảng cần kiểm tra
    $expectedTables = [
        'users',
        'categories',
        'posts',
        'post_categories',
        'comments',
        'product_categories',
        'products',
        'settings'
    ];
    
    // Lấy danh sách bảng từ CSDL
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Kiểm tra cấu trúc cơ sở dữ liệu</h2>";
    echo "<p>Tổng số bảng: <strong>" . count($tables) . "</strong></p>";
    
    echo "<table>
            <tr>
                <th>Bảng</th>
                <th>Trạng thái</th>
                <th>Số bản ghi</th>
            </tr>";
    
    $allTablesExist = true;
    
    foreach ($expectedTables as $table) {
        $exists = in_array($table, $tables);
        $recordCount = 0;
        $class = "";
        
        if ($exists) {
            $countStmt = $conn->query("SELECT COUNT(*) FROM `$table`");
            $recordCount = $countStmt->fetchColumn();
            
            if ($recordCount > 0) {
                $class = "status-good";
                $status = "✅ Đã tạo và có dữ liệu";
            } else {
                $class = "status-warning";
                $status = "⚠️ Đã tạo nhưng chưa có dữ liệu";
            }
        } else {
            $class = "status-error";
            $status = "❌ Chưa được tạo";
            $allTablesExist = false;
        }
        
        echo "<tr class='$class'>
                <td>$table</td>
                <td>$status</td>
                <td>$recordCount</td>
              </tr>";
    }
    
    echo "</table>";
    
    // Kiểm tra tài khoản admin
    if (in_array('users', $tables)) {
        $stmt = $conn->query("SELECT * FROM users WHERE username = 'admin'");
        $admin = $stmt->fetch();
        
        echo "<h2>Kiểm tra tài khoản admin</h2>";
        
        if ($admin) {
            echo "<p class='success'>✅ Tài khoản admin đã tồn tại.</p>";
            echo "<p>Username: <strong>admin</strong></p>";
            echo "<p>Email: <strong>" . $admin['email'] . "</strong></p>";
            echo "<p>Lưu ý: Mật khẩu mặc định là <strong>admin123</strong>. Bạn nên đổi mật khẩu này sau khi đăng nhập.</p>";
        } else {
            echo "<p class='error'>❌ Không tìm thấy tài khoản admin. Cơ sở dữ liệu có thể chưa được nhập đầy đủ.</p>";
        }
    }
    
    // Kiểm tra cài đặt website
    if (in_array('settings', $tables)) {
        $stmt = $conn->query("SELECT * FROM settings WHERE setting_key IN ('site_title', 'site_description')");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        echo "<h2>Kiểm tra cài đặt website</h2>";
        
        if (count($settings) > 0) {
            echo "<p class='success'>✅ Đã tìm thấy cài đặt website.</p>";
            echo "<ul>";
            foreach ($settings as $key => $value) {
                echo "<li><strong>$key:</strong> $value</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='warning'>⚠️ Không tìm thấy cài đặt website cơ bản.</p>";
        }
    }
    
    // Tổng kết
    echo "<h2>Tổng kết</h2>";
    
    if ($allTablesExist) {
        echo "<p class='success'>✅ Tất cả các bảng cơ sở dữ liệu đã được tạo thành công.</p>";
    } else {
        echo "<p class='error'>❌ Một số bảng chưa được tạo. Vui lòng kiểm tra lại quá trình nhập dữ liệu.</p>";
    }
    
    echo "<h2>Các bước tiếp theo</h2>";
    echo "<ol>
            <li>Truy cập <a href='index.php'>trang chủ</a> để kiểm tra website.</li>
            <li>Đăng nhập vào <a href='admin'>trang quản trị</a> với tài khoản admin.</li>
            <li>Thiết lập các cài đặt website và thay đổi mật khẩu admin.</li>
            <li>Tạo nội dung cho website của bạn.</li>
          </ol>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage() . "</p>";
    echo "<p>Vui lòng kiểm tra lại thông tin kết nối trong file config.php.</p>";
}

echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='set_permissions.php'>Thiết lập quyền cho thư mục và file</a></p>";
echo "</body></html>";
?> 
/**
 * Script kiểm tra cơ sở dữ liệu sau khi nhập dữ liệu
 * Sử dụng file này sau khi đã nhập dữ liệu từ phpMyAdmin
 */

// Bao gồm file cấu hình để lấy thông tin kết nối
require_once 'config.php';

// Header HTML
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Kiểm tra cơ sở dữ liệu Suncraft</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f0f0f0; }
        .status-good { background-color: #e8f5e9; }
        .status-warning { background-color: #fff8e1; }
        .status-error { background-color: #ffebee; }
    </style>
</head>
<body>
    <h1>Kiểm tra cơ sở dữ liệu Suncraft</h1>";

try {
    // Kết nối cơ sở dữ liệu
    $conn = getDbConnection();
    echo "<p class='success'>✅ Kết nối cơ sở dữ liệu thành công!</p>";
    
    // Danh sách các bảng cần kiểm tra
    $expectedTables = [
        'users',
        'categories',
        'posts',
        'post_categories',
        'comments',
        'product_categories',
        'products',
        'settings'
    ];
    
    // Lấy danh sách bảng từ CSDL
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Kiểm tra cấu trúc cơ sở dữ liệu</h2>";
    echo "<p>Tổng số bảng: <strong>" . count($tables) . "</strong></p>";
    
    echo "<table>
            <tr>
                <th>Bảng</th>
                <th>Trạng thái</th>
                <th>Số bản ghi</th>
            </tr>";
    
    $allTablesExist = true;
    
    foreach ($expectedTables as $table) {
        $exists = in_array($table, $tables);
        $recordCount = 0;
        $class = "";
        
        if ($exists) {
            $countStmt = $conn->query("SELECT COUNT(*) FROM `$table`");
            $recordCount = $countStmt->fetchColumn();
            
            if ($recordCount > 0) {
                $class = "status-good";
                $status = "✅ Đã tạo và có dữ liệu";
            } else {
                $class = "status-warning";
                $status = "⚠️ Đã tạo nhưng chưa có dữ liệu";
            }
        } else {
            $class = "status-error";
            $status = "❌ Chưa được tạo";
            $allTablesExist = false;
        }
        
        echo "<tr class='$class'>
                <td>$table</td>
                <td>$status</td>
                <td>$recordCount</td>
              </tr>";
    }
    
    echo "</table>";
    
    // Kiểm tra tài khoản admin
    if (in_array('users', $tables)) {
        $stmt = $conn->query("SELECT * FROM users WHERE username = 'admin'");
        $admin = $stmt->fetch();
        
        echo "<h2>Kiểm tra tài khoản admin</h2>";
        
        if ($admin) {
            echo "<p class='success'>✅ Tài khoản admin đã tồn tại.</p>";
            echo "<p>Username: <strong>admin</strong></p>";
            echo "<p>Email: <strong>" . $admin['email'] . "</strong></p>";
            echo "<p>Lưu ý: Mật khẩu mặc định là <strong>admin123</strong>. Bạn nên đổi mật khẩu này sau khi đăng nhập.</p>";
        } else {
            echo "<p class='error'>❌ Không tìm thấy tài khoản admin. Cơ sở dữ liệu có thể chưa được nhập đầy đủ.</p>";
        }
    }
    
    // Kiểm tra cài đặt website
    if (in_array('settings', $tables)) {
        $stmt = $conn->query("SELECT * FROM settings WHERE setting_key IN ('site_title', 'site_description')");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        echo "<h2>Kiểm tra cài đặt website</h2>";
        
        if (count($settings) > 0) {
            echo "<p class='success'>✅ Đã tìm thấy cài đặt website.</p>";
            echo "<ul>";
            foreach ($settings as $key => $value) {
                echo "<li><strong>$key:</strong> $value</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='warning'>⚠️ Không tìm thấy cài đặt website cơ bản.</p>";
        }
    }
    
    // Tổng kết
    echo "<h2>Tổng kết</h2>";
    
    if ($allTablesExist) {
        echo "<p class='success'>✅ Tất cả các bảng cơ sở dữ liệu đã được tạo thành công.</p>";
    } else {
        echo "<p class='error'>❌ Một số bảng chưa được tạo. Vui lòng kiểm tra lại quá trình nhập dữ liệu.</p>";
    }
    
    echo "<h2>Các bước tiếp theo</h2>";
    echo "<ol>
            <li>Truy cập <a href='index.php'>trang chủ</a> để kiểm tra website.</li>
            <li>Đăng nhập vào <a href='admin'>trang quản trị</a> với tài khoản admin.</li>
            <li>Thiết lập các cài đặt website và thay đổi mật khẩu admin.</li>
            <li>Tạo nội dung cho website của bạn.</li>
          </ol>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage() . "</p>";
    echo "<p>Vui lòng kiểm tra lại thông tin kết nối trong file config.php.</p>";
}

echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='set_permissions.php'>Thiết lập quyền cho thư mục và file</a></p>";
echo "</body></html>";
?> 
 
 