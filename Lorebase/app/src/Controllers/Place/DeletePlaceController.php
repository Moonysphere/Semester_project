<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class DeletePlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        $placeRepository = new PlaceRepository();

        $place = $placeRepository->find($request->getSlug('id'));

        if (empty($place)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        $placeRepository->remove($place);

        return new Response('', 204, ['Content-Type' => 'application/json']);
    }
}
