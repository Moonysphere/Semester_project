<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\PlaceRepository;

class DeleteAdminPlace extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Unauthorized', 403);
        }

        $placeRepository = new PlaceRepository();
        $place = $placeRepository->find($request->getSlug('id'));

        if (empty($place)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        $placeRepository->remove($place);

        return new Response('', 302, ['Location' => '/admin/backoffice']);
    }
}
