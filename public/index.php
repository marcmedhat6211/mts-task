<?php

require '../vendor/autoload.php';

use App\Core\App;
use App\Core\DotEnv;

$dotEnv = new DotEnv(dirname(__DIR__) . "/.env");
$dotEnv->load();
$app = new App;