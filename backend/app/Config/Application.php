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

        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();

        self::$inicializada = true;
    }
}
