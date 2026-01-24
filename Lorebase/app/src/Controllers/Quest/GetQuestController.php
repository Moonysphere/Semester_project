<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetQuestController extends AbstractController
{
    public function process(Request $request): Response
{
    $questRepository = new QuestRepository();

    $slug = $request->getSlug('slug');

    if ($slug === '') {
        return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
    }

    $quest = $questRepository->findBySlug($slug, 'quest');

    if (empty($quest)) {
        return new Response('Personnage non trouvé', 404, ['Content-Type' => 'application/json']);
    }

    return $this->render('Quest_views', 'detail', ['quest' => $quest]);
}

}