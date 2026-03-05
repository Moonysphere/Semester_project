<?php

namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class GetEditRoleController extends AbstractController
{
    public function process(Request $request): Response
    {
        $roleRepository = new RoleRepository();
        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $role = $roleRepository->findBySlug($slug, 'role');


        return $this->render('role', 'edit', ['role' => $role]);
    }
}
