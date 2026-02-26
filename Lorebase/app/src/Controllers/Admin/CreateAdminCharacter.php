<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;
use App\Entities\Character;
use App\Forms\CharacterForm;

class CreateAdminCharacter extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Unauthorized', 403);
        }

        if ($request->getMethod() === 'GET') {
            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getAllRoles();

            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'create_character', ['roles' => $roles, 'universes' => $universes]);
        }

        if ($request->getMethod() === 'POST') {
            error_log('CreateAdminCharacter POST received');
            error_log('POST data: ' . json_encode($_POST));

            $formData = [
                'name' => $_POST['name'] ?? null,
                'slug' => $_POST['slug'] ?? null,
                'description' => $_POST['description'] ?? null,
                'status' => $_POST['status'] ?? 'draft',
                'user_id' => null,
                'role_id' => $_POST['role_id'] ?? null,
                'origin' => $_POST['origin'] ?? null,
                'pv' => $_POST['pv'] ?? 0,
                'univers_id' => $_POST['univers_id'] ?? null,
            ];

            $form = new CharacterForm($formData);
            $character = $form->mapToEntity();

            if (!$character) {
                return $this->render('admin', 'create_character', ['errors' => $form->getErrors()]);
            }


            $character->user_id = null;
            $repo = new CharacterRepository();
            $repo->save($character);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
