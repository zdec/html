# Base de datos

## Inicialización

La base de datos se inicializa automáticamente al levantar el proyecto por primera vez con Docker:

```bash
docker compose up
```

PostgreSQL ejecuta `schema_init.sql` durante el primer arranque (directorio vacío). El script crea el esquema y carga los datos iniciales (categorías, tags, productos, imágenes, etc.).

## Usuario inicial (schema)

El script crea un usuario administrador para acceder al área admin y al login:

| Campo    | Valor              |
|----------|--------------------|
| **Email**    | `admin@itsecursas.co` |
| **Contraseña** | `AdminIT$`          |
| **Rol**  | Administrador (`is_admin = true`) |

Usar estas credenciales en `/login` para gestionar productos, usuarios, clientes y órdenes.

## Archivos

- **schema_init.sql** – Script SQL de inicialización (estructura + datos). Se ejecuta en `/docker-entrypoint-initdb.d/` al crear el volumen de PostgreSQL por primera vez.
- **schema_mysql.sql** – Mismo esquema y datos iniciales que `schema_init.sql`, sintaxis MySQL 8+ (utf8mb4). Para despliegues o importación en phpMyAdmin / hosting.
- **cpanel / SQL de creación** – En hosting compartido la base y el usuario se crean en cPanel (no hace falta un `.sql` local). Si tenías una plantilla con credenciales, bórrala tras el despliegue para no duplicar secretos.

## MySQL / cPanel — producción (Namecheap)

Credenciales reales tras crear la base en **cPanel → Manage My Databases** y asignar **All Privileges**. El esquema ya se importó con **`schema_mysql.sql`** (phpMyAdmin sobre la base indicada abajo).

| Variable / uso | Valor |
|----------------|-------|
| **Base de datos** | `itsetgaq_ecom-itsecur` |
| **Usuario MySQL** | `itsetgaq_itsecur` |
| **Contraseña** | `3TS2c4r$.a` |
| **Host** | `127.0.0.1` o `localhost` (desde la propia cuenta de hosting) |

En **`app/.env`** (local antes de subir o en el servidor), usar comillas en la contraseña por el carácter `$`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=itsetgaq_ecom-itsecur
DB_USERNAME=itsetgaq_itsecur
DB_PASSWORD="3TS2c4r$.a"
```

Si el hosting pide explícitamente `localhost`, cambia solo `DB_HOST`.

**Nota:** Si este repo es público o lo compartes, conviene **cambiar la contraseña en cPanel** y actualizar aquí y en `.env`; en equipos privados esto sirve como memoria técnica.

### Error `#1044` / usuario `cpses_...` en phpMyAdmin

Ese usuario es el login **temporal** de phpMyAdmin en cPanel, no el usuario de tu aplicación. **No puede** crear bases de datos ni ejecutar `CREATE DATABASE` / `CREATE USER` desde SQL.

1. Crea la base y el usuario solo en **cPanel → Bases de datos MySQL** y enlázalos con todos los privilegios.
2. En phpMyAdmin, elige en el menú izquierdo la base que **ya creó cPanel** (suele llamarse `cuenta_nombredb`, no un nombre inventado sin prefijo).
3. Desde ahí, **Importar** `schema_mysql.sql`. No uses el bloque `CREATE DATABASE` del archivo `cpanel_mysql_create_db_and_user.sql` en hosting compartido (ese archivo está pensado para servidores donde sí tienes usuario MySQL administrador).

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
