<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Core;

class Response
{
    public function json(array $datos, int $codigo = 200): void
    {
        http_response_code($codigo);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $datos,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }
}