<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $filePath = __DIR__ . '/../../../views/ruler/CreateRuler.html';

        if (!file_exists($filePath)) {
            return new Response(
                json_encode(['error' => 'Form not found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        $content = file_get_contents($filePath);

        return new Response(
            $content,
            200,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }
}
