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

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $place = $placeRepository->findBySlug($slug, 'place');

        return $this->render('place', 'detail', ['place' => $place]);
    }
}
