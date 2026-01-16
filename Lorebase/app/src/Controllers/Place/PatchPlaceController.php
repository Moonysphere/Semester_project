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

        $place->name = 'New name';

        $placeRepository->update($place);

        return new Response(json_encode($place), 200, ['Content-Type' => 'application/json']);
    }
}
