<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Edinson\GestorContactos\Core\Validador;

$casos = [
    [
        'descripcion' => 'acepta un contacto válido',
        'datos' => [
            'nombre' => 'Ana Martínez',
            'email' => 'ana@ejemplo.com',
            'Telefono' => '3001234567'
        ],
        'valido' => true,
        'campoError' => null
    ],
    [
        'descripcion' => 'rechaza un nombre vacío',
        'datos' => [
            'nombre' => '',
            'email' => 'ana@ejemplo.com',
            'Telefono' => '3001234567'
        ],
        'valido' => false,
        'campoError' => 'nombre'
    ],
    [
        'descripcion' => 'rechaza caracteres inválidos en el nombre',
        'datos' => [
            'nombre' => 'Ana123',
            'email' => 'ana@ejemplo.com',
            'Telefono' => '3001234567'
        ],
        'valido' => false,
        'campoError' => 'nombre'
    ],
    [
        'descripcion' => 'rechaza un correo inválido',
        'datos' => [
            'nombre' => 'Ana Martínez',
            'email' => 'correo-invalido',
            'Telefono' => '3001234567'
        ],
        'valido' => false,
        'campoError' => 'email'
    ],
    [
        'descripcion' => 'rechaza un teléfono con formato inválido',
        'datos' => [
            'nombre' => 'Ana Martínez',
            'email' => 'ana@ejemplo.com',
            'Telefono' => '300 123'
        ],
        'valido' => false,
        'campoError' => 'Telefono'
    ]
];

$fallos = 0;

foreach ($casos as $caso) {
    $validador = new Validador();
    $resultado = $validador->validarContacto($caso['datos']);
    $tieneErrorEsperado = $caso['campoError'] === null
        || array_key_exists($caso['campoError'], $validador->errores());

    if ($resultado !== $caso['valido'] || !$tieneErrorEsperado) {
        $fallos++;
        fwrite(STDERR, "FALLÓ: {$caso['descripcion']}\n");
        continue;
    }

    echo "OK: {$caso['descripcion']}\n";
}

if ($fallos > 0) {
    exit(1);
}

echo "\nTodas las validaciones pasaron.\n";
