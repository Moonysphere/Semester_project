<?php


namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\CharacterRepository;

class GetCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        $characterRepository = new CharacterRepository();

         $slug = $request->getSlug('slug');

    if ($slug === '') {
        return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
    }

        $character = $characterRepository->findBySlug($slug, 'character');

        return $this->render('character','detail', ['character' => $character]);
    }
}
