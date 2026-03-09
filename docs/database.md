# Base de datos - Fase 1

## Inicialización

La base de datos se inicializa automáticamente al levantar el proyecto por primera vez:

```bash
docker compose up
```

PostgreSQL ejecuta `database/schema_init.sql` en el primer arranque (directorio vacío). No se usan migraciones ni seeders de Laravel.

## Esquema (schema_init.sql)

- `users`, `password_reset_tokens`, `sessions` – Laravel por defecto
- `cache`, `cache_locks` – Laravel cache
- `jobs`, `job_batches`, `failed_jobs` – Laravel colas
- `categories` – id, name, slug
- `products` – id, category_id, sku, slug, name, description, price, old_price, stock, weight, dimensions, materials, other_info, badges (JSON), active, timestamps
- `product_images` – product_id, path, order
- `tags` – id, name, slug
- `product_tag` – pivot (product_id, tag_id)

## Datos iniciales

Incluidos en `schema_init.sql`: categorías, tags, productos, imágenes de productos y relaciones.

## Imágenes de productos

- **Estructura en disco:** `public/assets/images/products/{product_id}/1.webp` … `5.webp`
- **En BD (product_images.path):** `assets/images/products/{product_id}/N.webp`
- **Referencia:** por `product_id` para estabilidad

## Recrear desde cero

```bash
docker compose down -v
docker compose up
```

## Exportar cambios futuros

```bash
docker compose exec postgres pg_dump -U ecommerce -d ecommerce --no-owner --no-privileges > database/schema_init.sql
```
