<?php

namespace App\Controllers\Quest;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\UniversRepository;

class GetQuestController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $questRepository = new QuestRepository();
        $placeRepository = new PlaceRepository();
        $universRepository = new UniversRepository();

        $slug = $request->getSlug('slug');

        if ($slug === '') {
            throw new \Exception('Slug manquant', 404);
        }

        $quest = $questRepository->findBySlug($slug, 'quest');

        if (empty($quest)) {
            throw new \Exception('Quête non trouvée', 404);
        }

        $placeName = null;

        if (isset($quest->place_id) && $quest->place_id) {
            $placeName = $placeRepository->getPlaceName($quest->place_id);
        }

        $universName = null;

        if (isset($quest->univers_id) && $quest->univers_id) {
            $universName = $universRepository->getUniversName($quest->univers_id);
        }

        $isOwnEntity = $this->isEntityOwner($quest);

        return $this->render('Quest_views', 'detail', ['quest' => $quest, 'placeName' => $placeName, 'universName' => $universName, 'isOwnEntity' => $isOwnEntity]);
    }
}
