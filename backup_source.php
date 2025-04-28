<?php
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$zipFile = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s') . '.zip';

// Danh sách thư mục và file cần loại trừ
$excludes = [
    'suncraft_backup',
    'backup_source.php',
    'backup_db.php',
    '.git',
    'node_modules',
    'vendor'
];

// Tạo đối tượng ZipArchive
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
    exit("Không thể tạo file zip");
}

// Hàm đệ quy để thêm files và thư mục vào zip
function addDirToZip($dir, $zipArchive, $excludes, $zipDir = '') {
    if (in_array(basename($dir), $excludes)) {
        return;
    }
    
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            
            $fullPath = $dir . '/' . $entry;
            $zipPath = $zipDir . ($zipDir ? '/' : '') . $entry;
            
            if (in_array($entry, $excludes)) {
                continue;
            }
            
            if (is_dir($fullPath)) {
                $zipArchive->addEmptyDir($zipPath);
                addDirToZip($fullPath, $zipArchive, $excludes, $zipPath);
            } else {
                $zipArchive->addFile($fullPath, $zipPath);
            }
        }
        closedir($handle);
    }
}

// Thêm tất cả files và thư mục từ thư mục hiện tại
addDirToZip(__DIR__, $zip, $excludes);

// Đóng file zip
$zip->close();

echo "Đã tạo file backup mã nguồn: " . $zipFile;
?> 
// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$zipFile = $backupDir . '/suncraft_source_' . date('Y-m-d_H-i-s') . '.zip';

// Danh sách thư mục và file cần loại trừ
$excludes = [
    'suncraft_backup',
    'backup_source.php',
    'backup_db.php',
    '.git',
    'node_modules',
    'vendor'
];

// Tạo đối tượng ZipArchive
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
    exit("Không thể tạo file zip");
}

// Hàm đệ quy để thêm files và thư mục vào zip
function addDirToZip($dir, $zipArchive, $excludes, $zipDir = '') {
    if (in_array(basename($dir), $excludes)) {
        return;
    }
    
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            
            $fullPath = $dir . '/' . $entry;
            $zipPath = $zipDir . ($zipDir ? '/' : '') . $entry;
            
            if (in_array($entry, $excludes)) {
                continue;
            }
            
            if (is_dir($fullPath)) {
                $zipArchive->addEmptyDir($zipPath);
                addDirToZip($fullPath, $zipArchive, $excludes, $zipPath);
            } else {
                $zipArchive->addFile($fullPath, $zipPath);
            }
        }
        closedir($handle);
    }
}

// Thêm tất cả files và thư mục từ thư mục hiện tại
addDirToZip(__DIR__, $zip, $excludes);

// Đóng file zip
$zip->close();

echo "Đã tạo file backup mã nguồn: " . $zipFile;
?> 
 
 