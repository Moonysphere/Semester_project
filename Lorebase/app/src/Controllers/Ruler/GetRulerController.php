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

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $ruler = $RulerRepository->findBySlug($slug, 'ruler');

        return $this->render('ruler' , 'detail', ['ruler' => $ruler]);
    }
}
