<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetCreateRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();
        return $this->render('ruler', 'CreateRuler', ['universes' => $universes]);
    }
}
