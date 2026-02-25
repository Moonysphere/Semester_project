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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $placeRepository = new PlaceRepository();
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            throw new \Exception('Slug manquant', 400);        }

        $place = $placeRepository->findBySlug($slug, 'place');

        if (!$place) {
            throw new \Exception('Place non trouvé', 404);
        }
        $universName = null;

        if (isset($place->univers_id) && $place->univers_id) {
            $universName = $universRepository->getUniversName($place->univers_id);
        }

        $isOwnEntity = $this->isEntityOwner($place);

        return $this->render('place', 'detail', ['place' => $place, 'universName' => $universName, 'isOwnEntity' => $isOwnEntity]);
    }
}
