<?php
// Kết nối đến database thông qua file cấu hình
require_once 'api/config.php';
require_once 'api/db.php';

// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$backupFile = $backupDir . '/suncraft_db_' . date('Y-m-d_H-i-s') . '.sql';

// Thông tin kết nối
$host = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

// Lệnh mysqldump
$command = "mysqldump -h {$host} -P {$port} -u {$user} ";
if ($pass) {
    $command .= "-p'{$pass}' ";
}
$command .= "{$dbname} > \"{$backupFile}\"";

// Thực thi lệnh
$output = [];
$returnVar = 0;
exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Sao lưu cơ sở dữ liệu thành công: " . $backupFile;
} else {
    echo "Có lỗi khi sao lưu cơ sở dữ liệu.<br>";
    echo "Thử dùng phương pháp thay thế...<br>";
    
    // Phương pháp thay thế: Tạo script SQL
    try {
        $db = Database::getInstance();
        
        // Mở file backup để ghi
        $fileHandle = fopen($backupFile, 'w');
        
        // Lấy danh sách tất cả bảng
        $tablesQuery = "SHOW TABLES";
        $tables = $db->fetchAll($tablesQuery);
        
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            
            // Thêm comment và lệnh DROP TABLE nếu tồn tại
            fwrite($fileHandle, "-- Table structure for table `$tableName`\n");
            fwrite($fileHandle, "DROP TABLE IF EXISTS `$tableName`;\n");
            
            // Lấy cấu trúc bảng
            $structureQuery = "SHOW CREATE TABLE `$tableName`";
            $structure = $db->fetch($structureQuery);
            
            if ($structure) {
                $createTableSql = array_values($structure)[1];
                fwrite($fileHandle, $createTableSql . ";\n\n");
                
                // Lấy dữ liệu của bảng
                $dataQuery = "SELECT * FROM `$tableName`";
                $data = $db->fetchAll($dataQuery);
                
                if (count($data) > 0) {
                    fwrite($fileHandle, "-- Dumping data for table `$tableName`\n");
                    fwrite($fileHandle, "INSERT INTO `$tableName` VALUES ");
                    
                    $rows = [];
                    foreach ($data as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $values[] = "NULL";
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $rows[] = "(" . implode(',', $values) . ")";
                    }
                    
                    fwrite($fileHandle, implode(",\n", $rows) . ";\n\n");
                }
            }
        }
        
        fclose($fileHandle);
        echo "Sao lưu cơ sở dữ liệu thành công bằng phương pháp thay thế: " . $backupFile;
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}
?> 
// Kết nối đến database thông qua file cấu hình
require_once 'api/config.php';
require_once 'api/db.php';

// Tạo thư mục backup nếu chưa tồn tại
$backupDir = __DIR__ . '/suncraft_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Tên file backup
$backupFile = $backupDir . '/suncraft_db_' . date('Y-m-d_H-i-s') . '.sql';

// Thông tin kết nối
$host = DB_HOST;
$port = DB_PORT;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

// Lệnh mysqldump
$command = "mysqldump -h {$host} -P {$port} -u {$user} ";
if ($pass) {
    $command .= "-p'{$pass}' ";
}
$command .= "{$dbname} > \"{$backupFile}\"";

// Thực thi lệnh
$output = [];
$returnVar = 0;
exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Sao lưu cơ sở dữ liệu thành công: " . $backupFile;
} else {
    echo "Có lỗi khi sao lưu cơ sở dữ liệu.<br>";
    echo "Thử dùng phương pháp thay thế...<br>";
    
    // Phương pháp thay thế: Tạo script SQL
    try {
        $db = Database::getInstance();
        
        // Mở file backup để ghi
        $fileHandle = fopen($backupFile, 'w');
        
        // Lấy danh sách tất cả bảng
        $tablesQuery = "SHOW TABLES";
        $tables = $db->fetchAll($tablesQuery);
        
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            
            // Thêm comment và lệnh DROP TABLE nếu tồn tại
            fwrite($fileHandle, "-- Table structure for table `$tableName`\n");
            fwrite($fileHandle, "DROP TABLE IF EXISTS `$tableName`;\n");
            
            // Lấy cấu trúc bảng
            $structureQuery = "SHOW CREATE TABLE `$tableName`";
            $structure = $db->fetch($structureQuery);
            
            if ($structure) {
                $createTableSql = array_values($structure)[1];
                fwrite($fileHandle, $createTableSql . ";\n\n");
                
                // Lấy dữ liệu của bảng
                $dataQuery = "SELECT * FROM `$tableName`";
                $data = $db->fetchAll($dataQuery);
                
                if (count($data) > 0) {
                    fwrite($fileHandle, "-- Dumping data for table `$tableName`\n");
                    fwrite($fileHandle, "INSERT INTO `$tableName` VALUES ");
                    
                    $rows = [];
                    foreach ($data as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $values[] = "NULL";
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $rows[] = "(" . implode(',', $values) . ")";
                    }
                    
                    fwrite($fileHandle, implode(",\n", $rows) . ";\n\n");
                }
            }
        }
        
        fclose($fileHandle);
        echo "Sao lưu cơ sở dữ liệu thành công bằng phương pháp thay thế: " . $backupFile;
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}
?> 
 
 