<?php


namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class GetPlacesController extends AbstractController
{
    public function process(Request $request): Response
    {
        $placesRepository = new PlaceRepository();

        $places = $placesRepository->findAll();

        return new Response(json_encode($places), 200, ['Content-Type' => 'application/json']);
    }
}
