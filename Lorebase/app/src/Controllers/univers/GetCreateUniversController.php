<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateUniversController extends AbstractController
{
     public function process(Request $request): Response
    {
        return $this->render('univers','CreateUnivers');
    }
}
