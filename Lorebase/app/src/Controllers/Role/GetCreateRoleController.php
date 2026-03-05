<?php

namespace App\Controllers\Role;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class GetCreateRoleController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('role', 'CreateRole');
    }
}
