<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\UniversRepository;

class GetEditQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $universRepository = new UniversRepository();
        $universes = $universRepository->getAllUniverses();

        $placesRepository = new PlaceRepository();
        $places = $placesRepository->getAllPlaces();
        $questRepository = new QuestRepository();
        $slug = $request->getSlug('slug');

        if ($slug === '') {
            return new Response('Slug manquant', 400, ['Content-Type' => 'application/json']);
        }

        $quest = $questRepository->findBySlug($slug, 'quest');

        return $this->render('Quest_views', 'edit', ['quest' => $quest, 'universes' => $universes, 'places' => $places]);
    }
}
