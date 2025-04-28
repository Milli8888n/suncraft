<?php
// Script tìm và thay thế tham chiếu đến 'suncpsdo_suncraft' trong toàn bộ dự án
header('Content-Type: text/html; charset=utf-8');

// Cấu hình
$search_string = 'cpaneluser_suncraft';
$replace_string = 'suncpsdo_suncraft';
$root_dir = __DIR__;
$extensions = ['php', 'js', 'html', 'txt', 'md'];
$backup_dir = $root_dir . '/config_backup_' . date('Ymd_His');
$modified_files = [];

echo "<h1>Sửa cấu hình kết nối database</h1>";
echo "<p>Thời gian: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Tìm kiếm: <code>'$search_string'</code><br>";
echo "Thay thế bằng: <code>'$replace_string'</code></p>";

// Tạo thư mục backup
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
    echo "<p>Đã tạo thư mục backup: $backup_dir</p>";
}

// Xóa tất cả cookie và session hiện tại
clearCookiesAndSessions();

// Hàm tìm kiếm và thay thế trong file
function searchAndReplace($file_path, $search, $replace) {
    // Kiểm tra file có tồn tại không
    if (!file_exists($file_path)) {
        return [false, 0];
    }
    
    // Đọc nội dung file
    $content = file_get_contents($file_path);
    
    // Đếm số vị trí thay thế
    $count = 0;
    $new_content = str_replace($search, $replace, $content, $count);
    
    // Nếu có thay đổi, ghi lại nội dung
    if ($count > 0) {
        file_put_contents($file_path, $new_content);
        return [true, $count];
    }
    
    return [false, 0];
}

// Hàm để xóa tất cả cookie và session hiện tại
function clearCookiesAndSessions() {
    // Xóa tất cả cookie
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
    
    // Xóa session hiện tại
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
        session_write_close();
    }
    
    // Xóa tất cả file session trong thư mục session
    $session_path = session_save_path();
    if (!empty($session_path) && is_dir($session_path)) {
        $files = glob($session_path . '/sess_*');
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}

// Tìm và thay thế trong thư mục
function findAndReplaceInDirectory($directory, $search_string, $replace_string, $backup_dir, &$modified_files) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($files as $file) {
        // Chỉ xử lý các file PHP
        if ($file->isFile() && $file->getExtension() === 'php') {
            $file_path = $file->getRealPath();
            
            list($modified, $count) = searchAndReplace($file_path, $search_string, $replace_string);
            
            if ($modified) {
                // Tạo backup
                copy($file_path, $backup_dir . '/' . basename($file_path));
                
                // Thêm vào danh sách file đã sửa
                $modified_files[] = [
                    'file' => str_replace(__DIR__ . '/', '', $file_path),
                    'positions' => $count
                ];
            }
        }
    }
}

// Thực hiện tìm kiếm và thay thế trong thư mục
findAndReplaceInDirectory(__DIR__, $search_string, $replace_string, $backup_dir, $modified_files);

// Hiển thị kết quả
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa cấu hình kết nối database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        ul {
            margin-top: 10px;
        }
        li {
            margin-bottom: 5px;
        }
        .success {
            color: green;
        }
        .warning {
            color: orange;
        }
    </style>
</head>
<body>
    <h1>Sửa cấu hình kết nối database</h1>
    
    <p>Thời gian: <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <p>Tìm kiếm: <strong>'<?php echo htmlspecialchars($search_string); ?>'</strong></p>
    <p>Thay thế bằng: <strong>'<?php echo htmlspecialchars($replace_string); ?>'</strong></p>
    
    <p>Đã tạo thư mục backup: <?php echo htmlspecialchars($backup_dir); ?></p>
    
    <h2>Kết quả</h2>
    
    <?php if (empty($modified_files)): ?>
        <p class="warning">Không tìm thấy nội dung cần thay thế trong bất kỳ file nào.</p>
    <?php else: ?>
        <p class="success">Đã tìm và sửa <?php echo count($modified_files); ?> file</p>
        
        <h3>Danh sách file đã sửa:</h3>
        <ul>
            <?php foreach ($modified_files as $file): ?>
                <li><?php echo htmlspecialchars($file['file']); ?> - Đã sửa <?php echo $file['positions']; ?> vị trí</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <p>Tất cả file đã được sao lưu vào: <?php echo htmlspecialchars($backup_dir); ?></p>
    
    <h3>Bước tiếp theo:</h3>
    <ol>
        <li>Tải tất cả file đã sửa lên server</li>
        <li>Kiểm tra lại kết nối API</li>
        <li>Xóa file này khỏi server vì lý do bảo mật</li>
    </ol>
</body>
</html> 