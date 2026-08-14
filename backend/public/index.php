<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Edinson\GestorContactos\Config\Application;
use Edinson\GestorContactos\Core\Response;
use Edinson\GestorContactos\Core\Router;

Application::iniciar();

$router = new Router();

require __DIR__ . '/../routes/api.php';

// Apache incluye la carpeta public en la URL; el router solo necesita la ruta de la API.
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = $uri === '' ? '/' : $uri;

try {
    $router->despachar($uri, $_SERVER['REQUEST_METHOD']);
} catch (JsonException) {
    (new Response())->json([
        'estado' => 'error',
        'mensaje' => 'El cuerpo de la solicitud no contiene un JSON válido.'
    ], 400);
} catch (Throwable $error) {
    // El detalle se registra en el servidor y no se expone en la respuesta pública.
    error_log($error->getMessage());

    (new Response())->json([
        'estado' => 'error',
        'mensaje' => 'Ocurrió un error interno. Intente nuevamente.'
    ], 500);
}
