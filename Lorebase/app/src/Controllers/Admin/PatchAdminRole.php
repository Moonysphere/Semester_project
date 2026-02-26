<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class PatchAdminRole extends AbstractController
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

        $roleRepository = new RoleRepository();
        $role = $roleRepository->find($request->getSlug('id'));

        if (empty($role)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        if ($request->getMethod() === 'GET') {
            return $this->render('admin', 'edit_role', ['role' => $role]);
        }

        if ($request->getMethod() === 'PATCH') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data)) {
                return new Response(
                    json_encode(['error' => 'Invalid request format']),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }

            $role->name = $data['name'] ?? $role->name;
            $role->slug = $roleRepository->checkSlug("slug", "role", $roleRepository->slugify($data['name'])) ?? $role->slug;
            $role->description = $data['description'] ?? $role->description;

            if (isset($data['status']) && in_array($data['status'], ['draft', 'published', 'archived'], true)) {
                $role->status = $data['status'];
            }

            $roleRepository->update($role);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
