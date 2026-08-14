<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Controllers;

use Edinson\GestorContactos\Core\Controller;
use Edinson\GestorContactos\Models\ContactModel;
use Edinson\GestorContactos\Core\Validador;

class ContactController extends Controller
{
    private ContactModel $modelo;

    public function __construct()
    {
        parent::__construct();

        $this->modelo = new ContactModel();
    }

  public function index(): void
{
    $busqueda = trim($this->request->parametro('search', ''));

    $contactos = $this->modelo->obtenerTodos($busqueda);

    $this->response->json([
        'estado' => 'ok',
        'busqueda' => $busqueda,
        'total' => count($contactos),
        'datos' => $contactos
    ]);
}

    public function store(): void
{
    $datos = $this->request->cuerpo();

    $validador = new validador();

    if (!$validador->validarContacto($datos)) {

        $this->response->json([
            'estado' => 'error',
            'errores' => $validador->errores()
        ], 422);

        return;
    }

    if ($this->modelo->existeCorreo($datos['email'])) {

        $this->response->json([
            'estado' => 'error',
            'mensaje' => 'El correo ya está registrado.'
        ], 409);

        return;
    }

    $id = $this->modelo->crear($datos);

    $this->response->json([
        'estado' => 'ok',
        'mensaje' => 'Contacto creado correctamente.',
        'id' => $id
    ], 201);
}

   public function destroy(string $id): void
{
    if (!ctype_digit($id) || (int)$id <= 0) {

        $this->response->json([
            'estado' => 'error',
            'mensaje' => 'El ID debe ser un número positivo.'
        ], 422);

        return;
    }

    if (!$this->modelo->eliminar((int)$id)) {

        $this->response->json([
            'estado' => 'error',
            'mensaje' => 'El contacto no existe.'
        ], 404);

        return;
    }

    $this->response->json([
        'estado' => 'ok',
        'mensaje' => 'Contacto eliminado correctamente.'
    ]);
}
}