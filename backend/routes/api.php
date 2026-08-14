<?php

declare(strict_types=1);

use Edinson\GestorContactos\Controllers\HomeController;
use Edinson\GestorContactos\Controllers\ContactController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/contactos', [ContactController::class, 'index']);

$router->post('/contactos', [ContactController::class, 'store']);

$router->delete('/contactos/{id}', [ContactController::class, 'destroy']);

// Alias en inglés para mantener compatibilidad con clientes anteriores.
$router->get('/contacts', [ContactController::class, 'index']);
$router->post('/contacts', [ContactController::class, 'store']);
$router->delete('/contacts/{id}', [ContactController::class, 'destroy']);
