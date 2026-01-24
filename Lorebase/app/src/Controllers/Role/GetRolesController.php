<?php


namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class GetRolesController extends AbstractController
{
    public function process(Request $request): Response
    {
        $roleRepository = new RoleRepository();
        $roles = $roleRepository->findAll();

        return $this->render('role', 'list', ['roles' => $roles]);
    }
}
