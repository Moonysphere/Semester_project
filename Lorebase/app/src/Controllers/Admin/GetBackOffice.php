<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UserRepository;
use App\Repositories\CharacterRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\QuestRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UniversRepository;

class GetBackOffice extends AbstractController
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

        $userRepo = new UserRepository();
        $characterRepo = new CharacterRepository();
        $placeRepo = new PlaceRepository();
        $questRepo = new QuestRepository();
        $roleRepo = new RoleRepository();
        $universRepo = new UniversRepository();

        // Récupérer les entités par défaut (user_id IS NULL)
        $characters = $characterRepo->queryBuilder()
            ->select()
            ->from('character')
            ->where('user_id', 'IS NULL')
            ->executeQuery()
            ->getAllResults() ?? [];

        $places = $placeRepo->queryBuilder()
            ->select()
            ->from('place')
            ->where('user_id', 'IS NULL')
            ->executeQuery()
            ->getAllResults() ?? [];

        $quests = $questRepo->queryBuilder()
            ->select()
            ->from('quest')
            ->where('user_id', 'IS NULL')
            ->executeQuery()
            ->getAllResults() ?? [];

        $roles = $roleRepo->findAll() ?? [];
        $univers = $universRepo->queryBuilder()
            ->select()
            ->from('univers')
            ->where('user_id', 'IS NULL')
            ->executeQuery()
            ->getAllResults() ?? [];

        $data = [
            'users' => $userRepo->findAll() ?? [],
            'characters' => $characters,
            'places' => $places,
            'quests' => $quests,
            'roles' => $roles,
            'univers' => $univers,
        ];

        return $this->render('admin', 'backoffice', $data);
    }
}
