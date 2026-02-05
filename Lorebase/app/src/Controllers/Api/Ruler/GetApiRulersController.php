<?php

namespace App\Controllers\Api\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class GetApiRulersController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');

        $rulerRepository = new RulerRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;


        $search = $_GET['search'] ?? null;
        

        $queryBuilder = $rulerRepository->queryBuilder()
            ->select()
            ->from('r')
            ->where('status', '=')
            ->addParam('status', 'published');


        if ($search) {
            $queryBuilder
                ->andWhere('title', 'LIKE')
                ->addParam('title', '%' . $search . '%');
        }


        $allRulers = $queryBuilder->executeQuery()->getAllResults();


        $rulers = array_slice($allRulers, $offset, $limit);

        $rulersData = array_map(function($ruler) {
            return [
                'id' => $ruler->id,
                'name' => $ruler->name,
                'slug' => $ruler->slug,
                'categorie' => $ruler->categorie,
                'description' => $ruler->description,
                'status' => $ruler->status
            ];
        }, $rulers);
        return new Response(
            json_encode($rulersData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
