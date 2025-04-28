<?php
/**
 * Điểm vào chính cho trang web Suncraft
 * 
 * File này xử lý tất cả các request và điều hướng đến các controller thích hợp
 */

// Bao gồm file cấu hình
require_once 'config.php';

// Log request URI cho debug
error_log('Request URI: ' . $_SERVER['REQUEST_URI']);

// Tạo các thư mục cần thiết nếu chưa tồn tại
$requiredDirs = [
    UPLOADS_PATH,
    ASSETS_PATH,
    CACHE_PATH
];

foreach ($requiredDirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Lấy URL từ REQUEST_URI và loại bỏ /suncraft/ nếu có
$url = $_GET['url'] ?? '';
if (empty($url)) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Kiểm tra và loại bỏ prefix '/suncraft' nếu có
    if (strpos($path, '/suncraft') === 0) {
        $path = substr($path, strlen('/suncraft'));
    }
    
    // Xóa dấu / đầu tiên nếu có
    $url = ltrim($path, '/');
}

$url = rtrim($url, '/');
$parts = explode('/', $url);

// Debug URL parts
error_log('URL parts: ' . json_encode($parts));

// Tách controller và action
$controller = !empty($parts[0]) ? $parts[0] : 'home';
$action = !empty($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// Kiểm tra xem có phải là đường dẫn API không
if ($controller === 'api') {
    include 'api/index.php';
    exit;
}

// Kiểm tra xem có phải là đường dẫn admin không
$isAdmin = ($controller === 'admin');

// Nếu đường dẫn là admin, điều hướng đến trang admin
if ($isAdmin) {
    if (empty($parts[1])) {
        // Nếu chỉ có /admin, điều hướng đến trang chủ admin
        include 'admin/index.php';
        exit;
    } else {
        // Nếu có /admin/action, điều hướng đến action tương ứng
        $adminAction = $parts[1];
        $adminParams = array_slice($parts, 2);
        
        // Kiểm tra xem file controller admin có tồn tại không
        $adminControllerFile = 'admin/controllers/' . $adminAction . '.php';
        if (file_exists($adminControllerFile)) {
            include $adminControllerFile;
            exit;
        } else {
            // Nếu không tìm thấy controller, kiểm tra xem file view có tồn tại không
            $adminViewFile = 'admin/views/' . $adminAction . '.php';
            if (file_exists($adminViewFile)) {
                include $adminViewFile;
                exit;
            }
        }
        
        // Nếu không tìm thấy cả controller và view, chuyển hướng đến trang 404
        header('HTTP/1.0 404 Not Found');
        include 'templates/errors/404.php';
        exit;
    }
}

// Kiểm tra xem controller có tồn tại không (trang phía frontend)
$controllerFile = 'includes/controllers/' . $controller . '.php';
if (file_exists($controllerFile)) {
    include $controllerFile;
    exit;
}

// Kiểm tra xem view có tồn tại không (trang tĩnh)
$viewFile = 'templates/' . $controller . '.php';
if (file_exists($viewFile)) {
    include 'includes/layouts/header.php';
    include $viewFile;
    include 'includes/layouts/footer.php';
    exit;
}

// Kiểm tra tệp HTML tĩnh (khả năng tương thích ngược)
$htmlFile = $controller . '.html';
if (file_exists($htmlFile)) {
    // Log hit của file HTML
    error_log('Serving static HTML file: ' . $htmlFile);
    include $htmlFile;
    exit;
}

// Kiểm tra xem có phải là file static không (js, css, images)
$staticFileExtensions = ['js', 'css', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
$requestedFileExt = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION);

if (in_array($requestedFileExt, $staticFileExtensions)) {
    // Xây dựng đường dẫn tương đối đến file static
    $staticFile = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    
    // Kiểm tra xem file có tồn tại không
    if (file_exists($staticFile)) {
        // Xác định content type
        $contentTypes = [
            'js' => 'application/javascript',
            'css' => 'text/css',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'application/font-woff',
            'woff2' => 'application/font-woff2',
            'ttf' => 'application/x-font-ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        if (isset($contentTypes[$requestedFileExt])) {
            header('Content-Type: ' . $contentTypes[$requestedFileExt]);
        }
        
        // Gửi file về client
        readfile($staticFile);
        exit;
    }
}

// Nếu không tìm thấy controller, view hoặc route đặc biệt nào, chuyển hướng đến trang 404
header('HTTP/1.0 404 Not Found');

// Kiểm tra xem tệp 404 có tồn tại không
if (file_exists('templates/errors/404.php')) {
    include 'templates/errors/404.php';
} else {
    // Trang 404 mặc định nếu không tìm thấy tệp 404.php
    echo '<h1>404 - Không tìm thấy trang</h1>';
    echo '<p>Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>';
    echo '<p><a href="' . BASE_URL . '">Trở về trang chủ</a></p>';
}
exit;
