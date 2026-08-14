<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Models;

use Edinson\GestorContactos\Config\Database;
use PDO;

class ContactModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = (new Database())->conectar();
    }

    public function obtenerTodos(?string $search = null): array
    {
        if ($search !== null && trim($search) !== '') {

            $sql = "
                SELECT id, nombre, email, Telefono, created_at
                FROM contacts
                WHERE
                    nombre LIKE :search
                    OR email LIKE :search
                    OR Telefono LIKE :search
                ORDER BY id DESC
            ";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':search' => '%' . trim($search) . '%'
            ]);

            return $consulta->fetchAll();
        }

        $sql = "
            SELECT id, nombre, email, Telefono, created_at
            FROM contacts
            ORDER BY id DESC
        ";

        return $this->conexion
            ->query($sql)
            ->fetchAll();
    }

    public function crear(array $contacto): int
    {
        $sql = "
            INSERT INTO contacts (nombre, email, Telefono)
            VALUES (:nombre, :email, :Telefono)
        ";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':nombre' => $contacto['nombre'],
            ':email' => $contacto['email'],
            ':Telefono' => $contacto['Telefono'],
        ]);

        return (int) $this->conexion->lastInsertId();
    }

    public function obtenerPorTelefono(string $telefono): array|false
    {
        $sql = "
            SELECT id, nombre, email, Telefono, created_at
            FROM contacts
            WHERE Telefono = :Telefono
            LIMIT 1
        ";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':Telefono' => $telefono]);

        return $consulta->fetch();
    }

    public function actualizar(int $id, array $contacto): bool
    {
        $sql = "
            UPDATE contacts
            SET nombre = :nombre, email = :email, Telefono = :Telefono
            WHERE id = :id
        ";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':id' => $id,
            ':nombre' => $contacto['nombre'],
            ':email' => $contacto['email'],
            ':Telefono' => $contacto['Telefono'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM contacts WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount() > 0;
    }

    public function existeCorreo(string $email, ?int $exceptoId = null): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM contacts
            WHERE email = :email
        ";

        $parametros = [':email' => $email];

        if ($exceptoId !== null) {
            $sql .= ' AND id != :exceptoId';
            $parametros[':exceptoId'] = $exceptoId;
        }

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($parametros);

        return (bool) $consulta->fetchColumn();
    }
}
