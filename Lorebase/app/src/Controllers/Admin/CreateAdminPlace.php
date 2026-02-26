<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;
use App\Repositories\UniversRepository;
use App\Forms\PlaceForm;

class CreateAdminPlace extends AbstractController
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

        if ($request->getMethod() === 'GET') {
            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'create_place', ['universes' => $universes]);
        }

        if ($request->getMethod() === 'POST') {
            $formData = [
                'name' => $_POST['name'] ?? null,
                'slug' => $_POST['slug'] ?? null,
                'type' => $_POST['type'] ?? null,
                'description' => $_POST['description'] ?? null,
                'univers_id' => $_POST['univers_id'] ?? null,
                'status' => $_POST['status'] ?? 'draft',
                'user_id' => null,
            ];

            $form = new PlaceForm($formData);
            $place = $form->mapToEntity();

            if (!$place) {
                return $this->render('admin', 'create_place', ['errors' => $form->getErrors()]);
            }

            $place->user_id = null;

            $repo = new PlaceRepository();
            $repo->save($place);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
