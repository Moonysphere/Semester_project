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
        $characterRepository = new CharacterRepository();
        $characters = $characterRepository->findAll();

        return $this->render('list', ['characters' => $characters]);
    }
}
