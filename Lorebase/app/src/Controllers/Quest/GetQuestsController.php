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
        $questRepository = new QuestRepository();
        $quests = $questRepository->findAll();

        return $this->render('/Quest_views/list', ['quests' => $quests]);
    }
}