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
        $slug = $request->getSlug('slug');

    if ($slug === '') {
        return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
    }

    $univers = $universRepository->findBySlug($slug, 'univers');

        return $this->render('univers','edit', ['univers' => $univers]);
    }
}
