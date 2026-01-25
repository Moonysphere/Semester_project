<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;
use App\Repositories\PlaceRepository;

class GetCreateQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();

        $placesRepository = new PlaceRepository();
        $places = $placesRepository->getAllPlaces();

        return $this->render('Quest_views', 'Quest', ['universes' => $universes, 'places' => $places]);
    }
}
