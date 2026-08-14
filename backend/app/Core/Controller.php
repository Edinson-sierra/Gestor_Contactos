<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Core;

class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }
}