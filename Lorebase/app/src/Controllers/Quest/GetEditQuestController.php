<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetEditQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        $questRepository = new QuestRepository();
        $quest = $questRepository->find($request->getSlug('id'));

        if (empty($quest)) {
            return new Response('Quête non trouvée', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('Quest_views', 'edit', ['quest' => $quest]);
    }
}