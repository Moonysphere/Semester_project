<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreatePlaceController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('place', 'CreatePlace');
    }
}
