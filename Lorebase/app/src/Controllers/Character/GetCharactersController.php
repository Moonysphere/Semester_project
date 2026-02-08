<?php


namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class GetCharactersController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $characterRepository = new CharacterRepository();
        $characters = $characterRepository->findByDefault();

        return $this->render('character', 'defaultList', ['characters' => $characters]);
    }
}
