<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class PatchPlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        $placeRepository = new PlaceRepository();
        $place = $placeRepository->find($request->getSlug('id'));

        if (empty($place)) {
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

        $place->name = $data['name'] ?? $place->name;
        $place->type = $data['type'] ?? $place->type;
        $place->description = $data['description'] ?? $place->description;

        if (isset($data['toggle_status']) && $data['toggle_status']) {
        $newStatus = ($place->status === 'published') ? 'draft' : 'published';
        $placeRepository->setStatut($place->getId(), $newStatus);

        return new Response(
            json_encode(['success' => true, 'id' => $place->getId(), 'status' => $newStatus]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    if (array_key_exists('status', $data) && count($data) === 1) {
        $placeRepository->setStatut($place->getId(), $data['status']);

        return new Response(
            json_encode(['success' => true, 'id' => $place->getId(), 'status' => $data['status']]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
    $place->slug = $placeRepository->checkSlug("slug","place",$placeRepository->slugify($data['name'])) ?? $place->slug;
    $place->status = $data['status'] ?? $place->status;

        $placeRepository->update($place);

        return new Response(
            json_encode(['success' => true, 'id' => $place->getId(), 'slug' => $place->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
