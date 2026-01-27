<?php

namespace App\Controllers\Api\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class GetApiPlacesController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');

        $placeRepository = new PlaceRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;


        $search = $_GET['search'] ?? null;
        $minLevel = isset($_GET['min_level']) ? (int)$_GET['min_level'] : null;
        $maxLevel = isset($_GET['max_level']) ? (int)$_GET['max_level'] : null;

        $queryBuilder = $placeRepository->queryBuilder()
            ->select()
            ->from('p')
            ->where('status', '=')
            ->addParam('status', 'published');


        if ($search) {
            $queryBuilder
                ->andWhere('title', 'LIKE')
                ->addParam('title', '%' . $search . '%');
        }


        $allPlaces = $queryBuilder->executeQuery()->getAllResults();

        $places = array_slice($allPlaces, $offset, $limit);

        $placesData = array_map(function($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'slug' => $place->slug,
                'type' => $place->type,
                'description' => $place->description,   
                'status' => $place->status,

            ];
        }, $places);

        return new Response(
            json_encode($placesData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
