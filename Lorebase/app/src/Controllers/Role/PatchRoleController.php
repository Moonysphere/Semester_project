<?php

namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RoleRepository;

class PatchRoleController extends AbstractController
{
    public function process(Request $request): Response
    {
        $roleRepository = new RoleRepository();
        $role = $roleRepository->find($request->getSlug('id'));

        if (empty($role)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

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
}
