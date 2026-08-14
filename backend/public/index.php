<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Edinson\GestorContactos\Config\Application;
use Edinson\GestorContactos\Core\Router;

// Inicializar la aplicación

 //Carga el archivo .env una única vez al iniciar la aplicación.


Application::iniciar();

// Configuración de la respuesta


header('Content-Type: application/json; charset=utf-8');

// Crear el Router


$router = new Router();

//Registrar las rutas


require __DIR__ . '/../routes/api.php';

/*

Normalizar la URL

 Convierte una URL como:
/Gestor-Contactos/backend/public/contacts
en: /contacts
*/

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = $uri === '' ? '/' : $uri;

// Despachar la petición


$router->despachar($uri, $_SERVER['REQUEST_METHOD']);