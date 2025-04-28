<?php
// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Port hiện tại trên máy local
$rootUser = 'root';
$rootPassword = '';

// Thông tin database mới
$dbName = 'suncraft_db';
$newUser = 'suncraft_user';
$newPassword = 'StrongPassword123';

// Kết nối với MySQL bằng tài khoản root
try {
    $pdo = new PDO("mysql:host=$host;port=$port", $rootUser, $rootPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Đã kết nối MySQL thành công.\n";
    
    // Kiểm tra và tạo database nếu chưa tồn tại
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->rowCount() > 0) {
        echo "Database '$dbName' đã tồn tại.\n";
    } else {
        $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Đã tạo database '$dbName' thành công.\n";
    }
    
    // Kiểm tra user
    $stmt = $pdo->query("SELECT user FROM mysql.user WHERE user = '$newUser'");
    $userExists = $stmt->rowCount() > 0;
    
    if ($userExists) {
        echo "User '$newUser' đã tồn tại.\n";
        // Cập nhật mật khẩu cho user
        $pdo->exec("ALTER USER '$newUser'@'localhost' IDENTIFIED BY '$newPassword'");
        echo "Đã cập nhật mật khẩu cho user '$newUser'.\n";
    } else {
        // Tạo user mới
        $pdo->exec("CREATE USER '$newUser'@'localhost' IDENTIFIED BY '$newPassword'");
        echo "Đã tạo user '$newUser' thành công.\n";
    }
    
    // Cấp quyền cho user với database
    $pdo->exec("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$newUser'@'localhost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "Đã cấp quyền cho user '$newUser' với database '$dbName'.\n";
    
    echo "\nKết quả:\n";
    echo "- Database: $dbName\n";
    echo "- User: $newUser\n";
    echo "- Password: $newPassword\n";
    echo "- Host: $host\n";
    echo "- Port: $port\n";
    
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}

echo "\n";
echo "===================================================\n";
echo "HƯỚNG DẪN TẠO DATABASE TRÊN NAMECHEAP:\n";
echo "===================================================\n";
echo "1. Đăng nhập vào cPanel của Namecheap\n";
echo "2. Tìm mục 'MySQL Databases' và click vào\n";
echo "3. Ở phần 'Create New Database':\n";
echo "   - Nhập 'suncraft_db' vào trường Database Name\n";
echo "   - Click nút 'Create Database'\n";
echo "4. Ở phần 'MySQL Users':\n";
echo "   - Nhập 'suncraft_user' vào trường Username\n";
echo "   - Nhập mật khẩu mạnh vào trường Password\n";
echo "   - Click nút 'Create User'\n";
echo "5. Ở phần 'Add User To Database':\n";
echo "   - Chọn user 'suncraft_user' từ dropdown\n";
echo "   - Chọn database 'suncraft_db' từ dropdown\n";
echo "   - Click nút 'Add'\n";
echo "6. Trong trang phân quyền, chọn 'ALL PRIVILEGES'\n";
echo "   - Click nút 'Make Changes'\n";
echo "7. Ghi nhớ thông tin kết nối để cập nhật file config.php:\n";
echo "   - Database: suncraft_db\n";
echo "   - User: suncraft_user\n";
echo "   - Password: [mật khẩu bạn đã nhập]\n";
echo "   - Host: localhost\n";
echo "   - Port: 3306 (mặc định trên Namecheap)\n";
echo "===================================================\n";
?> 
// Thông tin kết nối MySQL
$host = 'localhost';
$port = '3307'; // Port hiện tại trên máy local
$rootUser = 'root';
$rootPassword = '';

// Thông tin database mới
$dbName = 'suncraft_db';
$newUser = 'suncraft_user';
$newPassword = 'StrongPassword123';

// Kết nối với MySQL bằng tài khoản root
try {
    $pdo = new PDO("mysql:host=$host;port=$port", $rootUser, $rootPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Đã kết nối MySQL thành công.\n";
    
    // Kiểm tra và tạo database nếu chưa tồn tại
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->rowCount() > 0) {
        echo "Database '$dbName' đã tồn tại.\n";
    } else {
        $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Đã tạo database '$dbName' thành công.\n";
    }
    
    // Kiểm tra user
    $stmt = $pdo->query("SELECT user FROM mysql.user WHERE user = '$newUser'");
    $userExists = $stmt->rowCount() > 0;
    
    if ($userExists) {
        echo "User '$newUser' đã tồn tại.\n";
        // Cập nhật mật khẩu cho user
        $pdo->exec("ALTER USER '$newUser'@'localhost' IDENTIFIED BY '$newPassword'");
        echo "Đã cập nhật mật khẩu cho user '$newUser'.\n";
    } else {
        // Tạo user mới
        $pdo->exec("CREATE USER '$newUser'@'localhost' IDENTIFIED BY '$newPassword'");
        echo "Đã tạo user '$newUser' thành công.\n";
    }
    
    // Cấp quyền cho user với database
    $pdo->exec("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$newUser'@'localhost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "Đã cấp quyền cho user '$newUser' với database '$dbName'.\n";
    
    echo "\nKết quả:\n";
    echo "- Database: $dbName\n";
    echo "- User: $newUser\n";
    echo "- Password: $newPassword\n";
    echo "- Host: $host\n";
    echo "- Port: $port\n";
    
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}

echo "\n";
echo "===================================================\n";
echo "HƯỚNG DẪN TẠO DATABASE TRÊN NAMECHEAP:\n";
echo "===================================================\n";
echo "1. Đăng nhập vào cPanel của Namecheap\n";
echo "2. Tìm mục 'MySQL Databases' và click vào\n";
echo "3. Ở phần 'Create New Database':\n";
echo "   - Nhập 'suncraft_db' vào trường Database Name\n";
echo "   - Click nút 'Create Database'\n";
echo "4. Ở phần 'MySQL Users':\n";
echo "   - Nhập 'suncraft_user' vào trường Username\n";
echo "   - Nhập mật khẩu mạnh vào trường Password\n";
echo "   - Click nút 'Create User'\n";
echo "5. Ở phần 'Add User To Database':\n";
echo "   - Chọn user 'suncraft_user' từ dropdown\n";
echo "   - Chọn database 'suncraft_db' từ dropdown\n";
echo "   - Click nút 'Add'\n";
echo "6. Trong trang phân quyền, chọn 'ALL PRIVILEGES'\n";
echo "   - Click nút 'Make Changes'\n";
echo "7. Ghi nhớ thông tin kết nối để cập nhật file config.php:\n";
echo "   - Database: suncraft_db\n";
echo "   - User: suncraft_user\n";
echo "   - Password: [mật khẩu bạn đã nhập]\n";
echo "   - Host: localhost\n";
echo "   - Port: 3306 (mặc định trên Namecheap)\n";
echo "===================================================\n";
?> 
 
 