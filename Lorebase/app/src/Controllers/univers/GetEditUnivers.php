<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetEditUnivers extends AbstractController
{
    public function process(Request $request): Response
    {
        $universRepository = new UniversRepository();
        $univers = $universRepository->find($request->getSlug('id'));

        if (empty($univers)) {
            return new Response('univers non trouvé', 404, ['Content-Type' => 'text/html']);
        }

        return $this->render('univers','edit', ['univers' => $univers]);
    }
}
