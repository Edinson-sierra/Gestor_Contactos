# Gestor de Contactos

API REST desarrollada como prueba técnica utilizando **PHP 8**, **arquitectura MVC**, **Composer**, **PDO** y **MySQL**.

## Tecnologías

* PHP 8
* Composer (PSR-4)
* MySQL (XAMPP)
* PDO
* Apache
* React + Vite (Frontend en desarrollo)

## Arquitectura

```text
Gestor-Contactos/
├── backend/
├── database/
│   └── schema.sql
├── frontend/
├── .gitignore
└── README.md
```

## Funcionalidades implementadas

* Arquitectura MVC.
* Router propio.
* Conexión mediante PDO.
* Variables de entorno (`.env`).
* Validaciones del lado del servidor.
* CRUD de contactos.
* Búsqueda por nombre, correo y teléfono.
* Respuestas JSON.

## Instalación

1. Clonar el repositorio.
2. Ejecutar `composer install` dentro de `backend`.
3. Copiar `.env.example` como `.env`.
4. Importar `database/schema.sql`.
5. Iniciar Apache y MySQL desde XAMPP.

## Endpoints

| Método | Endpoint                 |
| ------ | ------------------------ |
| GET    | `/`                      |
| GET    | `/contacts`              |
| GET    | `/contacts?search=texto` |
| POST   | `/contacts`              |
| DELETE | `/contacts/{id}`         |

## Autor

**Edinson Alejandro Sierra Gutiérrez**
