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
        $role->description = $data['description'] ?? $role->description;

        $roleRepository->update($role);

        // Retourne un succès JSON (pas de redirection)
        return new Response(
            json_encode(['success' => true, 'id' => $role->getId()]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
