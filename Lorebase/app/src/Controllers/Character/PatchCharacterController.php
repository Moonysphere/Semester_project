<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class PatchCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        $characterRepository = new CharacterRepository();
        $character = $characterRepository->find($request->getSlug('id'));

        if (empty($character)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $character->name = $data['name'] ?? $character->name;
        $character->role = $data['role'] ?? $character->role;
        $character->origin = $data['origin'] ?? $character->origin;
        $character->pv = $data['pv'] ?? $character->pv;
        $character->description = $data['description'] ?? $character->description;

        $characterRepository->update($character);

        // Retourne un succès JSON (pas de redirection)
        return new Response(
            json_encode(['success' => true, 'id' => $character->getId()]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
