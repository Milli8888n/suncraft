<?php
// Đường dẫn đến file .htaccess trong thư mục API
$htaccess_path = '/home/suncpsdo/public_html/suncraft/api/.htaccess';

// Kiểm tra xem file có tồn tại không
if (file_exists($htaccess_path)) {
    echo "Nội dung file .htaccess:\n\n";
    echo htmlspecialchars(file_get_contents($htaccess_path));
} else {
    echo "File .htaccess không tồn tại tại đường dẫn: $htaccess_path";
}
?> 