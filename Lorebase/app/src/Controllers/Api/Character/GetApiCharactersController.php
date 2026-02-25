<?php

namespace App\Controllers\Api\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractAPIController;
use App\Repositories\CharacterRepository;

class GetApiCharactersController extends AbstractAPIController
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

        $repo = new CharacterRepository();
        $query = $repo->queryBuilder()
            ->select()
            ->from('character')
            ->where('status', '=')
            ->addParam('status', 'published');

        $this->applyUserFilter($query, $userEmail);
        if ($search) {
            $this->applySearchFilter($query, $search, 'name');
        }

        $all = $query->executeQuery()->getAllResults();
        $characters = array_slice($all, $pagination['offset'], $pagination['limit']);

        $charactersData = array_map(function ($character) {
            return [
                'id' => $character->id,
                'name' => $character->name,
                'slug' => $character->slug,
                'description' => $character->description,
                'status' => $character->status,
                'owner' => $character->user_id
            ];
        }, $characters);

        return $this->apiResponse(
            $charactersData,
            200,
            $this->buildPagination($pagination['page'], $pagination['limit'], count($all))
        );
    }
}
