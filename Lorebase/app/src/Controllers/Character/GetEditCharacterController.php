<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class GetEditCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        $characterRepository = new CharacterRepository();
        $character = $characterRepository->find($request->getSlug('id'));

        if (empty($character)) {
            return new Response('Personnage non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('character','edit', ['character' => $character]);
    }
}
