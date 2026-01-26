<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UserRepository;

class GetProfileController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!$this->isLoggedIn()) {
            return new Response('', 302, ['Location' => '/login']);
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($_SESSION['user']['email']);

        if (!$user) {
            return new Response('Utilisateur non trouvé', 404);
        }

        return $this->render('auth', 'profile', ['user' => $user]);
    }
}
