<?php
/**
 * Hướng dẫn cấu hình .htaccess cho Suncraft
 * File này giúp tạo file .htaccess phù hợp cho từng môi trường hosting
 */

// Bao gồm file cấu hình
require_once 'config.php';

// Lấy thông tin server
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';

// Kiểm tra xem có phải Apache không
$is_apache = (stripos($server_software, 'apache') !== false);

// Kiểm tra mod_rewrite
$mod_rewrite_enabled = false;
if ($is_apache && function_exists('apache_get_modules')) {
    $mod_rewrite_enabled = in_array('mod_rewrite', apache_get_modules());
}

// Kiểm tra quyền ghi file
$can_write = is_writable(dirname(__FILE__));

// Kiểm tra .htaccess có tồn tại chưa
$htaccess_exists = file_exists('.htaccess');
$htaccess_content = $htaccess_exists ? file_get_contents('.htaccess') : '';

// Template cho .htaccess Apache tiêu chuẩn
$htaccess_template = <<<EOT
# Cấu hình Apache cho Suncraft
# Tự động tạo bởi htaccess-guide.php

# Bật mod_rewrite
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Đặt thư mục gốc cho rewrite rules
    RewriteBase /
    
    # Không áp dụng rewrite cho các tệp/thư mục có thật
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Điều hướng tất cả các yêu cầu khác đến index.php
    RewriteRule ^(.*)$ index.php?url=\$1 [QSA,L]
</IfModule>

# Cấu hình bảo mật
<IfModule mod_headers.c>
    # Bảo vệ khỏi clickjacking
    Header set X-Frame-Options "SAMEORIGIN"
    
    # Bảo vệ khỏi MIME-sniffing
    Header set X-Content-Type-Options "nosniff"
    
    # Bảo vệ khỏi XSS
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Ngăn chặn truy cập vào các tệp nhạy cảm
<FilesMatch "(\.htaccess|\.htpasswd|\.git|\.env|\.gitignore|\.sql|config\.php)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Ngăn chặn truy cập thư mục
Options -Indexes

# PHP cài đặt
<IfModule mod_php7.c>
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
</IfModule>

# Thiết lập default character set
AddDefaultCharset UTF-8
EOT;

// Template cho .htaccess trong thư mục con
$subdir_htaccess_template = <<<EOT
# Cấu hình Apache cho Suncraft trong thư mục con
# Tự động tạo bởi htaccess-guide.php

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase %s
    
    # Không áp dụng rewrite cho các tệp/thư mục có thật
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Điều hướng tất cả các yêu cầu khác đến index.php
    RewriteRule ^(.*)$ index.php?url=\$1 [QSA,L]
</IfModule>

# Ngăn chặn truy cập thư mục
Options -Indexes
EOT;

// Template cho nginx (lưu dưới dạng nginx.conf)
$nginx_template = <<<EOT
# Cấu hình Nginx cho Suncraft
# Sao chép nội dung này vào file nginx.conf hoặc vào block server của bạn

server {
    listen 80;
    server_name example.com www.example.com; # Thay thế bằng domain của bạn
    root /path/to/suncraft; # Thay thế bằng đường dẫn đến thư mục gốc Suncraft
    index index.php index.html;
    
    # Ghi log
    access_log /var/log/nginx/suncraft_access.log;
    error_log /var/log/nginx/suncraft_error.log;
    
    # Xử lý các yêu cầu PHP
    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock; # Điều chỉnh phiên bản PHP nếu cần
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Rewrite rules
    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }
    
    # Ngăn chặn truy cập vào các tệp nhạy cảm
    location ~ /\.ht {
        deny all;
    }
    
    location ~ /\.(git|env|gitignore|sql) {
        deny all;
    }
    
    location ~ ^/(config\.php|database_setup\.sql) {
        deny all;
    }
    
    # Cấu hình cache cho tài nguyên tĩnh
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
EOT;

// Xác định subdirectory dựa trên SCRIPT_NAME
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$base_dir = dirname($script_name);
$in_subdir = ($base_dir !== '/' && $base_dir !== '\\');
$subdir_htaccess = sprintf($subdir_htaccess_template, $base_dir);

// Cờ và thông báo
$messages = [];
$can_autogenerate = $can_write && $is_apache;

// Kiểm tra nếu công cụ này được chạy từ dòng lệnh
$is_cli = (php_sapi_name() == 'cli');

// Lấy tham số từ URL hoặc dòng lệnh
$action = $_GET['action'] ?? ($argv[1] ?? '');

// Thực hiện các hành động cụ thể
if ($action === 'generate-apache' && $can_write) {
    // Tạo file .htaccess cho Apache
    file_put_contents('.htaccess', $htaccess_template);
    $messages[] = [
        'type' => 'success',
        'text' => 'File .htaccess cho Apache đã được tạo thành công.'
    ];
} elseif ($action === 'generate-subdir' && $can_write) {
    // Tạo file .htaccess cho thư mục con
    file_put_contents('.htaccess', $subdir_htaccess);
    $messages[] = [
        'type' => 'success',
        'text' => 'File .htaccess cho thư mục con đã được tạo thành công.'
    ];
} elseif ($action === 'generate-nginx' && $can_write) {
    // Tạo file nginx.conf
    file_put_contents('nginx.conf', $nginx_template);
    $messages[] = [
        'type' => 'success',
        'text' => 'File nginx.conf đã được tạo thành công. Vui lòng sao chép nội dung vào cấu hình Nginx của bạn.'
    ];
} elseif ($action === 'test-rewrite') {
    // Kiểm tra xem mod_rewrite có hoạt động không
    $messages[] = [
        'type' => 'info',
        'text' => 'Đang kiểm tra khả năng rewrite URL... Vui lòng đợi.'
    ];
    
    // Code để kiểm tra rewrite URL sẽ được thêm ở đây
}

// Chỉ hiển thị HTML nếu không phải dòng lệnh
if (!$is_cli):
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn cấu hình .htaccess - Suncraft</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1, h2, h3 {
            color: #2c3e50;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        .btn-warning {
            background-color: #f39c12;
        }
        .btn-warning:hover {
            background-color: #e67e22;
        }
        pre {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            font-family: Consolas, Monaco, 'Andale Mono', monospace;
            overflow-x: auto;
        }
        .server-info {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .code-header {
            background-color: #f1f1f1;
            padding: 8px 15px;
            border-radius: 4px 4px 0 0;
            border: 1px solid #e9ecef;
            border-bottom: none;
            font-weight: bold;
        }
        .status-ok {
            color: #28a745;
        }
        .status-warning {
            color: #ffc107;
        }
        .status-error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>Hướng dẫn cấu hình .htaccess cho Suncraft</h1>
    
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-<?php echo $message['type']; ?>">
            <?php echo $message['text']; ?>
        </div>
    <?php endforeach; ?>
    
    <div class="server-info">
        <h3>Thông tin máy chủ</h3>
        <p><strong>Phần mềm máy chủ:</strong> <?php echo htmlspecialchars($server_software); ?></p>
        <p><strong>Thư mục gốc:</strong> <?php echo htmlspecialchars(dirname(__FILE__)); ?></p>
        <p><strong>Trong thư mục con:</strong> <?php echo $in_subdir ? 'Có (' . htmlspecialchars($base_dir) . ')' : 'Không'; ?></p>
        <p><strong>File .htaccess hiện tại:</strong> <?php echo $htaccess_exists ? 'Đã tồn tại' : 'Chưa tồn tại'; ?></p>
        
        <?php if ($is_apache): ?>
            <p><strong>mod_rewrite:</strong> 
                <?php if ($mod_rewrite_enabled): ?>
                    <span class="status-ok">Đã bật</span>
                <?php elseif ($mod_rewrite_enabled === false): ?>
                    <span class="status-error">Chưa bật</span> - <em>Bạn cần bật mod_rewrite trong cấu hình Apache</em>
                <?php else: ?>
                    <span class="status-warning">Không thể xác định</span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        
        <p><strong>Quyền ghi file:</strong> 
            <?php if ($can_write): ?>
                <span class="status-ok">Có quyền ghi</span>
            <?php else: ?>
                <span class="status-error">Không có quyền ghi</span> - <em>Bạn sẽ cần tạo thủ công file .htaccess</em>
            <?php endif; ?>
        </p>
    </div>
    
    <div class="container">
        <h2>Hướng dẫn theo loại máy chủ</h2>
        
        <h3>1. Apache</h3>
        <?php if ($is_apache): ?>
            <p>Máy chủ của bạn đang chạy Apache. Đây là cấu hình .htaccess được đề xuất:</p>
            
            <?php if ($in_subdir): ?>
                <div class="alert alert-info">
                    <strong>Lưu ý:</strong> Suncraft được cài đặt trong thư mục con (<?php echo htmlspecialchars($base_dir); ?>).
                    Cấu hình dưới đây đã được điều chỉnh phù hợp.
                </div>
            <?php endif; ?>
            
            <div class="code-header">.htaccess <?php echo $in_subdir ? 'cho thư mục con' : ''; ?></div>
            <pre><?php echo htmlspecialchars($in_subdir ? $subdir_htaccess : $htaccess_template); ?></pre>
            
            <?php if ($can_write): ?>
                <p>
                    <a href="?action=<?php echo $in_subdir ? 'generate-subdir' : 'generate-apache'; ?>" class="btn btn-success">
                        Tự động tạo file .htaccess
                    </a>
                </p>
            <?php else: ?>
                <p>
                    <strong>Bạn không có quyền ghi file:</strong> Vui lòng sao chép nội dung trên và tạo thủ công file .htaccess 
                    trong thư mục gốc.
                </p>
            <?php endif; ?>
            
        <?php else: ?>
            <p>Máy chủ của bạn có vẻ không phải là Apache, nhưng nếu bạn đang sử dụng Apache, đây là cấu hình .htaccess đề xuất:</p>
            
            <div class="code-header">.htaccess cho Apache</div>
            <pre><?php echo htmlspecialchars($htaccess_template); ?></pre>
            
            <?php if ($in_subdir): ?>
                <div class="code-header">.htaccess cho Apache (trong thư mục con)</div>
                <pre><?php echo htmlspecialchars($subdir_htaccess); ?></pre>
            <?php endif; ?>
        <?php endif; ?>
        
        <h3>2. Nginx</h3>
        <p>Nếu bạn đang sử dụng Nginx, sao chép cấu hình sau vào tệp cấu hình Nginx của bạn:</p>
        
        <div class="code-header">Cấu hình Nginx</div>
        <pre><?php echo htmlspecialchars($nginx_template); ?></pre>
        
        <?php if ($can_write): ?>
            <p>
                <a href="?action=generate-nginx" class="btn">
                    Tạo file nginx.conf
                </a>
            </p>
        <?php endif; ?>
        
        <div class="alert alert-info">
            <strong>Lưu ý:</strong> Đối với Nginx, bạn cần chỉnh sửa cấu hình máy chủ và khởi động lại để thay đổi có hiệu lực.
        </div>
    </div>
    
    <div class="container">
        <h2>Kiểm tra cấu hình</h2>
        
        <p>Sau khi bạn đã cấu hình máy chủ, bạn có thể kiểm tra xem URL rewriting có hoạt động không bằng cách truy cập:</p>
        
        <p><a href="<?php echo rtrim(BASE_URL, '/'); ?>/test-rewrite" class="btn btn-warning">Kiểm tra URL Rewriting</a></p>
        
        <p>Nếu bạn thấy trang "URL Rewriting hoạt động!" thì cấu hình của bạn đã hoạt động.</p>
        <p>Nếu bạn thấy lỗi 404, có thể URL rewriting chưa được cấu hình đúng.</p>
    </div>
    
    <div class="container">
        <h2>Các vấn đề thường gặp</h2>
        
        <h3>1. Lỗi 500 Internal Server Error</h3>
        <p>Nếu bạn thấy lỗi này sau khi tạo file .htaccess, có thể một số chỉ thị không được hỗ trợ trên máy chủ của bạn.</p>
        <p>Hãy thử chỉnh sửa .htaccess và bỏ từng phần một để xác định phần nào gây ra lỗi.</p>
        
        <h3>2. mod_rewrite không hoạt động</h3>
        <p>Một số máy chủ không bật sẵn mod_rewrite. Bạn cần:</p>
        <ul>
            <li>Liên hệ nhà cung cấp hosting để bật mod_rewrite</li>
            <li>Nếu bạn có quyền quản trị máy chủ, chạy lệnh <code>a2enmod rewrite</code> và khởi động lại Apache</li>
        </ul>
        
        <h3>3. RewriteBase không đúng</h3>
        <p>Nếu bạn cài đặt Suncraft trong thư mục con, đảm bảo rằng RewriteBase trỏ đến đúng thư mục.</p>
        <p>Ví dụ: nếu Suncraft nằm trong <code>https://example.com/suncraft/</code>, RewriteBase nên là <code>/suncraft</code>.</p>
    </div>
    
    <footer>
        <p>&copy; 2023 Suncraft. Mọi quyền được bảo lưu.</p>
    </footer>
</body>
</html>
<?php 
else:
    // Chế độ CLI
    echo "Công cụ cấu hình .htaccess Suncraft\n";
    echo "=============================\n\n";
    
    echo "Thông tin máy chủ:\n";
    echo "- Phần mềm máy chủ: $server_software\n";
    echo "- Thư mục gốc: " . dirname(__FILE__) . "\n";
    echo "- Trong thư mục con: " . ($in_subdir ? "Có ($base_dir)" : "Không") . "\n";
    echo "- File .htaccess hiện tại: " . ($htaccess_exists ? "Đã tồn tại" : "Chưa tồn tại") . "\n";
    
    if ($is_apache) {
        echo "- mod_rewrite: " . ($mod_rewrite_enabled ? "Đã bật" : "Chưa bật hoặc không thể xác định") . "\n";
    }
    
    echo "- Quyền ghi file: " . ($can_write ? "Có" : "Không") . "\n\n";
    
    echo "Các tùy chọn:\n";
    echo "1. Tạo .htaccess cho Apache\n";
    echo "2. Tạo .htaccess cho thư mục con\n";
    echo "3. Tạo nginx.conf\n";
    echo "4. Hiển thị nội dung .htaccess\n";
    echo "5. Thoát\n\n";
    
    echo "Nhập lựa chọn của bạn (1-5): ";
    $choice = trim(fgets(STDIN));
    
    switch ($choice) {
        case '1':
            if ($can_write) {
                file_put_contents('.htaccess', $htaccess_template);
                echo "\nFile .htaccess cho Apache đã được tạo thành công.\n";
            } else {
                echo "\nKhông có quyền ghi. Đây là nội dung .htaccess bạn cần tạo thủ công:\n\n";
                echo $htaccess_template . "\n";
            }
            break;
            
        case '2':
            if ($can_write) {
                file_put_contents('.htaccess', $subdir_htaccess);
                echo "\nFile .htaccess cho thư mục con đã được tạo thành công.\n";
            } else {
                echo "\nKhông có quyền ghi. Đây là nội dung .htaccess bạn cần tạo thủ công:\n\n";
                echo $subdir_htaccess . "\n";
            }
            break;
            
        case '3':
            if ($can_write) {
                file_put_contents('nginx.conf', $nginx_template);
                echo "\nFile nginx.conf đã được tạo thành công.\n";
            } else {
                echo "\nKhông có quyền ghi. Đây là nội dung nginx.conf bạn cần tạo thủ công:\n\n";
                echo $nginx_template . "\n";
            }
            break;
            
        case '4':
            if ($htaccess_exists) {
                echo "\nNội dung .htaccess hiện tại:\n\n";
                echo $htaccess_content . "\n";
            } else {
                echo "\nKhông tìm thấy file .htaccess.\n";
            }
            break;
            
        case '5':
            echo "\nĐã thoát.\n";
            break;
            
        default:
            echo "\nLựa chọn không hợp lệ.\n";
    }
endif;
?> 
 
 
 