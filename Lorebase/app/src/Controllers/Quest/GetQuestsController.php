<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;

class GetQuestsController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $questRepository = new QuestRepository();
        $quests = $questRepository->findByDefault();

        return $this->render('Quest_views', 'defaultList', ['quests' => $quests]);
    }
}
