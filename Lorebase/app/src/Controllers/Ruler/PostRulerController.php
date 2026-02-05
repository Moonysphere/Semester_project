<?php

namespace App\Controllers\Ruler;

use App\Forms\RulerForm;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class PostRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $data = $request->getPayload();

        if ($data === null || $data === '') {
            $data = file_get_contents('php://input');
        }

        $form = new RulerForm();

        if (is_string($data)) {
            $parsedData = $form->parseStringToArray($data);

            if ($parsedData === null || empty($parsedData)) {
                return new Response(
                    json_encode(['error' => 'Invalid request format']),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }

            $data = $parsedData;
        }

        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $data['status'] = $data['status'] ?? 'draft';
        $data['user_id'] = $_SESSION['user']['email'] ?? null;

        if (!in_array($data['status'], ['draft', 'published'], true)) {
            return new Response(
                json_encode(['error' => 'Invalid status']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $form->setData($data);

        if (!$form->validateRequiredFields()) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $ruler = $form->save();

        if ($ruler === null) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response('', 302, ['Location' => '/ruler']);
    }
}
