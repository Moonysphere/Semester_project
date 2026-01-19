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
        $placeRepository = new PlaceRepository();
        $places = $placeRepository->findAll();

        return $this->render('place', 'list', ['places' => $places]);
    }
}
