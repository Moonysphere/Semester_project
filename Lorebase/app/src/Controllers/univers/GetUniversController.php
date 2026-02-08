<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetUniversController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $univers = $universRepository->findBySlug($slug, 'univers');

        $isOwnEntity = $this->isEntityOwner($univers);

        return $this->render('univers', 'detail', ['univers' => $univers, 'isOwnEntity' => $isOwnEntity]);
    }
}
