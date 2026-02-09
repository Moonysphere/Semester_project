<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetUserQuestsController extends AbstractController
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

        $questRepository = new QuestRepository();

        $userQuests = $questRepository->findBy(['user_id' => $userEmail]);
        $defaultQuests = $questRepository->findByDefault();

        if (!$isOwner && !$isAdmin) {
            $userQuests = array_filter($userQuests, function ($quest) {
                return $quest->status === 'published';
            });
        }

        $data = [
            'quests' => $userQuests,
            'defaultQuests' => $defaultQuests,
            'isLoggedIn' => $userEmail !== null,
            'isOwner' => $isOwner,
            'displayedUser' => $user,
            'user' => $this->getCurrentUser(),
        ];

        return $this->render('Quest_views', 'list', $data);
    }
}
