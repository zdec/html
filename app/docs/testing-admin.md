# Pruebas automatizadas del Admin

Esta guia describe como ejecutar y validar la suite automatizada del modulo admin.

## Alcance cubierto

- Login y logout
- Acceso por middleware `auth` y `admin`
- CRUD de productos (incluye stock e imagenes)
- CRUD y flujo de ordenes (pedido -> remision -> venta)
- CRUD de usuarios y restricciones de eliminacion
- Gestion de clientes
- Actualizacion de perfil
- Policies y `OrderFlowService`

## Estructura de pruebas

- `tests/Feature/Auth/AuthFlowTest.php`
- `tests/Feature/Admin/Products/ProductCrudTest.php`
- `tests/Feature/Admin/Orders/OrderCrudTest.php`
- `tests/Feature/Admin/Users/UserCrudTest.php`
- `tests/Feature/Admin/Customers/CustomerManagementTest.php`
- `tests/Feature/Admin/Profile/ProfileTest.php`
- `tests/Unit/Policies/*`
- `tests/Unit/Services/OrderFlowServiceTest.php`

## Ejecucion local en Docker

Desde la raiz del proyecto:

```bash
docker compose exec app php artisan test
```

Ejecutar por suite:

```bash
docker compose exec app php artisan test tests/Feature
docker compose exec app php artisan test tests/Unit
```

Ejecutar por modulo:

```bash
docker compose exec app php artisan test tests/Feature/Admin/Products/ProductCrudTest.php
docker compose exec app php artisan test tests/Feature/Admin/Orders/OrderCrudTest.php
```

## Criterios de exito por modulo

- **Auth**: login valido redirige a admin, login invalido mantiene sesion como invitado, logout limpia autenticacion.
- **Ordenes**: solo el rol esperado puede ver/editar, estados invalidos retornan error, remision/venta actualizan estado y movimientos.
- **Productos**: exige imagen principal + galeria minima, no elimina con stock > 0, actualiza stock correctamente.
- **Usuarios**: solo admin crea/edita/elimina, no permite autoeliminacion ni eliminar usuario id 1.
- **Clientes**: solo admin puede listar/editar, update persiste datos esperados.
- **Perfil**: usuario autenticado actualiza datos propios y puede cambiar clave con `current_password`.
- **Policies/Service**: reglas de autorizacion y transiciones de inventario/venta consistentes.

## Notas

- La suite usa bootstrap de esquema de pruebas definido en `tests/Support/BootstrapsDatabase.php`.
- Los tests no dependen de datos manuales de desarrollo.
