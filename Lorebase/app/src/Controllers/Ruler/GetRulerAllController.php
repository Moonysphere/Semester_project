<?php


namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class GetRulerAllController extends AbstractController
{
    public function process(Request $request): Response
    {
        $rulerRepository = new RulerRepository();
        $rulers = $rulerRepository->findAll();

        return $this->render('ruler','list', ['rulers' => $rulers]);
    }
}
