<?php

namespace App\Controllers\Api\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class GetApiRolesController extends AbstractController
{
    public function process(Request $request): Response
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');

        $roleRepository = new RoleRepository();


        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
        $offset = ($page - 1) * $limit;


        $search = $_GET['search'] ?? null;

        $queryBuilder = $roleRepository->queryBuilder()
            ->select()
            ->from('r')
            ->where('status', '=')
            ->addParam('status', 'published');


        if ($search) {
            $queryBuilder
                ->andWhere('name', 'LIKE')
                ->addParam('name', '%' . $search . '%');
        }


        $allRoles = $queryBuilder->executeQuery()->getAllResults();

        $roles = array_slice($allRoles, $offset, $limit);

        $rolesData = array_map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'status' => $role->status
            ];
        }, $roles);

        return new Response(
            json_encode($rolesData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
