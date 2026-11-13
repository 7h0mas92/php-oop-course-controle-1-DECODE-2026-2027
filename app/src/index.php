<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Lib\Http\Router;
use App\Lib\Http\Request;

$request = new Request();

$router = new Router();
$response = $router->main($request);

// on envoie la réponse au client
http_response_code($response->statusCode);
echo $response->content;
