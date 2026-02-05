<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UserRepository;

class PatchUserRole extends AbstractController
{
    public function process(Request $request): Response
    {
        $payload = $request->getPayload();

        if (is_string($payload)) {
            $data = json_decode($payload, true);
        } else {
            $data = $payload;
        }

        $email = $data['email'] ?? null;
        $newRole = $data['role'] ?? null;

        if (!$email || !$newRole) {
            return new Response(
                json_encode(['success' => false, 'error' => 'Missing email or role']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $userRepo = new UserRepository();
            $user = $userRepo->findByEmail($email);

            if (!$user) {
                return new Response(
                    json_encode(['success' => false, 'error' => 'User not found']),
                    404,
                    ['Content-Type' => 'application/json']
                );
            }

            $user->role = $newRole;
            $userRepo->updateUserRole($user);

            return new Response(
                json_encode(['success' => true, 'message' => 'Role updated']),
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            error_log('Update role error: ' . $e->getMessage());
            return new Response(
                json_encode(['success' => false, 'error' => $e->getMessage()]),
                500,
                ['Content-Type' => 'application/json']
            );
        }
    }
}
