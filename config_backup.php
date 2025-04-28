<?php
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup/config_files';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Danh sách các file cấu hình cần sao lưu
$configFiles = [
    'api/config.php',
    'api/db.php',
    '.htaccess',
    'api/helpers.php',
    'api/router.php',
    'api/login.php',
    'api/check-auth.php',
    'api/auth.php'
];

// Sao chép từng file
$count = 0;
foreach ($configFiles as $file) {
    if (file_exists($file)) {
        $destFile = $backupDir . '/' . basename($file);
        if (copy($file, $destFile)) {
            echo "Đã sao lưu: $file\n";
            $count++;
        } else {
            echo "Lỗi sao lưu: $file\n";
        }
    } else {
        echo "File không tồn tại: $file\n";
    }
}

echo "Đã sao lưu $count file cấu hình vào thư mục $backupDir";
?> 
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup/config_files';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Danh sách các file cấu hình cần sao lưu
$configFiles = [
    'api/config.php',
    'api/db.php',
    '.htaccess',
    'api/helpers.php',
    'api/router.php',
    'api/login.php',
    'api/check-auth.php',
    'api/auth.php'
];

// Sao chép từng file
$count = 0;
foreach ($configFiles as $file) {
    if (file_exists($file)) {
        $destFile = $backupDir . '/' . basename($file);
        if (copy($file, $destFile)) {
            echo "Đã sao lưu: $file\n";
            $count++;
        } else {
            echo "Lỗi sao lưu: $file\n";
        }
    } else {
        echo "File không tồn tại: $file\n";
    }
}

echo "Đã sao lưu $count file cấu hình vào thư mục $backupDir";
?> 
 
 