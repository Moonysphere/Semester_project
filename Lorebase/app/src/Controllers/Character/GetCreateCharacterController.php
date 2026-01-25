<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;

class GetCreateCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $roleRepository = new RoleRepository();
        $roles = $roleRepository->getAllRoles();

        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();

        return $this->render('character', 'CreateCharacter', ['roles' => $roles, 'universes' => $universes]);
    }
}
