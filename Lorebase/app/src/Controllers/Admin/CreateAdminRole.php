<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;
use App\Forms\RoleForm;

class CreateAdminRole extends AbstractController
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
            return $this->render('admin', 'create_role', []);
        }

        if ($request->getMethod() === 'POST') {
            $formData = [
                'name' => $_POST['name'] ?? null,
                'description' => $_POST['description'] ?? null,
                'status' => $_POST['status'] ?? 'draft',
            ];

            $form = new RoleForm($formData);
            $role = $form->mapToEntity();

            if (!$role) {
                return $this->render('admin', 'create_role', ['errors' => $form->getErrors()]);
            }

            $repo = new RoleRepository();
            $repo->save($role);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
