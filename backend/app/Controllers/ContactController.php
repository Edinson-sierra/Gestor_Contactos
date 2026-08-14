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
        $validador = new Validador();

        if (!$validador->validarContacto($datos)) {
            $this->response->json([
                'estado' => 'error',
                'errores' => $validador->errores()
            ], 422);
            return;
        }

        $datos['nombre'] = trim($datos['nombre']);
        $datos['email'] = trim($datos['email']);
        $datos['Telefono'] = trim($datos['Telefono']);

        $contactoExistente = $this->modelo->obtenerPorTelefono($datos['Telefono']);
        $reemplazar = filter_var($datos['reemplazar'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // La primera petición informa el duplicado; solo se actualiza si el usuario confirma.
        if ($contactoExistente && !$reemplazar) {
            $this->response->json([
                'estado' => 'conflicto',
                'codigo' => 'TELEFONO_DUPLICADO',
                'mensaje' => 'Ya existe un contacto con este teléfono.',
                'contacto' => $contactoExistente
            ], 409);
            return;
        }

        $idReemplazado = $contactoExistente
            ? (int) $contactoExistente['id']
            : null;

        // Al reemplazar, el correo del mismo registro no debe contarse como duplicado.
        if ($this->modelo->existeCorreo($datos['email'], $idReemplazado)) {
            $this->response->json([
                'estado' => 'error',
                'errores' => [
                    'email' => 'Este correo pertenece a otro contacto.'
                ],
                'mensaje' => 'El correo ya está registrado.'
            ], 409);
            return;
        }

        if ($contactoExistente && $reemplazar) {
            $this->modelo->actualizar($idReemplazado, $datos);

            $this->response->json([
                'estado' => 'ok',
                'mensaje' => 'Contacto reemplazado correctamente.',
                'id' => $idReemplazado,
                'reemplazado' => true
            ]);
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
