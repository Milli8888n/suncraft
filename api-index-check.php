<?php
// Đường dẫn đến file index.php trong thư mục API
$index_path = '/home/suncpsdo/public_html/suncraft/api/index.php';

// Kiểm tra xem file có tồn tại không
if (file_exists($index_path)) {
    // Hiển thị vài dòng đầu tiên
    $content = file_get_contents($index_path);
    $lines = explode("\n", $content);
    $first_lines = array_slice($lines, 0, 50);
    
    echo "Nội dung 50 dòng đầu tiên của file index.php:\n\n";
    echo htmlspecialchars(implode("\n", $first_lines));
} else {
    echo "File index.php không tồn tại tại đường dẫn: $index_path";
}
?> 