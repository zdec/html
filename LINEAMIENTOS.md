# LINEAMIENTOS

Documento de estándares para desarrollo y documentación del proyecto.

## Cómo trabajar

- **Código:** Seguir convenciones PSR y el estilo recomendado por Laravel (Laravel Pint).
- **Ramas y commits:** Usar mensajes de commit descriptivos; ramas por funcionalidad o corrección.
- **Repositorio:**
  - Versionar: código fuente, migraciones, tests, configuración base.
  - No versionar: `.env`, `vendor`, `node_modules`, carpeta `/script`.

## Cómo documentar

- **README.md:** Documentación del proyecto (uso, requisitos, cómo levantar). Enfoque en quién va a ejecutar o desplegar.
- **LINEAMIENTOS.md:** Estándares de desarrollo y documentación (este archivo).
- **Formato:** Markdown. Lenguaje: español para documentación de usuario; se puede usar inglés en comentarios de código si el equipo lo prefiere.

## Cómo usar `/script`

La carpeta `/script` está pensada solo para desarrollo y **no se sube al repositorio** (está en `.gitignore`).

- **Uso:** Scripts locales de utilidad (seeds manuales, llamadas curl a la API, helpers puntuales).
- **No usar para:** CI/CD, deploy o scripts que deban estar versionados. Esos van en rutas como `scripts/` (con "s") o en la raíz si son parte del flujo oficial.
