<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('ruler','CreateRuler');
    }
}
