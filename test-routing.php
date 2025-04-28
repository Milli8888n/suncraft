<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Lấy thông tin đường dẫn
echo "<h1>Kiểm tra Đường dẫn và Routing</h1>";

echo "<h2>Thông tin Máy chủ</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Unknown') . "\n";
echo "</pre>";

echo "<h2>Thông tin URL</h2>";
echo "<pre>";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "\n";
echo "PHP Self: " . ($_SERVER['PHP_SELF'] ?? 'Unknown') . "\n";
echo "Query String: " . ($_SERVER['QUERY_STRING'] ?? 'None') . "\n";
echo "</pre>";

echo "<h2>Kiểm tra Tệp & Thư mục API</h2>";
$apiDir = __DIR__ . '/api';
$files = [
    $apiDir => 'API Directory',
    $apiDir . '/index.php' => 'API Index',
    $apiDir . '/login.php' => 'API Login',
    $apiDir . '/check-auth.php' => 'API Check Auth',
    $apiDir . '/.htaccess' => 'API .htaccess'
];

echo "<pre>";
foreach ($files as $path => $description) {
    echo "$description: ";
    if (file_exists($path)) {
        echo "EXISTS";
        if (is_file($path)) {
            echo " (File, " . filesize($path) . " bytes)";
            echo " - Readable: " . (is_readable($path) ? 'Yes' : 'No');
        } else {
            echo " (Directory)";
            echo " - Readable: " . (is_readable($path) ? 'Yes' : 'No');
        }
    } else {
        echo "NOT FOUND";
    }
    echo "\n";
}
echo "</pre>";

echo "<h2>Kiểm tra Mod Rewrite</h2>";
echo "<pre>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite enabled: " . (in_array('mod_rewrite', $modules) ? 'Yes' : 'No') . "\n";
} else {
    echo "Không thể kiểm tra apache_get_modules()\n";
    echo "Server API: " . php_sapi_name() . "\n";
}
echo "</pre>";

echo "<h2>Kiểm tra .htaccess</h2>";
$htaccess = __DIR__ . '/.htaccess';
echo "<pre>";
if (file_exists($htaccess)) {
    echo "File .htaccess tồn tại (" . filesize($htaccess) . " bytes)\n";
    
    if (is_readable($htaccess)) {
        echo "Nội dung .htaccess:\n";
        echo htmlspecialchars(file_get_contents($htaccess));
    } else {
        echo "File .htaccess không đọc được\n";
    }
} else {
    echo "File .htaccess không tồn tại\n";
}
echo "</pre>";

// Kiểm tra kết nối cơ sở dữ liệu
echo "<h2>Kiểm tra Kết nối DB</h2>";
echo "<pre>";
$config_file = __DIR__ . '/api/config.php';
if (file_exists($config_file) && is_readable($config_file)) {
    include_once $config_file;
    
    // Kiểm tra xem các hằng số DB có tồn tại
    if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME')) {
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Kết nối DB thành công!\n";
        } catch(PDOException $e) {
            echo "Lỗi kết nối DB: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Các hằng số DB không được định nghĩa trong config.php\n";
    }
} else {
    echo "Không thể đọc tệp config.php\n";
}
echo "</pre>"; 