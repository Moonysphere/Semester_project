<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class DeleteQuestCOntroller extends AbstractController
{
    public function process(Request $request): Response
    {
        $questRepository = new QuestRepository();

        $quest = $questRepository->find($request->getSlug('id'));

        if (empty($quest)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        if ($quest->user_id !== $_SESSION['user']['email'] && $_SESSION['user']['role'] !== 'admin') {
            return new Response(json_encode(['error' => 'Unauthorized']), 403, ['Content-Type' => 'application/json']);
        }

        $questRepository->remove($quest);

        return new Response('Quête supprimée', 204, ['Content-Type' => 'application/json', 'Location' => '/quest']);
    }
}
