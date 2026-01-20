<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class GetRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $RulerRepository = new RulerRepository();

        $ruler = $RulerRepository->find($request->getSlug('id'));

        if (empty($ruler)) {
            return new Response('Ruler non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('ruler' , 'detail', ['ruler' => $ruler]);
    }
}
