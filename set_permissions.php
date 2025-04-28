<?php
/**
 * Script thiết lập quyền cho các thư mục và file
 * Sử dụng script này sau khi tải tất cả file lên hosting
 */

// Danh sách thư mục cần quyền 755 (rwxr-xr-x)
$directories = [
    './',
    './uploads',
    './assets',
    './cache',
    './includes',
    './templates',
    './admin'
];

// Danh sách thư mục cần kiểm tra và tạo nếu chưa tồn tại
$required_dirs = [
    './uploads',
    './assets',
    './cache',
    './uploads/posts',
    './uploads/products',
    './uploads/users'
];

// Thiết lập header
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Thiết lập quyền cho Suncraft</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        ul { padding-left: 20px; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Thiết lập quyền cho Suncraft</h1>";

// Tạo các thư mục cần thiết nếu chưa tồn tại
echo "<h2>Tạo thư mục cần thiết</h2><ul>";
foreach ($required_dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<li class='success'>✅ Đã tạo thư mục: $dir</li>";
        } else {
            echo "<li class='error'>❌ Không thể tạo thư mục: $dir</li>";
        }
    } else {
        echo "<li class='success'>✅ Thư mục đã tồn tại: $dir</li>";
    }
}
echo "</ul>";

// Thiết lập quyền cho thư mục
echo "<h2>Thiết lập quyền cho thư mục (755)</h2><ul>";
foreach ($directories as $dir) {
    if (file_exists($dir)) {
        if (chmod($dir, 0755)) {
            echo "<li class='success'>✅ Đã thiết lập quyền 755 cho: $dir</li>";
        } else {
            echo "<li class='error'>❌ Không thể thiết lập quyền cho: $dir</li>";
        }
    } else {
        echo "<li class='warning'>⚠️ Thư mục không tồn tại: $dir</li>";
    }
}
echo "</ul>";

// Thiết lập quyền cho tất cả các file .php (644)
echo "<h2>Thiết lập quyền cho file PHP (644)</h2>";
$count = 0;
$error_count = 0;

$php_files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('.', RecursiveDirectoryIterator::SKIP_DOTS)
);

echo "<ul>";
foreach ($php_files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $file_path = $file->getPathname();
        if (chmod($file_path, 0644)) {
            $count++;
        } else {
            echo "<li class='error'>❌ Không thể thiết lập quyền cho: $file_path</li>";
            $error_count++;
        }
    }
}

if ($error_count === 0) {
    echo "<li class='success'>✅ Đã thiết lập quyền 644 cho $count file PHP</li>";
} else {
    echo "<li class='warning'>⚠️ Đã thiết lập quyền cho $count file PHP, $error_count file lỗi</li>";
}
echo "</ul>";

// Kiểm tra quyền ghi cho các thư mục quan trọng
echo "<h2>Kiểm tra quyền ghi</h2><ul>";
$writable_dirs = ['./uploads', './cache', './assets'];
foreach ($writable_dirs as $dir) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<li class='success'>✅ Thư mục $dir có quyền ghi</li>";
        } else {
            echo "<li class='error'>❌ Thư mục $dir KHÔNG có quyền ghi</li>";
        }
    }
}
echo "</ul>";

// Kiểm tra các file quan trọng
echo "<h2>Kiểm tra file quan trọng</h2><ul>";
$important_files = [
    './index.php',
    './config.php',
    './.htaccess'
];

foreach ($important_files as $file) {
    if (file_exists($file)) {
        echo "<li class='success'>✅ Đã tìm thấy: $file</li>";
    } else {
        echo "<li class='error'>❌ Không tìm thấy: $file</li>";
    }
}
echo "</ul>";

// Kết luận
echo "<h2>Tổng kết</h2>";
echo "<p>Quá trình thiết lập quyền đã hoàn tất. Nếu có bất kỳ lỗi nào hiển thị bên trên, bạn có thể cần thiết lập quyền thủ công.</p>";
echo "<p>Thiết lập quyền thủ công trong cPanel File Manager:</p>";
echo "<ol>
    <li>Chọn thư mục/file bạn muốn thay đổi quyền</li>
    <li>Nhấp chuột phải và chọn 'Change Permissions'</li>
    <li>Đối với thư mục: thiết lập 755 (rwxr-xr-x)</li>
    <li>Đối với file: thiết lập 644 (rw-r--r--)</li>
    <li>Nhấp 'Change Permissions' để lưu thay đổi</li>
</ol>";

echo "<p><strong>Lưu ý:</strong> Sau khi thiết lập quyền, hãy kiểm tra website của bạn để đảm bảo mọi thứ hoạt động bình thường.</p>";
echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='check_database.php'>Kiểm tra kết nối database</a></p>";

echo "</body></html>";
?> 
/**
 * Script thiết lập quyền cho các thư mục và file
 * Sử dụng script này sau khi tải tất cả file lên hosting
 */

// Danh sách thư mục cần quyền 755 (rwxr-xr-x)
$directories = [
    './',
    './uploads',
    './assets',
    './cache',
    './includes',
    './templates',
    './admin'
];

// Danh sách thư mục cần kiểm tra và tạo nếu chưa tồn tại
$required_dirs = [
    './uploads',
    './assets',
    './cache',
    './uploads/posts',
    './uploads/products',
    './uploads/users'
];

// Thiết lập header
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Thiết lập quyền cho Suncraft</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        ul { padding-left: 20px; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Thiết lập quyền cho Suncraft</h1>";

// Tạo các thư mục cần thiết nếu chưa tồn tại
echo "<h2>Tạo thư mục cần thiết</h2><ul>";
foreach ($required_dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<li class='success'>✅ Đã tạo thư mục: $dir</li>";
        } else {
            echo "<li class='error'>❌ Không thể tạo thư mục: $dir</li>";
        }
    } else {
        echo "<li class='success'>✅ Thư mục đã tồn tại: $dir</li>";
    }
}
echo "</ul>";

// Thiết lập quyền cho thư mục
echo "<h2>Thiết lập quyền cho thư mục (755)</h2><ul>";
foreach ($directories as $dir) {
    if (file_exists($dir)) {
        if (chmod($dir, 0755)) {
            echo "<li class='success'>✅ Đã thiết lập quyền 755 cho: $dir</li>";
        } else {
            echo "<li class='error'>❌ Không thể thiết lập quyền cho: $dir</li>";
        }
    } else {
        echo "<li class='warning'>⚠️ Thư mục không tồn tại: $dir</li>";
    }
}
echo "</ul>";

// Thiết lập quyền cho tất cả các file .php (644)
echo "<h2>Thiết lập quyền cho file PHP (644)</h2>";
$count = 0;
$error_count = 0;

$php_files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('.', RecursiveDirectoryIterator::SKIP_DOTS)
);

echo "<ul>";
foreach ($php_files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $file_path = $file->getPathname();
        if (chmod($file_path, 0644)) {
            $count++;
        } else {
            echo "<li class='error'>❌ Không thể thiết lập quyền cho: $file_path</li>";
            $error_count++;
        }
    }
}

if ($error_count === 0) {
    echo "<li class='success'>✅ Đã thiết lập quyền 644 cho $count file PHP</li>";
} else {
    echo "<li class='warning'>⚠️ Đã thiết lập quyền cho $count file PHP, $error_count file lỗi</li>";
}
echo "</ul>";

// Kiểm tra quyền ghi cho các thư mục quan trọng
echo "<h2>Kiểm tra quyền ghi</h2><ul>";
$writable_dirs = ['./uploads', './cache', './assets'];
foreach ($writable_dirs as $dir) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<li class='success'>✅ Thư mục $dir có quyền ghi</li>";
        } else {
            echo "<li class='error'>❌ Thư mục $dir KHÔNG có quyền ghi</li>";
        }
    }
}
echo "</ul>";

// Kiểm tra các file quan trọng
echo "<h2>Kiểm tra file quan trọng</h2><ul>";
$important_files = [
    './index.php',
    './config.php',
    './.htaccess'
];

foreach ($important_files as $file) {
    if (file_exists($file)) {
        echo "<li class='success'>✅ Đã tìm thấy: $file</li>";
    } else {
        echo "<li class='error'>❌ Không tìm thấy: $file</li>";
    }
}
echo "</ul>";

// Kết luận
echo "<h2>Tổng kết</h2>";
echo "<p>Quá trình thiết lập quyền đã hoàn tất. Nếu có bất kỳ lỗi nào hiển thị bên trên, bạn có thể cần thiết lập quyền thủ công.</p>";
echo "<p>Thiết lập quyền thủ công trong cPanel File Manager:</p>";
echo "<ol>
    <li>Chọn thư mục/file bạn muốn thay đổi quyền</li>
    <li>Nhấp chuột phải và chọn 'Change Permissions'</li>
    <li>Đối với thư mục: thiết lập 755 (rwxr-xr-x)</li>
    <li>Đối với file: thiết lập 644 (rw-r--r--)</li>
    <li>Nhấp 'Change Permissions' để lưu thay đổi</li>
</ol>";

echo "<p><strong>Lưu ý:</strong> Sau khi thiết lập quyền, hãy kiểm tra website của bạn để đảm bảo mọi thứ hoạt động bình thường.</p>";
echo "<p><a href='index.php'>Quay về trang chủ</a> | <a href='check_database.php'>Kiểm tra kết nối database</a></p>";

echo "</body></html>";
?> 
 
 