<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
    return $this->render('Quest_views', 'Quest');    }
}