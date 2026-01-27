<?php


namespace App\Controllers\Api\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetApiUniversController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');


        $universRepository = new UniversRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;

        $search = $_GET['search'] ?? null;

        $queryBuilder = $universRepository->queryBuilder()
            ->select()
            ->from('u') 
            ->where('status', '=')
            ->addParam('status', 'published');

        if ($search) {
            $queryBuilder
                ->andWhere('name', 'LIKE')
                ->addParam('name', '%' . $search . '%');
        }

        $allUnivers = $queryBuilder->executeQuery()->getAllResults();


        $univers = array_slice($allUnivers, $offset, $limit);


        $universData = array_map(function($univers) {
            return [
                'id' => $univers->id,
                'name' => $univers->name,
                'slug' => $univers->slug,
                'description' => $univers->description,
                'createdate' => $univers->createdate,
                'status' => $univers->status
            ];
        }, $univers);


        return new Response(
            json_encode($universData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );  
    }

}