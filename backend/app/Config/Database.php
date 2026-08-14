<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Config;

use PDO;
use PDOException;

class Database
{
    private ?PDO $conexion = null;

    //Retorna una única instancia de la conexión PDO.
     
    public function conectar(): PDO
    {
        if ($this->conexion !== null) {
            return $this->conexion;
        }

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_DATABASE']
            );

            $this->conexion = new PDO(
                $dsn,
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD']
            );

            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $this->conexion;

        } catch (PDOException $e) {

            throw new PDOException(
                'Error al conectar con la base de datos: ' . $e->getMessage(),
                (int) $e->getCode()
            );
        }
    }
}