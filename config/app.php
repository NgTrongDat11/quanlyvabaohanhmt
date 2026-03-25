<?php
/**
 * Cấu hình ứng dụng
 */

// Nạp biến môi trường từ file .env (nếu có)
$rootPath = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
$envPath = $rootPath . '/.env';
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }
}

// Thông tin cơ bản
define('APP_NAME', getenv('APP_NAME') ?: 'Cao Hùng Tech');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost/TEST');

// Môi trường (development, production)
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Hiển thị lỗi (chỉ trong development)
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Session
session_start();

// Google OAuth 2.0
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: (APP_URL . '/auth/googleCallback'));
