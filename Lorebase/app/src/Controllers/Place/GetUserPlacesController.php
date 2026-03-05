<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class GetUserPlacesController extends AbstractController
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

        $placeRepository = new PlaceRepository();

        $userPlaces = $placeRepository->findBy(['user_id' => $userEmail]);
        $defaultPlaces = $placeRepository->findByDefault();

        if (!$isOwner && !$isAdmin) {
            $userPlaces = array_filter($userPlaces, function ($place) {
                return $place->status === 'published';
            });
        }

        $data = [
            'places' => $userPlaces,
            'defaultPlaces' => $defaultPlaces,
            'isLoggedIn' => $userEmail !== null,
            'isOwner' => $isOwner,
            'displayedUser' => $user,
            'user' => $this->getCurrentUser(),
        ];

        return $this->render('place', 'list', $data);
    }
}
