<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;
use App\Repositories\UniversRepository;

class GetPlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        $placeRepository = new PlaceRepository();
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $place = $placeRepository->findBySlug($slug, 'place');
        $universName = null;

        if (isset($place->univers_id) && $place->univers_id) {
            $universName = $universRepository->getUniversName($place->univers_id);
        }

        return $this->render('place', 'detail', ['place' => $place, 'universName' => $universName]);
    }
}
