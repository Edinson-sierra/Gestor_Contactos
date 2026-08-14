<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Core;

class Request
{
    public function metodo(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function ruta(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function cuerpo(): array
    {
        $contenido = file_get_contents('php://input');

        return json_decode($contenido, true) ?? [];
    }

    public function parametro(string $nombre, mixed $default = null): mixed
    {
        return $_GET[$nombre] ?? $default;
    }
}