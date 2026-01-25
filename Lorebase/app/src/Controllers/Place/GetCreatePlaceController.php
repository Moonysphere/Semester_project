<?php

namespace App\Controllers\Place;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetCreatePlaceController extends AbstractController
{
    public function process(Request $request): Response
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();

        return $this->render('place', 'CreatePlace', ['universes' => $universes]);
    }
}
