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
        $character->slug = $characterRepository->slugify($data['name']) ?? $character->slug;
        $character->role = $data['role'] ?? $character->role;
        $character->origin = $data['origin'] ?? $character->origin;
        $character->pv = $data['pv'] ?? $character->pv;
        $character->description = $data['description'] ?? $character->description;

        // Si seulement status envoyé, utiliser setStatut()
        if (array_key_exists('status', $data) && count($data) === 1) {
            if (in_array($data['status'], ['draft', 'published', 'archived'], true)) {
                $characterRepository->setStatut($character->getId(), $data['status']);

                return new Response(
                    json_encode(['success' => true, 'id' => $character->getId(), 'status' => $data['status']]),
                    200,
                    ['Content-Type' => 'application/json']
                );
            }
        }

        // Sinon mise à jour complète
        if (isset($data['status']) && in_array($data['status'], ['draft', 'published', 'archived'], true)) {
            $character->status = $data['status'];
        }

        $characterRepository->update($character);

        return new Response(
            json_encode(['success' => true, 'id' => $character->getId(),'slug' => $character->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
