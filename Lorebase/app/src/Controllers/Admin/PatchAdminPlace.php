<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;
use App\Repositories\UniversRepository;
use App\Forms\PlaceForm;

class PatchAdminPlace extends AbstractController
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
            return new Response('Place ID not found', 400);
        }

        $repo = new PlaceRepository();

        if ($request->getMethod() === 'GET') {
            $place = $repo->find($id);
            if (!$place) {
                return new Response('Place not found', 404);
            }

            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'edit_place', ['place' => $place, 'universes' => $universes]);
        }

        if ($request->getMethod() === 'PATCH') {
            $place = $repo->find($id);
            if (!$place) {
                return new Response('Place not found', 404);
            }

            $formData = [
                'name' => $_POST['name'] ?? $place->name,
                'slug' => $_POST['slug'] ?? $place->slug,
                'type' => $_POST['type'] ?? $place->type,
                'description' => $_POST['description'] ?? $place->description,
                'status' => $_POST['status'] ?? $place->status,
                'user_id' => null,
            ];

            $form = new PlaceForm($formData);
            $updatedPlace = $form->mapToEntity();

            if (!$updatedPlace) {
                return $this->render('admin', 'edit_place', ['place' => $place, 'errors' => $form->getErrors()]);
            }

            $updatedPlace->id = $id;
            $updatedPlace->user_id = null;

            $repo->update($updatedPlace);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
