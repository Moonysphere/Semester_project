<?php

namespace App\Controllers\Api\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetApiQuestsController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');

        $questRepository = new QuestRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;


        $search = $_GET['search'] ?? null;
        $minLevel = isset($_GET['min_level']) ? (int)$_GET['min_level'] : null;
        $maxLevel = isset($_GET['max_level']) ? (int)$_GET['max_level'] : null;

        $queryBuilder = $questRepository->queryBuilder()
            ->select()
            ->from('q')
            ->where('status', '=')
            ->addParam('status', 'published');


        if ($search) {
            $queryBuilder
                ->andWhere('title', 'LIKE')
                ->addParam('title', '%' . $search . '%');
        }

        if ($minLevel !== null) {
            $queryBuilder
                ->andWhere('levelrequirements', '>=')
                ->addParam('min_level', $minLevel);
        }

        if ($maxLevel !== null) {
            $queryBuilder
                ->andWhere('levelrequirements', '<=')
                ->addParam('max_level', $maxLevel);
        }


        $allQuests = $queryBuilder->executeQuery()->getAllResults();


        $quests = array_slice($allQuests, $offset, $limit);

        $questsData = array_map(function($quest) {
            return [
                'id' => $quest->id,
                'title' => $quest->title,
                'slug' => $quest->slug,
                'description' => $quest->description,
                'statut_quest' => $quest->statut_quest,
                'levelrequirements' => $quest->levelrequirements,
                'status' => $quest->status
            ];
        }, $quests);

        return new Response(
            json_encode($questsData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
