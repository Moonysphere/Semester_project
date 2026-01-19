<?php


namespace App\Controllers\Univers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\UniversRepository;

class GetUniversAllController extends AbstractController
{
    public function process(Request $request): Response
    {
        $UniversRepository = new UniversRepository();
        $univers = $UniversRepository->findAll();

        return $this->render('univers','list', ['univers' => $univers]);
    }
}
