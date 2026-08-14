<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Core;

class Validador
{
    private array $errores = [];

    public function validarContacto(array $datos): bool
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');

        if ($nombre === '') {

            $this->errores['nombre'] = 'El nombre es obligatorio.';

        } elseif (strlen($nombre) < 2 || strlen($nombre) > 100) {

            $this->errores['nombre'] = 'El nombre debe tener entre 2 y 100 caracteres.';

        } elseif (!preg_match("/^[\\p{L}\\s'.-]+$/u", $nombre)) {

            $this->errores['nombre'] = 'El nombre contiene caracteres inválidos.';
        }

        $email = trim($datos['email'] ?? '');

        if ($email === '') {

            $this->errores['email'] = 'El correo es obligatorio.';

        } elseif (strlen($email) > 150) {

            $this->errores['email'] = 'El correo es demasiado largo.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->errores['email'] = 'El correo no es válido.';
        }

        $telefono = trim($datos['Telefono'] ?? '');

        if ($telefono === '') {

            $this->errores['Telefono'] = 'El teléfono es obligatorio.';

        } elseif (!preg_match('/^[0-9]{7,15}$/', $telefono)) {

            $this->errores['Telefono'] =
                'El teléfono debe tener entre 7 y 15 dígitos.';
        }

        return empty($this->errores);
    }

    public function errores(): array
    {
        return $this->errores;
    }
}