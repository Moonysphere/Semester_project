<?php

namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class PatchUniversController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $universRepository = new UniversRepository();
        $univers = $universRepository->find($request->getSlug('id'));

        if (empty($univers)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
        }

        if ($univers->user_id !== $_SESSION['user']['email'] && $_SESSION['user']['role'] !== 'admin') {
            return new Response(json_encode(['error' => 'Unauthorized']), 403, ['Content-Type' => 'application/json']);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $univers->name = (string)($data['name'] ?? $univers->name);
        $univers->slug = $universRepository->checkSlug("slug", "univers", $universRepository->slugify($data['name'])) ?? $univers->slug;
        $univers->description = $data['description'] ?? $univers->description;

        // Si seulement status envoyé, utiliser setStatut()
        if (array_key_exists('status', $data) && count($data) === 1) {
            if (in_array($data['status'], ['draft', 'published', 'archived'], true)) {
                $universRepository->setStatut($univers->getId(), $data['status']);

                return new Response(
                    json_encode(['success' => true, 'id' => $univers->getId(), 'status' => $data['status']]),
                    200,
                    ['Content-Type' => 'application/json']
                );
            }
        }

        // Sinon mise à jour complète
        if (isset($data['status']) && in_array($data['status'], ['draft', 'published', 'archived'], true)) {
            $univers->status = $data['status'];
        }

        $universRepository->update($univers);

        return new Response(
            json_encode(['success' => true, 'id' => $univers->getId(), 'slug' => $univers->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
