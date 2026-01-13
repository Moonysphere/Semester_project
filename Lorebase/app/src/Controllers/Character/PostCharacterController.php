<?php



namespace App\Controllers\Character;

use App\Forms\CharacterForm;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class PostCharacterController extends AbstractController
{
    public function process(Request $request): Response
    {
        $data = $request->getPayload();

        if ($data === null || $data === '') {
            $data = file_get_contents('php://input');
        }

        $form = new CharacterForm();

        if (is_string($data)) {
            $parsedData = $form->parseStringToArray($data);
            if ($parsedData === null || empty($parsedData)) {
                return new Response(
                    json_encode(['error' => 'Invalid request format']),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }
            $data = $parsedData;
        }

        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $form->setData($data);

        if (!$form->validateRequiredFields()) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $character = $form->save();

        if ($character === null) {
            return new Response(
                json_encode(['errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response('', 302, ['Location' => '/character']);
    }
}
