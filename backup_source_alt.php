<?php
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$zipFile = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s') . '.zip';

// Sử dụng lệnh hệ thống để tạo file zip
// Trong Windows, ta sẽ dùng 7-Zip nếu có
// Hoặc sử dụng PowerShell để tạo file zip
$command = "powershell.exe -command \"Get-ChildItem -Path . -Exclude suncraft_backup | Compress-Archive -DestinationPath '$zipFile' -Force\"";

// Thực thi lệnh
$output = [];
$returnVar = 0;
exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Đã tạo file backup mã nguồn: " . $zipFile;
} else {
    echo "Có lỗi khi tạo file backup. Mã lỗi: " . $returnVar . "\n";
    echo "Output: ";
    echo implode("\n", $output);
    
    // Phương pháp thay thế: sao chép từng file
    echo "\nThử phương pháp thay thế...\n";
    
    // Tạo thư mục lưu trữ source code
    $sourceDir = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s');
    mkdir($sourceDir, 0755, true);
    
    // Danh sách thư mục và file cần loại trừ
    $excludes = ['suncraft_backup', '.git', 'node_modules', 'vendor'];
    
    // Hàm đệ quy sao chép files và thư mục
    copyDirectory(__DIR__, $sourceDir, $excludes);
    
    echo "Đã sao chép files vào thư mục: " . $sourceDir;
}

// Hàm sao chép thư mục đệ quy
function copyDirectory($source, $destination, $excludes) {
    if (in_array(basename($source), $excludes)) {
        return;
    }
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $handle = opendir($source);
    while (($file = readdir($handle)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        if (in_array($file, $excludes)) {
            continue;
        }
        
        $sourcePath = $source . '/' . $file;
        $destPath = $destination . '/' . $file;
        
        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destPath, $excludes);
        } else {
            copy($sourcePath, $destPath);
        }
    }
    
    closedir($handle);
}
?> 
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$zipFile = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s') . '.zip';

// Sử dụng lệnh hệ thống để tạo file zip
// Trong Windows, ta sẽ dùng 7-Zip nếu có
// Hoặc sử dụng PowerShell để tạo file zip
$command = "powershell.exe -command \"Get-ChildItem -Path . -Exclude suncraft_backup | Compress-Archive -DestinationPath '$zipFile' -Force\"";

// Thực thi lệnh
$output = [];
$returnVar = 0;
exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Đã tạo file backup mã nguồn: " . $zipFile;
} else {
    echo "Có lỗi khi tạo file backup. Mã lỗi: " . $returnVar . "\n";
    echo "Output: ";
    echo implode("\n", $output);
    
    // Phương pháp thay thế: sao chép từng file
    echo "\nThử phương pháp thay thế...\n";
    
    // Tạo thư mục lưu trữ source code
    $sourceDir = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s');
    mkdir($sourceDir, 0755, true);
    
    // Danh sách thư mục và file cần loại trừ
    $excludes = ['suncraft_backup', '.git', 'node_modules', 'vendor'];
    
    // Hàm đệ quy sao chép files và thư mục
    copyDirectory(__DIR__, $sourceDir, $excludes);
    
    echo "Đã sao chép files vào thư mục: " . $sourceDir;
}

// Hàm sao chép thư mục đệ quy
function copyDirectory($source, $destination, $excludes) {
    if (in_array(basename($source), $excludes)) {
        return;
    }
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $handle = opendir($source);
    while (($file = readdir($handle)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        if (in_array($file, $excludes)) {
            continue;
        }
        
        $sourcePath = $source . '/' . $file;
        $destPath = $destination . '/' . $file;
        
        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destPath, $excludes);
        } else {
            copy($sourcePath, $destPath);
        }
    }
    
    closedir($handle);
}
?> 
 
 