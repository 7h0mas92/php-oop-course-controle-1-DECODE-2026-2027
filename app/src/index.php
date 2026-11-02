<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Router;
use App\Http\Request;

$request = new Request();

$router = new Router();
$router->main($request);


?>
