<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\QuestRepository;
use App\Repositories\UniversRepository;
use App\Forms\QuestForm;

class CreateAdminQuest extends AbstractController
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

        if ($request->getMethod() === 'GET') {
            $universRepository = new UniversRepository();
            $universes = $universRepository->getAllUniverses();

            return $this->render('admin', 'create_quest', ['universes' => $universes]);
        }

        if ($request->getMethod() === 'POST') {
            $formData = [
                'title' => $_POST['title'] ?? null,
                'slug' => $_POST['slug'] ?? null,
                'description' => $_POST['description'] ?? null,
                'statut_quest' => $_POST['statut_quest'] ?? null,
                'levelrequirements' => $_POST['levelrequirements'] ?? 0,
                'univers_id' => $_POST['univers_id'] ?? null,
                'status' => $_POST['status'] ?? 'draft',
                'user_id' => null,
            ];

            $form = new QuestForm($formData);
            $quest = $form->mapToEntity();

            if (!$quest) {
                return $this->render('admin', 'create_quest', ['errors' => $form->getErrors()]);
            }

            $quest->user_id = null;

            $repo = new QuestRepository();
            $repo->save($quest);

            return new Response('', 302, ['Location' => '/admin/backoffice']);
        }

        return new Response('Method not allowed', 405);
    }
}
