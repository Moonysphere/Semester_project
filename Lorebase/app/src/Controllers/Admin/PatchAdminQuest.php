<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;
use App\Repositories\UniversRepository;
use App\Forms\QuestForm;

class PatchAdminQuest extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vérifier que l'utilisateur est admin
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Unauthorized', 403);
        }

        $id = $request->getSlug('id');

        if (!$id) {
            return new Response('Quest ID not found', 400);
        }

        $repo = new QuestRepository();

        if ($request->getMethod() === 'GET') {
            $quest = $repo->find($id);
            if (!$quest) {
                return new Response('Quest not found', 404);
            }

            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'edit_quest', ['quest' => $quest, 'universes' => $universes]);
        }

        if ($request->getMethod() === 'PATCH') {
            $quest = $repo->find($id);
            if (!$quest) {
                return new Response('Quest not found', 404);
            }

            $formData = [
                'title' => $_POST['title'] ?? $quest->title,
                'slug' => $_POST['slug'] ?? $quest->slug,
                'description' => $_POST['description'] ?? $quest->description,
                'statut_quest' => $_POST['statut_quest'] ?? $quest->statut_quest,
                'levelrequirements' => $_POST['levelrequirements'] ?? $quest->levelrequirements,
                'status' => $_POST['status'] ?? $quest->status,
                'user_id' => null,
            ];

            $form = new QuestForm($formData);
            $updatedQuest = $form->mapToEntity();

            if (!$updatedQuest) {
                return $this->render('admin', 'edit_quest', ['quest' => $quest, 'errors' => $form->getErrors()]);
            }

            $updatedQuest->id = $id;
            $updatedQuest->user_id = null;

            $repo->update($updatedQuest);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
