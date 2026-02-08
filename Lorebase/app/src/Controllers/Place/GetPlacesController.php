<?php


namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class GetPlacesController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $placeRepository = new PlaceRepository();
        $places = $placeRepository->findByDefault();

        return $this->render('place', 'defaultList', ['places' => $places]);
    }
}
