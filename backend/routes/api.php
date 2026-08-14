<?php

declare(strict_types=1);


//Rutas de la API
use Edinson\GestorContactos\Controllers\HomeController;
use Edinson\GestorContactos\Controllers\ContactController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/contacts', [ContactController::class, 'index']);

$router->post('/contacts', [ContactController::class, 'store']);

$router->delete('/contacts/{id}', [ContactController::class, 'destroy']);