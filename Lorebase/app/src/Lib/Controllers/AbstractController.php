<?php

namespace App\Lib\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;

abstract class AbstractController {
    public abstract function process(Request $request): Response;

    protected function render(string $file ,string $template, array $data = []): Response
    {
        $response = new Response();
        extract($data);
        ob_start();
        require_once __DIR__ . "/../../../views/{$file}/{$template}.html";
        $response->setContent(ob_get_clean());
        $response->addHeader('Content-Type', 'text/html');

        return $response;
    }
    
}
