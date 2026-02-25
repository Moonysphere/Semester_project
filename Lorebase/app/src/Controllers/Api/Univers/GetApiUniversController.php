<?php

namespace App\Controllers\Api\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractAPIController;
use App\Repositories\UniversRepository;

class GetApiUniversController extends AbstractAPIController
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

        $repo = new UniversRepository();
        $query = $repo->queryBuilder()
            ->select()
            ->from('univers')
            ->where('status', '=')
            ->addParam('status', 'published');

        $this->applyUserFilter($query, $userEmail);
        if ($search) {
            $this->applySearchFilter($query, $search, 'name');
        }

        $all = $query->executeQuery()->getAllResults();
        $univers = array_slice($all, $pagination['offset'], $pagination['limit']);

        $universData = array_map(function ($univers) {
            return [
                'id' => $univers->id,
                'name' => $univers->name,
                'slug' => $univers->slug,
                'description' => $univers->description,
                'createdate' => $univers->createdate,
                'status' => $univers->status,
                'owner' => $univers->user_id
            ];
        }, $univers);

        return $this->apiResponse(
            $universData,
            200,
            $this->buildPagination($pagination['page'], $pagination['limit'], count($all))
        );
    }
}
