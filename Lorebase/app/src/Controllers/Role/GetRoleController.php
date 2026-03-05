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

         $slug = $request->getSlug('slug');

        if ($slug === '') {
            throw new \Exception('Slug manquant', 404);
        }

        $role = $roleRepository->findBySlug($slug, 'role');

        if (!$role) {
            throw new \Exception('Rôle non trouvé', 404);
        }

        return $this->render('role', 'detail', ['role' => $role]);
    }
}
