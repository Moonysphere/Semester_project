<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetPatchQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        $questRepository = new QuestRepository();
        $quest = $questRepository->find($request->getSlug('id'));

        if (empty($quest)) {
            return new Response('Quête non trouvée', 404, ['Content-Type' => 'application/json']);
        }
    
    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_array($data)) {
        return new Response(
            json_encode(['error' => 'Invalid request format']),
            400,
            ['Content-Type' => 'application/json']
        );
    }   

    $quest->title = $data['title'] ?? $quest->title;
    $quest->description = $data['description'] ?? $quest->description;
    $quest->statut = $data['statut'] ?? $quest->statut;
    $quest->statutPlaceId = $data['statutPlaceId'] ?? $quest->statutPlaceId;
    $quest->universeId = $data['universeId'] ?? $quest->universeId;
    $quest->levelRequirement = $data['levelRequirement'] ?? $quest->levelRequirement;

    $questRepository->update($quest);

    return new Response(
            json_encode(['success' => true, 'id' => $quest->getId()]),
            200,
            ['Content-Type' => 'application/json']
        );

        
    }
}