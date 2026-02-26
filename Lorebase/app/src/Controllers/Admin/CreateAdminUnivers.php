<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;
use App\Forms\UniversForm;

class CreateAdminUnivers extends AbstractController
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
            return $this->render('admin', 'create_univers', []);
        }

        if ($request->getMethod() === 'POST') {
            $formData = [
                'name' => $_POST['name'] ?? null,
                'slug' => $_POST['slug'] ?? null,
                'description' => $_POST['description'] ?? null,
                'status' => $_POST['status'] ?? 'draft',
                'user_id' => null,
            ];

            $form = new UniversForm($formData);
            $univers = $form->mapToEntity();

            if (!$univers) {
                return $this->render('admin', 'create_univers', ['errors' => $form->getErrors()]);
            }

            $univers->user_id = null;

            $repo = new UniversRepository();
            $repo->save($univers);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
