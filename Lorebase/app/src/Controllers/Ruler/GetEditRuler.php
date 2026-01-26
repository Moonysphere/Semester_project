<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;
use App\Repositories\UniversRepository;

class GetEditRuler extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();
        $rulerRepository = new RulerRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $ruler = $rulerRepository->findBySlug($slug, 'ruler');


        return $this->render('ruler', 'edit', ['ruler' => $ruler, 'universes' => $universes]);
    }
}
