<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetUserUniversController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userInfo = $this->getUserFromUsername($request);

        if (!$userInfo) {
            return new Response('Utilisateur introuvable', 404);
        }

        $user = $userInfo['user'];
        $isOwner = $userInfo['isOwner'];
        $isAdmin = $this->isAdmin();
        $userEmail = $userInfo['currentUserEmail'];

        $universRepository = new UniversRepository();

        $userUniverses = $universRepository->findBy(['user_id' => $userEmail]);
        $defaultUniverses = $universRepository->findByDefault();

        if (!$isOwner && !$isAdmin) {
            $userUniverses = array_filter($userUniverses, function ($univers) {
                return $univers->status === 'published';
            });
        }

        $data = [
            'univers' => $userUniverses,
            'defaultUniverses' => $defaultUniverses,
            'isLoggedIn' => $userEmail !== null,
            'isOwner' => $isOwner,
            'displayedUser' => $user,
            'user' => $this->getCurrentUser(),
        ];

        return $this->render('univers', 'list', $data);
    }
}
