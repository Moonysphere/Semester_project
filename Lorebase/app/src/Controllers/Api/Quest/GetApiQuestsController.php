<?php

namespace App\Controllers\Api\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractAPIController;
use App\Repositories\QuestRepository;

class GetApiQuestsController extends AbstractAPIController
{
    public function process(Request $request): Response
    {
        $pagination = $this->getPaginationParams();
        $search = $this->getSearchParam();

        $userEmail = null;
        if ($this->hasUserFilter()) {
            $userEmail = $this->getUserFilter();
            if (!$userEmail) {
                return $this->apiError('User not found', 404);
            }
        }

        $repo = new QuestRepository();
        $query = $repo->queryBuilder()
            ->select()
            ->from('quest')
            ->where('status', '=')
            ->addParam('status', 'published');

        $this->applyUserFilter($query, $userEmail);
        if ($search) {
            $this->applySearchFilter($query, $search, 'title');
        }

        $all = $query->executeQuery()->getAllResults();
        $quests = array_slice($all, $pagination['offset'], $pagination['limit']);

        $questsData = array_map(function ($quest) {
            return [
                'id' => $quest->id,
                'title' => $quest->title,
                'slug' => $quest->slug,
                'description' => $quest->description,
                'statut_quest' => $quest->statut_quest,
                'levelrequirements' => $quest->levelrequirements,
                'status' => $quest->status,
                'owner' => $quest->user_id
            ];
        }, $quests);

        return $this->apiResponse(
            $questsData,
            200,
            $this->buildPagination($pagination['page'], $pagination['limit'], count($all))
        );
    }
}
