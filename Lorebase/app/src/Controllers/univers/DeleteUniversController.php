<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class DeleteUniversController extends AbstractController
{
    public function process(Request $request): Response
    {
        $universRepository = new UniversRepository();

        $univers = $universRepository->find($request->getSlug('id'));

        if (empty($univers)) {
            return new Response(
                json_encode(['error' => 'not found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        $universRepository->remove($univers);

        return new Response(
            'Univers supprimé',
            204,
            ['Content-Type' => 'application/json', 'Location' => '/univers']
        );
    }
}
