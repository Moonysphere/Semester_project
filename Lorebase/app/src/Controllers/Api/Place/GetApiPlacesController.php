<?php

namespace App\Controllers\Api\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractAPIController;
use App\Repositories\PlaceRepository;

class GetApiPlacesController extends AbstractAPIController
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

        $repo = new PlaceRepository();
        $query = $repo->queryBuilder()
            ->select()
            ->from('place')
            ->where('status', '=')
            ->addParam('status', 'published');

        $this->applyUserFilter($query, $userEmail);
        if ($search) {
            $this->applySearchFilter($query, $search, 'name');
        }

        $all = $query->executeQuery()->getAllResults();
        $places = array_slice($all, $pagination['offset'], $pagination['limit']);

        $placesData = array_map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'slug' => $place->slug,
                'type' => $place->type,
                'description' => $place->description,
                'status' => $place->status,
                'owner' => $place->user_id
            ];
        }, $places);

        return $this->apiResponse(
            $placesData,
            200,
            $this->buildPagination($pagination['page'], $pagination['limit'], count($all))
        );
    }
}
