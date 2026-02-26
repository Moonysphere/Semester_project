<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UserRepository;
use App\Repositories\CharacterRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\QuestRepository;
use App\Repositories\UniversRepository;

class DeleteUser extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Unauthorized', 403);
        }

        $email = $request->getSlug('email');

        if (!$email) {
            return new Response('Email not found', 400);
        }

        try {
            $characterRepo = new CharacterRepository();
            $placeRepo = new PlaceRepository();
            $questRepo = new QuestRepository();
            $universRepo = new UniversRepository();

            $userCharacters = $characterRepo->queryBuilder()
                ->select()
                ->from('character')
                ->where('user_id', '=')
                ->addParam('user_id', $email)
                ->executeQuery()
                ->getAllResults();

            $userPlaces = $placeRepo->queryBuilder()
                ->select()
                ->from('place')
                ->where('user_id', '=')
                ->addParam('user_id', $email)
                ->executeQuery()
                ->getAllResults();

            $userQuests = $questRepo->queryBuilder()
                ->select()
                ->from('quest')
                ->where('user_id', '=')
                ->addParam('user_id', $email)
                ->executeQuery()
                ->getAllResults();

            $userUnivers = $universRepo->queryBuilder()
                ->select()
                ->from('univers')
                ->where('user_id', '=')
                ->addParam('user_id', $email)
                ->executeQuery()
                ->getAllResults();

            foreach ($userCharacters as $character) {
                $characterRepo->remove($character);
            }

            foreach ($userPlaces as $place) {
                $placeRepo->remove($place);
            }

            foreach ($userQuests as $quest) {
                $questRepo->remove($quest);
            }

            foreach ($userUnivers as $univers) {
                $universRepo->remove($univers);
            }

            $userRepo = new UserRepository();
            $user = $userRepo->queryBuilder()
                ->select()
                ->from('users')
                ->where('email', '=')
                ->addParam('email', $email)
                ->executeQuery()
                ->getOneResult();

            if ($user) {
                $userRepo->remove($user);
            }

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        } catch (\Exception $e) {
            return new Response('Error deleting user: ' . $e->getMessage(), 500);
        }
    }
}
