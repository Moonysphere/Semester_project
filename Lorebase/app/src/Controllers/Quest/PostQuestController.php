<?php

namespace App\Controllers\Quest;

use App\Forms\QuestForm;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class PostQuestController extends AbstractController
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

        $form = new QuestForm();

        if (is_string($data)) {
            $data = $form->parseStringToArray($data);

            if (empty($data)) {
                return new Response(
                    json_encode(['error' => 'Invalid request format']),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }
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

        if (!in_array($data['status'], ['draft', 'published', 'archived'], true)) {
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

        $quest = $form->save();

        if ($quest === null) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $username = $_SESSION['user']['username'] ?? null;
        $redirectUrl = $username ? "/$username/quest" : '/quest';

        return new Response('', 302, ['Location' => $redirectUrl]);
    }
}
