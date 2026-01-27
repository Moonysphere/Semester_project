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
use App\Repositories\RulerRepository;
use App\Repositories\UniversRepository;

class GetBackOffice extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }



        $userRepo = new UserRepository();
        $characterRepo = new CharacterRepository();
        $placeRepo = new PlaceRepository();
        $questRepo = new QuestRepository();
        $roleRepo = new RoleRepository();
        $rulerRepo = new RulerRepository();
        $universRepo = new UniversRepository();

        $data = [
            'users' => $userRepo->findAll() ?? [],
            'characters' => $characterRepo->findAll() ?? [],
            'places' => $placeRepo->findAll() ?? [],
            'quests' => $questRepo->findAll() ?? [],
            'roles' => $roleRepo->findAll() ?? [],
            'rulers' => $rulerRepo->findAll() ?? [],
            'univers' => $universRepo->findAll() ?? [],
        ];

        return $this->render('admin', 'backoffice', $data);
    }
}
