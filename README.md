# Gestor de Contactos

Aplicación web para registrar, consultar, filtrar y eliminar contactos. Utiliza una API REST en PHP con arquitectura MVC y un frontend independiente en React.

## Repositorio

El código fuente del proyecto se encuentra publicado en GitHub:

<https://github.com/Edinson-sierra/Gestor_Contactos>

## Funcionalidades

- Listado y búsqueda de contactos por nombre, correo o teléfono.
- Creación y eliminación con validaciones en frontend y backend.
- Detección de correos y teléfonos duplicados.
- Opción para rechazar o reemplazar un contacto si el teléfono ya existe.
- Interfaz responsive con confirmaciones y mensajes de estado.

## Requisitos y versiones utilizadas

| Tecnología | Versión utilizada | Propósito |
|---|---:|---|
| PHP | 8.2.12 | API REST y acceso a datos mediante PDO MySQL |
| Composer | 2.10.2 | Dependencias y autoload PSR-4 del backend |
| MariaDB | 10.4.32 | Base de datos incluida en XAMPP; compatible con MySQL 8 |
| Node.js | 24.11.1 | Entorno de ejecución del frontend |
| npm | 11.6.2 | Instalación de dependencias y scripts del frontend |
| React | 19.2.8 | Interfaz de usuario |
| Vite | 8.2.0 | Servidor de desarrollo y compilación |
| Axios | 1.19.0 | Comunicación con la API |

También pueden utilizarse versiones compatibles de PHP 8, Node.js y MySQL o MariaDB.

## Estructura

```text
Gestor-Contactos/
├── backend/
│   ├── app/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Core/
│   │   └── Models/
│   ├── public/index.php
│   └── routes/api.php
├── database/schema.sql
├── frontend/src/
│   ├── components/
│   ├── hooks/
│   ├── pages/
│   └── services/
└── README.md
```

## Requisitos

- Apache y MySQL o MariaDB; se recomienda XAMPP.
- PHP 8 o superior con PDO MySQL habilitado.
- Composer, Node.js y npm.

## Configuración de la base de datos

1. Iniciar Apache y MySQL desde XAMPP.
2. Importar el script [`database/schema.sql`](database/schema.sql) desde phpMyAdmin: seleccionar **Importar**, elegir el archivo y ejecutar la importación.
3. Como alternativa, importarlo desde la consola, ubicándose en la raíz del proyecto:

```bash
mysql -u root -p < database/schema.sql
```

Si el usuario `root` no tiene contraseña, basta con pulsar Enter cuando MySQL la solicite. El script crea, si no existen, la base de datos `contact_manager` y la tabla `contacts`.

## Levantar el backend

Instalar las dependencias:

```bash
cd backend
composer install
```

Copiar `.env.example` como `.env` (`copy` en Windows o `cp` en Linux/macOS) y configurar:

```dotenv
APP_NAME="Gestor de Contactos"
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contact_manager
DB_USERNAME=root
DB_PASSWORD=
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:5174
```

Si el repositorio está en `C:\xampp\htdocs\Gestor-Contactos`, la API estará normalmente en:

```text
http://localhost/Gestor-Contactos/backend/public
```

Si Apache utiliza otro puerto, debe incluirse en la URL, por ejemplo `http://localhost:8080/...`.

Para comprobar que el backend está funcionando, iniciar Apache y abrir la URL de la API en el navegador. No es necesario ejecutar un servidor PHP adicional cuando el proyecto está dentro de `htdocs`.

## Levantar el frontend

Instalar dependencias y crear la configuración local:

```bash
cd frontend
npm install
copy .env.example .env
```

En Linux o macOS se debe utilizar `cp .env.example .env`. Después, configurar la dirección real de la API:

```dotenv
VITE_API_URL=http://localhost:8080/Gestor-Contactos/backend/public
```

Si Apache usa el puerto 80, se debe retirar `:8080`. Para iniciar el frontend:

```bash
npm run dev
```

Vite mostrará la dirección de acceso, normalmente `http://localhost:5173`.

## Endpoints

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/` | Comprueba el estado de la API |
| `GET` | `/contactos` | Lista todos los contactos |
| `GET` | `/contactos?search=texto` | Filtra contactos |
| `POST` | `/contactos` | Crea un contacto |
| `DELETE` | `/contactos/{id}` | Elimina un contacto |

Las rutas bajo `/contacts` se conservan como alias de compatibilidad.

### Ejemplo de creación

```json
{
  "nombre": "Ana Martínez",
  "email": "ana@ejemplo.com",
  "Telefono": "3001234567"
}
```

Si el teléfono ya existe, la API devuelve HTTP `409` con el código `TELEFONO_DUPLICADO`. El frontend permite rechazar la operación o reenviarla con `reemplazar: true` para sustituir el contacto existente.

## Verificación del frontend

```bash
npm run lint
npm run build
```

## Pruebas del backend

Las pruebas usan PHP nativo, sin dependencias adicionales:

```bash
cd backend
composer test
```

La prueba de integración inserta y consulta un contacto dentro de una transacción. Al finalizar ejecuta `rollback`, por lo que no deja datos de prueba. También pueden ejecutarse por separado:

```bash
composer test:unit
composer test:integration
```

## Autor

**Edinson Alejandro Sierra Gutiérrez**
