<?php
/**
 * Tệp cấu hình chính cho Suncraft
 * 
 * Tệp này chứa các cài đặt cơ bản:
 * - Thông tin kết nối database
 * - Các hằng số và biến môi trường
 * - Cấu hình chung
 */

// Chế độ debug (true khi phát triển, false khi lên production)
define('DEBUG_MODE', true);

// Thiết lập múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình cơ sở dữ liệu
define('DB_HOST', 'localhost');     // Host cơ sở dữ liệu
define('DB_PORT', '3306');          // Port của MySQL/MariaDB
define('DB_NAME', 'suncpsdo_suncraft_db');   // Tên database
define('DB_USER', 'suncpsdo_suncraft_user'); // Tên người dùng
define('DB_PASS', 'StrongPassword123'); // Mật khẩu

// URL gốc của website (tự động xác định)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = "https://";
} else {
    $protocol = "http://";
}
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$baseURL = $protocol . $host;
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath !== '/' && $basePath !== '\\') {
    $baseURL .= $basePath;
}
define('BASE_URL', rtrim($baseURL, '/'));

// Đường dẫn thư mục
define('ROOT_PATH', realpath(dirname(__FILE__)));
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('CACHE_PATH', ROOT_PATH . '/cache');

// Cấu hình upload
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Cấu hình bảo mật
define('AUTH_SECRET', 'SuncraftSecretKey2023'); // Khóa bí mật cho jwt/session
define('AUTH_EXPIRE', 86400); // Thời gian hết hạn session (1 ngày)

// Biến môi trường khác
define('ITEMS_PER_PAGE', 10); // Số mục hiển thị trên mỗi trang
define('SITE_EMAIL', 'contact@suncraft.com');

/**
 * Kết nối đến database sử dụng PDO
 * Trả về đối tượng PDO nếu kết nối thành công
 * Hiển thị lỗi nếu kết nối thất bại (chỉ khi DEBUG_MODE = true)
 */
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Lỗi kết nối database: " . $e->getMessage());
            } else {
                die("Không thể kết nối đến cơ sở dữ liệu. Vui lòng thử lại sau.");
            }
        }
    }
    
    return $conn;
}

/**
 * Hàm xử lý lỗi tùy chỉnh
 */
function errorHandler($errno, $errstr, $errfile, $errline) {
    if (DEBUG_MODE) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;'>";
        echo "<strong>Lỗi [$errno]:</strong> $errstr<br>";
        echo "File: $errfile, Line: $errline";
        echo "</div>";
    } else {
        error_log("Lỗi [$errno]: $errstr trong $errfile dòng $errline");
    }
    
    // Không chặn các xử lý lỗi mặc định của PHP
    return false;
}

// Thiết lập xử lý lỗi tùy chỉnh
set_error_handler("errorHandler");

/**
 * Hàm lấy cài đặt từ bảng settings
 */
function getSetting($key, $default = '') {
    static $settings = null;
    
    if ($settings === null) {
        try {
            $db = getDbConnection();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $settings = [];
            
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                echo "Lỗi khi lấy cài đặt: " . $e->getMessage();
            }
            return $default;
        }
    }
    
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Tạo URL an toàn
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Hàm redirect
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Hàm làm sạch dữ liệu đầu vào
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
    } else {
        $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Hàm tạo thông báo flash
 */
function setFlashMessage($type, $message) {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Hàm lấy và xóa thông báo flash
 */
function getFlashMessages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    
    return $messages;
}

// Khởi tạo session
if (!session_id()) {
    session_start();
}

// Thiết lập các thông báo lỗi dựa trên chế độ DEBUG
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}
?> 