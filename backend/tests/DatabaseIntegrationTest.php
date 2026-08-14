<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Edinson\GestorContactos\Config\Database;

Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$conexion = (new Database())->conectar();
$numeroPrueba = (string) time();

$conexion->beginTransaction();

try {
    $insertar = $conexion->prepare(
        'INSERT INTO contacts (nombre, email, Telefono)
         VALUES (:nombre, :email, :Telefono)'
    );

    $insertar->execute([
        ':nombre' => 'Contacto de Prueba',
        ':email' => "prueba{$numeroPrueba}@ejemplo.com",
        ':Telefono' => $numeroPrueba
    ]);

    $id = (int) $conexion->lastInsertId();

    $consultar = $conexion->prepare(
        'SELECT nombre FROM contacts WHERE id = :id'
    );
    $consultar->execute([':id' => $id]);
    $contacto = $consultar->fetch();

    if (!$contacto || $contacto['nombre'] !== 'Contacto de Prueba') {
        throw new RuntimeException('No fue posible consultar el contacto creado.');
    }

    echo "OK: conexión, inserción y consulta en la base de datos.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "FALLÓ: {$error->getMessage()}\n");
    exit(1);
} finally {
    // Revierte la inserción para no dejar datos de prueba.
    $conexion->rollBack();
}
