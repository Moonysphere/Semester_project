<?php


namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class GetUserCharactersController extends AbstractController
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

        $characterRepository = new CharacterRepository();

        $userCharacters = $characterRepository->findBy(['user_id' => $userEmail]);
        $defaultCharacters = $characterRepository->findByDefault();

        if (!$isOwner && !$isAdmin) {
            $userCharacters = array_filter($userCharacters, function ($character) {
                return $character->status === 'published';
            });
        }

        $data = [
            'characters' => $userCharacters,
            'defaultCharacters' => $defaultCharacters,
            'isLoggedIn' => $userEmail !== null,
            'isOwner' => $isOwner,
            'displayedUser' => $user,
            'user' => $this->getCurrentUser(),
        ];

        return $this->render('character', 'list', $data);
    }
}
