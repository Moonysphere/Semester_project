<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class PatchQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        $questRepository = new QuestRepository();
        $quest = $questRepository->find($request->getSlug('id'));

        if (!$quest) {
            return new Response(
                json_encode(['error' => 'Quête non trouvée']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }


        if (isset($data['title'])) {
            $quest->title = $data['title'];
        }
        if (isset($data['description'])) {
            $quest->description = $data['description'];
        }
        if (isset($data['statut_quest'])) {
            $quest->statut_quest = $data['statut_quest'];
        }
        if (isset($data['levelrequirements'])) {
            $quest->levelrequirements = (int) $data['levelrequirements'];
        }


        if (isset($data['status']) && in_array($data['status'], ['draft', 'published', 'archived'], true)) {
            $quest->status = $data['status'];
        }

        $questRepository->update($quest);

        return new Response(
            json_encode(['success' => true, 'id' => $quest->getId()]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}