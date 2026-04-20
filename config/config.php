<?php

define('APP_NAME', 'AlicerceDesk');

// 🔥 BASE PARA WAMP
define('BASE_URL', '/alicercedesk/public/');

define('BASE_PATH', dirname(__DIR__));

define('ENV', 'local');

if (ENV === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

date_default_timezone_set('America/Sao_Paulo');

define('ASSETS_URL', BASE_URL . 'assets/');
define('CSS_URL', ASSETS_URL . 'css/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}