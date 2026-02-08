<?php

namespace App\Controllers\Place;

use App\Forms\PlaceForm;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class PostPlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $data = $request->getPayload();

        if ($data === null || $data === '') {
            $data = file_get_contents('php://input');
        }

        $form = new PlaceForm();

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
        // Draft = brouillon donc je sais pas en quel langue le mettre comme dans la BDD on parle anglais
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

        $place = $form->save();

        if ($place === null) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $username = $_SESSION['user']['username'] ?? null;
        $redirectUrl = $username ? "/$username/place" : '/place';

        return new Response('', 302, ['Location' => $redirectUrl]);
    }
}
