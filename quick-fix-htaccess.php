<?php
/**
 * File hướng dẫn sửa nhanh .htaccess để khắc phục lỗi API
 */

echo "<h1>Sửa nhanh file .htaccess</h1>";
echo "<p>Nếu bạn muốn khắc phục nhanh mà không sửa code, hãy thay đổi file .htaccess trong thư mục API:</p>";
echo "<ol>
    <li>Đăng nhập vào cPanel > File Manager</li>
    <li>Mở file `/home/suncpsdo/public_html/suncraft/api/.htaccess`</li>
    <li>Thay toàn bộ nội dung bằng đoạn code sau:</li>
</ol>";

echo "<pre>";
echo htmlspecialchars('# Cấu hình API router đơn giản
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /suncraft/api/
    
    # Các request đến file thực tế - bypass routing
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ - [L]
    
    # Chuyển hướng trực tiếp từ /stats đến stats.php
    RewriteRule ^stats/?$ stats.php [L,QSA]
    
    # Tất cả các request khác vào index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Cấu hình CORS
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
    Header set Access-Control-Allow-Credentials "true"
</IfModule>');
echo "</pre>";

echo "<p>Lời giải thích:</p>";
echo "<ul>
    <li>Cấu hình này đơn giản hơn phiên bản hiện tại</li>
    <li>Điểm quan trọng: <code>RewriteRule ^stats/?$ stats.php [L,QSA]</code> chuyển hướng trực tiếp từ /api/stats đến stats.php</li>
    <li>Mọi request khác vẫn đi qua index.php</li>
</ul>";

echo "<p><strong>Sau khi sửa:</strong> Kiểm tra lại bằng cách truy cập: https://suncraft.com.vn/api/stats</p>";
?> 