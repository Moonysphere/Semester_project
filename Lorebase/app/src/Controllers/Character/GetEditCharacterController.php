<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;

class GetEditCharacterController extends AbstractController
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
        $characterRepository = new CharacterRepository();
        $slug = $request->getSlug('slug');


        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $character = $characterRepository->findBySlug($slug, 'character');

        return $this->render('character', 'edit', ['character' => $character, 'roles' => $roles, 'universes' => $universes]);
    }
}
