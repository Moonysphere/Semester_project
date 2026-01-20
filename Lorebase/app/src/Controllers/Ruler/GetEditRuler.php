<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;

class GetEditRuler extends AbstractController
{
    public function process(Request $request): Response
    {
        $rulerRepository = new RulerRepository();
        $ruler = $rulerRepository->find($request->getSlug('id'));

        if (empty($ruler)) {
            return new Response('Personnage non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('ruler','edit', ['ruler' => $ruler]);
    }
}
