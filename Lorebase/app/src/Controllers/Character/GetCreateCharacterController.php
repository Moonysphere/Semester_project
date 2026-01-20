<?php

namespace App\Controllers\Character;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('character','CreateCharacter');
    }
}
