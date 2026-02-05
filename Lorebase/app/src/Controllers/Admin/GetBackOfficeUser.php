<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\QuestRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RulerRepository;
use App\Repositories\UniversRepository;
use App\Repositories\UserRepository;

class GetBackOfficeUser extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (!$sessionUser) {
            return new Response(json_encode(['error' => 'Not logged in']), 403, ['Content-Type' => 'application/json']);
        }

        $urlEmail = $request->getSlug('email');

        if ($urlEmail && $urlEmail !== $sessionUser['email']) {
            $userRepo = new UserRepository();
            $currentUser = $userRepo->findByEmail($sessionUser['email']);

            if (!$currentUser || $currentUser->role !== 'admin') {
                return new Response(json_encode([
                    'error' => 'Unauthorized - Admin only',
                    'debug' => [
                        'currentUserRole' => $currentUser?->role,
                        'isAdmin' => $currentUser?->role === 'admin',
                        'SESSION_ID' => session_id(),
                    ]
                ]), 403, ['Content-Type' => 'application/json']);
            }
            $userEmail = $urlEmail;
            $viewedUser = $userRepo->findByEmail($userEmail);
            $displayedUserName = $viewedUser?->username ?? $userEmail;
        } else {
            $userEmail = $sessionUser['email'];
            $displayedUserName = $sessionUser['username'] ?? $userEmail;
        }

        $characterRepository = new CharacterRepository();
        $placeRepository = new PlaceRepository();
        $questRepository = new QuestRepository();
        $roleRepository = new RoleRepository();
        $rulerRepository = new RulerRepository();
        $universRepository = new UniversRepository();

        $characters = $characterRepository->findBy(['user_id' => $userEmail]);
        $places = $placeRepository->findBy(['user_id' => $userEmail]);
        $quests = $questRepository->findBy(['user_id' => $userEmail]);
        $rulers = $rulerRepository->findBy(['user_id' => $userEmail]);
        $univers = $universRepository->findBy(['user_id' => $userEmail]);

        $roles = $roleRepository->findAll();

        $characters = $characters ?? [];
        $places = $places ?? [];
        $quests = $quests ?? [];
        $roles = $roles ?? [];
        $rulers = $rulers ?? [];
        $univers = $univers ?? [];

        $data = [
            'characters' => $characters,
            'places' => $places,
            'quests' => $quests,
            'roles' => $roles,
            'rulers' => $rulers,
            'univers' => $univers,
            'character_count' => count($characters),
            'place_count' => count($places),
            'quest_count' => count($quests),
            'role_count' => count($roles),
            'ruler_count' => count($rulers),
            'univers_count' => count($univers),
            'displayedUserName' => $displayedUserName,
        ];

        return $this->render('admin', 'backoffice_user', $data);
    }
}
