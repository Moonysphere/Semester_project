<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;
use App\Forms\CharacterForm;

class PatchAdminCharacter extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Unauthorized', 403);
        }

        $id = $request->getSlug('id');

        if (!$id) {
            return new Response('Character ID not found', 400);
        }

        $repo = new CharacterRepository();

        if ($request->getMethod() === 'GET') {
            $character = $repo->find($id);
            if (!$character) {
                return new Response('Character not found', 404);
            }

            $roleRepository = new RoleRepository();
            $roles = $roleRepository->getAllRoles();

            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'edit_character', ['character' => $character, 'roles' => $roles, 'universes' => $universes]);
        }

        if ($request->getMethod() === 'PATCH') {
            $character = $repo->find($id);
            if (!$character) {
                return new Response('Character not found', 404);
            }

            $formData = [
                'name' => $_POST['name'] ?? $character->name,
                'slug' => $_POST['slug'] ?? $character->slug,
                'description' => $_POST['description'] ?? $character->description,
                'status' => $_POST['status'] ?? $character->status,
                'user_id' => null, // Toujours null pour les entités par défaut
                'role_id' => $_POST['role_id'] ?? $character->role_id,
                'origin' => $_POST['origin'] ?? $character->origin,
                'pv' => $_POST['pv'] ?? $character->pv,
                'univers_id' => $_POST['univers_id'] ?? $character->univers_id,
            ];

            $form = new CharacterForm($formData);
            $updatedCharacter = $form->mapToEntity();

            if (!$updatedCharacter) {
                return $this->render('admin', 'edit_character', ['character' => $character, 'errors' => $form->getErrors()]);
            }

            $updatedCharacter->id = $id;
            $updatedCharacter->user_id = null;

            $repo->update($updatedCharacter);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
