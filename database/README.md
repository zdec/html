# Base de datos

## Inicialización

La base de datos se inicializa automáticamente al levantar el proyecto por primera vez con Docker:

```bash
docker compose up
```

PostgreSQL ejecuta `schema_init.sql` durante el primer arranque (directorio vacío). El script crea el esquema y carga los datos iniciales (categorías, tags, productos, imágenes, etc.).

## Archivos

- **schema_init.sql** – Script SQL de inicialización (estructura + datos). Se ejecuta en `/docker-entrypoint-initdb.d/` al crear el volumen de PostgreSQL por primera vez.

## Recrear la base de datos

Si necesitas empezar desde cero:

1. Detener los contenedores: `docker compose down`
2. Eliminar el volumen: `docker compose down -v` o `docker volume rm html_postgres_data`
3. Volver a levantar: `docker compose up`

## Nota sobre migraciones

Las migraciones de Laravel (`app/database/migrations/`) y seeders están obsoletas. El esquema y datos se gestionan mediante `schema_init.sql`. Para exportar cambios futuros:

```bash
docker compose exec postgres pg_dump -U ecommerce -d ecommerce --no-owner --no-privileges > database/schema_init.sql
```
