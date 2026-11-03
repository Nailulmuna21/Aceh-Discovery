<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Mode maintenance / Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoload Composer / Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Jalankan aplikasi / Boot the application
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
