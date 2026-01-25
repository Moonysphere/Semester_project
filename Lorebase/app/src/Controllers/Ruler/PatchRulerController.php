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
        $ruler->slug = $rulerRepository->checkSlug("slug","ruler",$rulerRepository->slugify($data['name'])) ?? $ruler->slug;
        $ruler->categorie = $data['categorie'] ?? $ruler ->categorie;
        $ruler->description = $data['description'] ?? $ruler ->description;

        if (isset($data['toggle_status']) && $data['toggle_status']) {
        $newStatus = ($ruler->status === 'published') ? 'draft' : 'published';
        $rulerRepository->setStatut($ruler->getId(), $newStatus);

        return new Response(
            json_encode(['success' => true, 'id' => $ruler->getId(), 'status' => $newStatus]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    if (array_key_exists('status', $data) && count($data) === 1) {
        $rulerRepository->setStatut($ruler->getId(), $data['status']);

        return new Response(
            json_encode(['success' => true, 'id' => $ruler->getId(), 'status' => $data['status']]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    $ruler->status = $data['status'] ?? $ruler->status;

        $rulerRepository->update($ruler);

            // Retourne un succès JSON (pas de redirection)
        return new Response(
            json_encode(['success' => true, 'id' => $ruler->getId(), 'slug' => $ruler->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
