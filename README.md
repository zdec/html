# E-commerce B2C - IT Secur

Tienda virtual de artículos tecnológicos construida con Laravel y PostgreSQL.

## Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

No se requiere PHP, Composer ni PostgreSQL instalados en el host; todo el entorno corre en contenedores.

## Inicio rápido

### 1. Configurar variables de entorno

```bash
cp .env.example .env
# Editar .env si necesitas cambiar DB_DATABASE, DB_PASSWORD, etc.
```

### 2. Levantar el entorno

**Opción A – Script recomendado**

```bash
./docker-dev.sh
```

**Opción B – Docker Compose directamente**

```bash
docker compose up
```

### 3. Acceder a la aplicación

- **URL:** http://localhost:8080
- La aplicación Laravel estará disponible en el puerto 8080 (configurable con la variable `PORT` en el `.env`).

### 4. Primera ejecución

En la primera ejecución genera la clave de la aplicación y el enlace de almacenamiento (imágenes de productos en admin):

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
```

La base de datos se inicializa automáticamente con `database/schema_init.sql` al levantar PostgreSQL por primera vez. No se usan migraciones ni seeders.

Ver [docs/database.md](docs/database.md) y [database/README.md](database/README.md) para más detalles.

## Estructura del proyecto

```
.
├── app/           # Aplicación Laravel
├── database/      # Script inicial de BD (schema_init.sql)
├── docker/        # Configuración Nginx
├── docker-compose.yml
├── Dockerfile
└── docker-dev.sh
```

## Variables de entorno

| Variable     | Descripción           | Por defecto   |
|-------------|-----------------------|---------------|
| DB_DATABASE | Nombre de la base     | ecommerce     |
| DB_USERNAME | Usuario PostgreSQL    | ecommerce     |
| DB_PASSWORD | Contraseña PostgreSQL | secret        |
| APP_URL     | URL de la aplicación  | http://localhost:8000 |

El archivo `app/.env.example` contiene la configuración completa de Laravel. Para desarrollo local dentro de `app/`:

```bash
cp app/.env.example app/.env
```

## docker-dev.sh

El script `docker-dev.sh` ejecuta `docker compose up` y muestra la URL de acceso. Es útil para:

- Unificar el comando de arranque del equipo
- Evitar olvidar parámetros
- Poder añadir validaciones (por ejemplo, comprobar que existe `.env`)

Uso: `./docker-dev.sh` o `bash docker-dev.sh`

## Producción / despliegue

- **Imágenes de productos:** Se guardan en `storage/app/public/products/`. Es necesario ejecutar `php artisan storage:link` para que la ruta `public/storage` apunte a ese directorio (en Docker: `docker compose exec app php artisan storage:link`).
- En producción puede configurarse otro disco (S3, etc.) en `config/filesystems.php` y usar el mismo flujo de subida.

## Documentación adicional

- **LINEAMIENTOS.md** – Cómo trabajar, documentar y usar la carpeta `/script`
- **Planes por fase** – Ver `.cursor/plans/` para la ejecución por fases del proyecto
