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
        $universRepository = new UniversRepository();
        $univers = $universRepository->find($request->getSlug('id'));

        if (empty($univers)) {
            return new Response(json_encode(['error' => 'not found']), 404, ['Content-Type' => 'application/json']);
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
        $univers->slug = $universRepository->slugify($data['name']) ?? $univers->slug;
        $univers->description = $data['description'] ?? $univers->description;


        $universRepository->update($univers);

        return new Response(
            json_encode(['success' => true, 'id' => $univers->getId(),'slug' => $univers->slug]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
