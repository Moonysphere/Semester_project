<?php

namespace App\Controllers\Api\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractAPIController;
use App\Repositories\RoleRepository;

class GetApiRolesController extends AbstractAPIController
{
    public function process(Request $request): Response
    {
        $pagination = $this->getPaginationParams();
        $search = $this->getSearchParam();

        $repo = new RoleRepository();
        $query = $repo->queryBuilder()
            ->select()
            ->from('role')
            ->where('status', '=')
            ->addParam('status', 'published');

        if ($search) {
            $this->applySearchFilter($query, $search, 'name');
        }

        $all = $query->executeQuery()->getAllResults();
        $roles = array_slice($all, $pagination['offset'], $pagination['limit']);

        $rolesData = array_map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'status' => $role->status
            ];
        }, $roles);

        return $this->apiResponse(
            $rolesData,
            200,
            $this->buildPagination($pagination['page'], $pagination['limit'], count($all))
        );
    }
}
