<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;
use App\Forms\UniversForm;

class PatchAdminUnivers extends AbstractController
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
            return new Response('Univers ID not found', 400);
        }

        $repo = new UniversRepository();

        if ($request->getMethod() === 'GET') {
            $univers = $repo->find($id);
            if (!$univers) {
                return new Response('Univers not found', 404);
            }
            return $this->render('admin', 'edit_univers', ['univers' => $univers]);
        }

        if ($request->getMethod() === 'PATCH') {
            $univers = $repo->find($id);
            if (!$univers) {
                return new Response('Univers not found', 404);
            }

            $formData = [
                'name' => $_POST['name'] ?? $univers->name,
                'slug' => $_POST['slug'] ?? $univers->slug,
                'description' => $_POST['description'] ?? $univers->description,
                'status' => $_POST['status'] ?? $univers->status,
                'user_id' => null,
            ];

            $form = new UniversForm($formData);
            $updatedUnivers = $form->mapToEntity();

            if (!$updatedUnivers) {
                return $this->render('admin', 'edit_univers', ['univers' => $univers, 'errors' => $form->getErrors()]);
            }

            $updatedUnivers->id = $id;
            $updatedUnivers->user_id = null;

            $repo->update($updatedUnivers);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
