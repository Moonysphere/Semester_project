<?php

use App\Lib\Http\Request;
use App\Lib\Http\Router;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $request = new Request();
    $response = Router::route($request);

    foreach ($response->getHeaders() as $headerName => $headerValue) {
        header("$headerName: $headerValue");
    }
    http_response_code($response->getStatus());
    echo $response->getContent();
    exit();
} catch (\Exception $e) {
    echo $e->getMessage();
}
