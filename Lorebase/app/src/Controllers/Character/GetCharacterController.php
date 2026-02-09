<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;

class GetCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $characterRepository = new CharacterRepository();
        $roleRepository = new RoleRepository();
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $character = $characterRepository->findBySlug($slug, 'character');

        if (!$character) {
            return new Response('Personnage non trouvé', 404, ['Content-Type' => 'application/json']);
        }

        $roleName = null;

        if (isset($character->role_id) && $character->role_id) {
            $roleName = $roleRepository->getRoleName($character->role_id);
        }

        $universName = null;

        if (isset($character->univers_id) && $character->univers_id) {
            $universName = $universRepository->getUniversName($character->univers_id);
        }

        $isOwnEntity = $this->isEntityOwner($character);

        return $this->render('character', 'detail', ['character' => $character, 'roleName' => $roleName, 'universName' => $universName, 'isOwnEntity' => $isOwnEntity]);
    }
}
