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

        if ($contenido === false || trim($contenido) === '') {
            return [];
        }

        // JSON_THROW_ON_ERROR permite distinguir un JSON inválido de un formulario vacío.
        $datos = json_decode($contenido, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($datos)) {
            throw new \JsonException('El cuerpo debe ser un objeto JSON.');
        }

        return $datos;
    }

    public function parametro(string $nombre, mixed $default = null): mixed
    {
        return $_GET[$nombre] ?? $default;
    }
}
