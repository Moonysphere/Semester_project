<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class GetPlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        $placeRepository = new PlaceRepository();

        $place = $placeRepository->find($request->getSlug('id'));

        if (empty($place)) {
            return new Response('Place non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('place', 'detail', ['place' => $place]);
    }
}
