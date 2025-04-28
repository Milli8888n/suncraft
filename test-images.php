<?php
// Hiển thị tất cả lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Thư mục uploads
$uploadsDir = __DIR__ . '/uploads';

echo "<h1>Danh sách file trong thư mục uploads</h1>";

if (file_exists($uploadsDir) && is_dir($uploadsDir)) {
    $files = scandir($uploadsDir);
    
    echo "<table border='1'>";
    echo "<tr>
            <th>Tên file</th>
            <th>Kích thước</th>
            <th>Ngày sửa đổi</th>
            <th>URL</th>
            <th>Hình ảnh</th>
          </tr>";
    
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $fullPath = $uploadsDir . '/' . $file;
            $fileSize = filesize($fullPath);
            $fileTime = filemtime($fullPath);
            $fileUrl = '/uploads/' . $file;
            
            // Định dạng kích thước file
            if ($fileSize < 1024) {
                $sizeStr = $fileSize . ' B';
            } elseif ($fileSize < 1024 * 1024) {
                $sizeStr = round($fileSize / 1024, 2) . ' KB';
            } else {
                $sizeStr = round($fileSize / (1024 * 1024), 2) . ' MB';
            }
            
            echo "<tr>
                    <td>{$file}</td>
                    <td>{$sizeStr}</td>
                    <td>" . date('Y-m-d H:i:s', $fileTime) . "</td>
                    <td><a href='{$fileUrl}' target='_blank'>{$fileUrl}</a></td>
                    <td><img src='{$fileUrl}' style='max-width: 100px; max-height: 100px;'></td>
                  </tr>";
        }
    }
    
    echo "</table>";
    
    // Form upload file đơn giản
    echo "<h2>Upload file mới</h2>";
    echo "<form action='/api/upload' method='POST' enctype='multipart/form-data'>";
    echo "<input type='file' name='file'><br><br>";
    echo "<input type='submit' value='Upload'>";
    echo "</form>";
} else {
    echo "<p>Thư mục uploads không tồn tại hoặc không thể truy cập được.</p>";
}
?> 