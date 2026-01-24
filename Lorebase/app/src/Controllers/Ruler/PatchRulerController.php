<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class PatchRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $rulerRepository = new RulerRepository();
        $ruler = $rulerRepository->find($request->getSlug('id'));

        if (empty($ruler)) {
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

        $ruler->name = $data['name'] ?? $ruler ->name;
        $ruler->slug = $rulerRepository->slugify($data['name']) ?? $ruler->slug;
        $ruler->categorie = $data['categorie'] ?? $ruler ->categorie;
        $ruler->description = $data['description'] ?? $ruler ->description;

        $rulerRepository->update($ruler);

            // Retourne un succès JSON (pas de redirection)
        return new Response(
            json_encode(['success' => true, 'id' => $ruler->getId(), 'slug' => $ruler->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
