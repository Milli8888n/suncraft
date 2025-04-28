<?php
/**
 * Script API Test - Dùng để chẩn đoán và sửa lỗi API
 * Upload file này vào thư mục gốc của website và truy cập qua: /api-test.php
 */

// Hiển thị lỗi cho mục đích debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Thông tin môi trường
echo "<h1>API Diagnostic Tool</h1>";

// Kiểm tra môi trường
echo "<h2>1. Thông tin môi trường</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Path: " . __FILE__ . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "</pre>";

// Kiểm tra thư mục API
echo "<h2>2. Kiểm tra thư mục API</h2>";
$apiDir = __DIR__ . '/api';
if (file_exists($apiDir) && is_dir($apiDir)) {
    echo "<p style='color:green'>✅ Thư mục API tồn tại: $apiDir</p>";
    
    // Liệt kê các file trong thư mục API
    echo "<h3>Các file trong thư mục API:</h3>";
    echo "<ul>";
    $files = scandir($apiDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>" . $file;
            if (is_dir($apiDir . '/' . $file)) {
                echo " (thư mục)";
            } else {
                echo " (" . filesize($apiDir . '/' . $file) . " bytes)";
            }
            echo "</li>";
        }
    }
    echo "</ul>";
    
    // Kiểm tra các file quan trọng
    $requiredFiles = ['index.php', 'categories.php', 'products.php', '.htaccess'];
    echo "<h3>Kiểm tra các file quan trọng:</h3>";
    foreach ($requiredFiles as $file) {
        if (file_exists($apiDir . '/' . $file)) {
            echo "<p style='color:green'>✅ File $file tồn tại</p>";
        } else {
            echo "<p style='color:red'>❌ File $file không tồn tại</p>";
        }
    }
} else {
    echo "<p style='color:red'>❌ Thư mục API không tồn tại hoặc không thể truy cập: $apiDir</p>";
    
    // Tạo thư mục API
    echo "<h3>Tạo thư mục API và các file cần thiết?</h3>";
    echo "<p>Nếu bạn muốn tạo thư mục API và các file cần thiết, hãy thêm tham số <code>?create=true</code> vào URL.</p>";
    
    if (isset($_GET['create']) && $_GET['create'] === 'true') {
        if (!file_exists($apiDir)) {
            if (mkdir($apiDir, 0755, true)) {
                echo "<p style='color:green'>✅ Đã tạo thư mục API</p>";
            } else {
                echo "<p style='color:red'>❌ Không thể tạo thư mục API</p>";
            }
        }
        
        // Tạo file .htaccess
        $htaccessContent = <<<EOT
# Cấu hình API router
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /api/
    
    # Cho phép truy cập trực tiếp các file thực tế
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Chuyển tất cả request đến index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Cấu hình CORS
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
</IfModule>
EOT;
        
        if (file_put_contents($apiDir . '/.htaccess', $htaccessContent)) {
            echo "<p style='color:green'>✅ Đã tạo file .htaccess</p>";
        } else {
            echo "<p style='color:red'>❌ Không thể tạo file .htaccess</p>";
        }
        
        // Tạo file index.php
        $indexContent = <<<EOT
<?php
header('Content-Type: application/json');

// Xử lý CORS
if (isset(\$_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
}

// Xác định endpoint từ URL
\$requestUri = \$_SERVER['REQUEST_URI'];
\$path = parse_url(\$requestUri, PHP_URL_PATH);
\$segments = explode('/', trim(\$path, '/'));

// Loại bỏ các phần đường dẫn không liên quan
\$apiIndex = -1;
foreach (\$segments as \$index => \$segment) {
    if (\$segment === 'api') {
        \$apiIndex = \$index;
        break;
    }
}

// Nếu tìm thấy 'api' trong đường dẫn
if (\$apiIndex >= 0) {
    // Loại bỏ tất cả segments trước và bao gồm 'api'
    \$segments = array_slice(\$segments, \$apiIndex + 1);
}

// Xác định endpoint và ID
\$endpoint = isset(\$segments[0]) && !empty(\$segments[0]) ? \$segments[0] : '';
\$id = isset(\$segments[1]) && is_numeric(\$segments[1]) ? (int)\$segments[1] : null;

// Phản hồi cho test
echo json_encode([
    'status' => 'ok',
    'message' => 'API test works!',
    'endpoint' => \$endpoint,
    'id' => \$id,
    'request_uri' => \$requestUri,
    'segments' => \$segments,
    'method' => \$_SERVER['REQUEST_METHOD']
]);
EOT;
        
        if (file_put_contents($apiDir . '/index.php', $indexContent)) {
            echo "<p style='color:green'>✅ Đã tạo file index.php</p>";
        } else {
            echo "<p style='color:red'>❌ Không thể tạo file index.php</p>";
        }
    }
}

// Kiểm tra mod_rewrite
echo "<h2>3. Kiểm tra mod_rewrite</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color:green'>✅ mod_rewrite đã được bật</p>";
    } else {
        echo "<p style='color:red'>❌ mod_rewrite không được bật</p>";
        echo "<p>Bạn cần bật mod_rewrite trong cấu hình Apache của bạn.</p>";
    }
} else {
    echo "<p style='color:orange'>⚠️ Không thể kiểm tra mod_rewrite qua PHP</p>";
    echo "<p>Bạn nên kiểm tra cấu hình máy chủ bằng cách khác.</p>";
}

// Kiểm tra kết nối API trực tiếp
echo "<h2>4. Kiểm tra kết nối API</h2>";

// Xác định đường dẫn API
$apiUrl = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/api/stats';

echo "<p>Đang kiểm tra kết nối đến: <code>$apiUrl</code></p>";

// Thử kết nối
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "<p style='color:red'>❌ Lỗi kết nối: $error</p>";
} else {
    echo "<p>Mã HTTP: $httpCode</p>";
    echo "<pre>$result</pre>";
    
    if ($httpCode == 200) {
        echo "<p style='color:green'>✅ Kết nối API thành công!</p>";
    } else {
        echo "<p style='color:red'>❌ Kết nối API không thành công (HTTP $httpCode)</p>";
    }
}

// Hướng dẫn khắc phục
echo "<h2>5. Hướng dẫn khắc phục</h2>";
echo "<ol>";
echo "<li>Đảm bảo thư mục <code>api</code> tồn tại trong thư mục gốc của website</li>";
echo "<li>Đảm bảo các file cần thiết đã được tạo: <code>index.php</code>, <code>categories.php</code>, <code>products.php</code>, <code>.htaccess</code></li>";
echo "<li>Đảm bảo mod_rewrite đã được bật trong Apache</li>";
echo "<li>Kiểm tra quyền truy cập cho thư mục và file API</li>";
echo "<li>Xem log PHP và Apache để tìm lỗi cụ thể</li>";
echo "</ol>";

// Tạo file test
echo "<h2>6. Tạo file test API đơn giản</h2>";
echo "<p>Nếu bạn muốn tạo file test API đơn giản để kiểm tra kết nối API, hãy thêm tham số <code>?create_test=true</code> vào URL.</p>";

if (isset($_GET['create_test']) && $_GET['create_test'] === 'true') {
    // Tạo file test categories
    $categoriesContent = <<<EOT
<?php
header('Content-Type: application/json');

// Xử lý CORS
if (isset(\$_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
}

// Dữ liệu mẫu
\$categories = [
    ['id' => 1, 'name' => 'Máy khoan', 'slug' => 'may-khoan', 'post_count' => 2],
    ['id' => 2, 'name' => 'Máy cắt', 'slug' => 'may-cat', 'post_count' => 1],
    ['id' => 3, 'name' => 'Máy phát điện', 'slug' => 'may-phat-dien', 'post_count' => 0]
];

// Xác định phương thức HTTP và xử lý tương ứng
\$method = \$_SERVER['REQUEST_METHOD'];
\$requestUri = \$_SERVER['REQUEST_URI'];
\$uriSegments = explode('/', trim(parse_url(\$requestUri, PHP_URL_PATH), '/'));

// Lấy id từ URL nếu có
\$categoryId = null;
foreach (\$uriSegments as \$key => \$segment) {
    if (\$segment === 'categories' && isset(\$uriSegments[\$key + 1]) && is_numeric(\$uriSegments[\$key + 1])) {
        \$categoryId = (int)\$uriSegments[\$key + 1];
        break;
    }
}

// Trả về dữ liệu mẫu
echo json_encode(['categories' => \$categories]);
EOT;
    
    if (file_put_contents($apiDir . '/categories.php', $categoriesContent)) {
        echo "<p style='color:green'>✅ Đã tạo file categories.php</p>";
    } else {
        echo "<p style='color:red'>❌ Không thể tạo file categories.php</p>";
    }
    
    // Tạo file test products
    $productsContent = <<<EOT
<?php
header('Content-Type: application/json');

// Xử lý CORS
if (isset(\$_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
}

// Dữ liệu mẫu
\$products = [
    [
        'id' => 1, 
        'name' => 'Máy khoan Bosch GBH 2-26', 
        'price' => 2500000, 
        'category_id' => 1, 
        'category_name' => 'Máy khoan',
        'description' => 'Máy khoan bê tông chuyên nghiệp', 
        'sku' => 'MK001', 
        'status' => 'active', 
        'image' => '../uploads/may-khoan.jpg'
    ],
    [
        'id' => 2, 
        'name' => 'Máy cắt Makita M5801B', 
        'price' => 1800000, 
        'category_id' => 2, 
        'category_name' => 'Máy cắt',
        'description' => 'Máy cắt đa năng', 
        'sku' => 'MC001', 
        'status' => 'active', 
        'image' => '../uploads/may-cat.jpg'
    ]
];

// Trả về dữ liệu mẫu
echo json_encode(['products' => \$products]);
EOT;
    
    if (file_put_contents($apiDir . '/products.php', $productsContent)) {
        echo "<p style='color:green'>✅ Đã tạo file products.php</p>";
    } else {
        echo "<p style='color:red'>❌ Không thể tạo file products.php</p>";
    }
    
    // Tạo file test stats
    $statsContent = <<<EOT
<?php
header('Content-Type: application/json');

// Xử lý CORS
if (isset(\$_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
}

// Trả về dữ liệu thống kê mẫu
echo json_encode([
    'status' => 'success',
    'posts' => 2,
    'categories' => 3,
    'products' => 2,
    'views' => rand(10, 100)
]);
EOT;
    
    if (file_put_contents($apiDir . '/stats.php', $statsContent)) {
        echo "<p style='color:green'>✅ Đã tạo file stats.php</p>";
    } else {
        echo "<p style='color:red'>❌ Không thể tạo file stats.php</p>";
    }
}

echo "<h2>7. Kiểm tra URL API</h2>";
echo "<p>Các URL API bạn có thể thử:</p>";
echo "<ul>";
$host = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
echo "<li><a href='$host/api/stats' target='_blank'>$host/api/stats</a></li>";
echo "<li><a href='$host/api/categories' target='_blank'>$host/api/categories</a></li>";
echo "<li><a href='$host/api/products' target='_blank'>$host/api/products</a></li>";
echo "</ul>";
?> 