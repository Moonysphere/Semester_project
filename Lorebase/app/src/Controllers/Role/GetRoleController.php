<?php


namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class GetRoleController extends AbstractController
{
    public function process(Request $request): Response
    {
        $roleRepository = new RoleRepository();

        $role = $roleRepository->find($request->getSlug('id'));

        if (empty($role)) {
            return new Response('Rôle non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('role', 'detail', ['role' => $role]);
    }
}
