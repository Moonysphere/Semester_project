<?php


namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class DeleteCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        $characterRepository = new CharacterRepository();

        $character = $characterRepository->find($request->getSlug('id'));

        if (empty($character)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        $characterRepository->remove($character);

        return new Response('Personnage supprimé', 204, ['Content-Type' => 'application/json', 'Location' => '/character']);
    }
}
