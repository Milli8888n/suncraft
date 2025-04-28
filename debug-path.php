<?php
// Debug các đường dẫn
header('Content-Type: text/plain; charset=utf-8');

echo "=== REQUEST INFO ===\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

echo "=== SERVER INFO ===\n";
echo "SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'off') . "\n";
echo "SERVER_PORT: " . $_SERVER['SERVER_PORT'] . "\n\n";

echo "=== SCRIPT INFO ===\n";
echo "__FILE__: " . __FILE__ . "\n";
echo "__DIR__: " . __DIR__ . "\n";
echo "getcwd(): " . getcwd() . "\n\n";

echo "=== PATH INFO ===\n";
echo "PATH_INFO: " . (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'Not set') . "\n";
echo "PATH_TRANSLATED: " . (isset($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] : 'Not set') . "\n";
echo "ORIG_PATH_INFO: " . (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : 'Not set') . "\n\n";

echo "=== REQUEST METHOD ===\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n\n";

echo "=== URL COMPONENTS ===\n";
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo "Full URL: " . $url . "\n";
$parsedUrl = parse_url($url);
echo "Parsed URL: " . print_r($parsedUrl, true) . "\n\n";

echo "=== ENV VARIABLES ===\n";
$env = getenv();
foreach ($env as $key => $value) {
    echo "$key: $value\n";
} 