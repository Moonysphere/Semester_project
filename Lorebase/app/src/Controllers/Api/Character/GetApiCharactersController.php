<?php

namespace App\Controllers\Api\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class GetApiCharactersController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');

        $characterRepository = new CharacterRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;


        $search = $_GET['search'] ?? null;
        $minLevel = isset($_GET['min_level']) ? (int)$_GET['min_level'] : null;
        $maxLevel = isset($_GET['max_level']) ? (int)$_GET['max_level'] : null;

        $queryBuilder = $characterRepository->queryBuilder()
            ->select()
            ->from('c')
            ->where('status', '=')
            ->addParam('status', 'published');


        if ($search) {
            $queryBuilder
                ->andWhere('title', 'LIKE')
                ->addParam('title', '%' . $search . '%');
        }

        if ($minLevel !== null) {
            $queryBuilder
                ->andWhere('pv', '>=')
                ->addParam('min_pv', $minLevel);
        }

        if ($maxLevel !== null) {
            $queryBuilder
                ->andWhere('pv', '<=')
                ->addParam('max_pv', $maxLevel);
        }


        $allCharacters = $queryBuilder->executeQuery()->getAllResults();

        $characters = array_slice($allCharacters, $offset, $limit);

        $charactersData = array_map(function($character) {
            return [
                'id' => $character->id,
                'name' => $character->name,
                'slug' => $character->slug,
                'pv' => $character->pv,
                'description' => $character->description,
                'status' => $character->status
            ];
        }, $characters);
        return new Response(
            json_encode($charactersData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
