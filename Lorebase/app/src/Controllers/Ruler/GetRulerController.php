<?php

namespace App\Controllers\Ruler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\RulerRepository;
use App\Repositories\UniversRepository;

class GetRulerController extends AbstractController
{
    public function process(Request $request): Response
    {
        $RulerRepository = new RulerRepository();
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $ruler = $RulerRepository->findBySlug($slug, 'ruler');
        $universName = null;

        if (isset($ruler->univers_id) && $ruler->univers_id) {
            $universName = $universRepository->getUniversName($ruler->univers_id);
        }
        return $this->render('ruler', 'detail', ['ruler' => $ruler, 'universName' => $universName]);
    }
}
