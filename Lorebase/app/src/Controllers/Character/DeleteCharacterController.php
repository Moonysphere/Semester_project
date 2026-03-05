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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $characterRepository = new CharacterRepository();

        $character = $characterRepository->find($request->getSlug('id'));

        if (empty($character)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        if ($character->user_id !== $_SESSION['user']['email'] && $_SESSION['user']['role'] !== 'admin') {
            return new Response(json_encode(['error' => 'Unauthorized']), 403, ['Content-Type' => 'application/json']);
        }

        $characterRepository->remove($character);
        $username = $_SESSION['user']['username'] ?? null;
        $redirectUrl = $username ? "/$username/character" : '/character';

        return new Response('Personnage supprimé', 302, ['Content-Type' => 'application/json', 'Location' => $redirectUrl]);
    }
}
