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

        $placeRepository->update($place);

        return new Response(
            json_encode(['success' => true, 'id' => $place->getId()]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
