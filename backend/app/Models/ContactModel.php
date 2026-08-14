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

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM contacts WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount() > 0;
    }
    public function existeCorreo(string $email): bool
{
    $sql = "
        SELECT COUNT(*)
        FROM contacts
        WHERE email = :email
    ";

    $consulta = $this->conexion->prepare($sql);

    $consulta->execute([
        ':email' => $email
    ]);

    return (bool) $consulta->fetchColumn();
}
}