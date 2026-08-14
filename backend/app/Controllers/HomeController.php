<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Controllers;

use Edinson\GestorContactos\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->response->json([
            'estado' => 'ok',
            'mensaje' => 'API Contact Manager funcionando.'
        ]);
    }
}