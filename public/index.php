<?php

use App\Kernel\App;

define("APP_PATH", dirname(__DIR__));

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once APP_PATH . '/vendor/autoload.php';

$app = new App();
$app->run();


