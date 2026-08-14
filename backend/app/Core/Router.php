<?php

declare(strict_types=1);

namespace Edinson\GestorContactos\Core;

class Router
{
    private array $rutas = [];

    public function get(string $ruta, callable|array $accion): void
    {
        $this->registrar('GET', $ruta, $accion);
    }

    public function post(string $ruta, callable|array $accion): void
    {
        $this->registrar('POST', $ruta, $accion);
    }

    public function delete(string $ruta, callable|array $accion): void
    {
        $this->registrar('DELETE', $ruta, $accion);
    }

    private function registrar(string $metodo, string $ruta, callable|array $accion): void
    {
        $this->rutas[$metodo][$this->normalizarRuta($ruta)] = $accion;
    }

    public function despachar(string $uri, string $metodo): void
{
    $ruta = $this->normalizarRuta($uri);

    $rutasMetodo = $this->rutas[$metodo] ?? [];

    foreach ($rutasMetodo as $patron => $accion) {

        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $patron);
        $regex = "#^{$regex}$#";

        if (preg_match($regex, $ruta, $coincidencias)) {

            array_shift($coincidencias);

            if (is_array($accion)) {

                [$controlador, $metodoControlador] = $accion;

                $instancia = new $controlador();

                $instancia->$metodoControlador(...$coincidencias);

                return;
            }

            call_user_func_array($accion, $coincidencias);

            return;
        }
    }

    http_response_code(404);

    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Ruta no encontrada.'
    ]);
}

    private function normalizarRuta(string $ruta): string
    {
        $ruta = rtrim($ruta, '/');

        return $ruta === '' ? '/' : $ruta;
    }
}