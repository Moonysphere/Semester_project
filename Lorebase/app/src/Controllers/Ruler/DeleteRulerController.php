<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class DeleteRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $RulerRepository = new RulerRepository();

        $ruler = $RulerRepository->find($request->getSlug('id'));

        if (empty($ruler)) {
            return new Response(
                json_encode(['error' => 'not found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        if ($ruler->user_id !== $_SESSION['user']['email'] && $_SESSION['user']['role'] !== 'admin') {
            return new Response(json_encode(['error' => 'Unauthorized']), 403, ['Content-Type' => 'application/json']);
        }

        $RulerRepository->remove($ruler);

        return new Response(
            'Regles supprimé',
            204,
            ['Content-Type' => 'application/json', 'Location' => '/univers']
        );
    }
}
