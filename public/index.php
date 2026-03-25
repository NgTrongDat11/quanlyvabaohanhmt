<?php
/**
 * Entry Point - Front Controller
 * Đây là điểm vào duy nhất của ứng dụng
 */

// Thiết lập đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));

// Require các file cần thiết
require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/helpers/helpers.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/App.php';

// Khởi chạy ứng dụng
$app = new App();
