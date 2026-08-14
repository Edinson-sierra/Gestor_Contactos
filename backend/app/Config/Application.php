<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Config;

use Dotenv\Dotenv;

class Application
{
    private static bool $inicializada = false;

    public static function iniciar(): void
    {
        if (self::$inicializada) {
            return;
        }

        // Cargar variables de entorno
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();

        // Configuración general
        date_default_timezone_set('America/Bogota');

        // Configurar CORS
        self::configurarCors();

        self::$inicializada = true;
    }

    private static function configurarCors(): void
    {
        $origenesPermitidos = [
            'http://localhost:5173',
            'http://localhost:5174'
        ];

        $origen = $_SERVER['HTTP_ORIGIN'] ?? '';

        if (in_array($origen, $origenesPermitidos, true)) {
            header("Access-Control-Allow-Origin: {$origen}");
        }

        header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json; charset=UTF-8');

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}