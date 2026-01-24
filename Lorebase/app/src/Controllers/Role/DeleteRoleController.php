<?php


namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class DeleteRoleController extends AbstractController
{
    public function process(Request $request): Response
    {
        $roleRepository = new RoleRepository();

        $role = $roleRepository->find($request->getSlug('id'));

        if (empty($role)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        $roleRepository->remove($role);

        return new Response('Rôle supprimé', 204, ['Content-Type' => 'application/json', 'Location' => '/role']);
    }
}
