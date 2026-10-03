# SETUP-001 — Crear proyecto Laravel con Sail

**Objetivo:** Disponer de un proyecto Laravel ejecutable localmente de forma idéntica para todo el equipo.

**Descripción:** Crear el proyecto con la versión estable vigente de Laravel (ADR-01), configurar Laravel Sail con PHP 8.3+, MySQL 8, Redis y Mailpit, preparar `.env.example` completo y actualizar el README con la instalación real.

**Dependencias:** DOC-001 aprobado; Q-24 (repositorio GitHub disponible).

**Archivos o módulos afectados:** raíz del proyecto, `docker-compose.yml`, `.env.example`, `README.md`, `config/app.php`.

**Base de datos:** solo migraciones por defecto de Laravel (users, cache, jobs, sessions, password_reset_tokens).

**Backend:** proyecto base; `APP_LOCALE=es`, `APP_TIMEZONE=America/Mexico_City` vía env.

**Frontend:** el que trae el esqueleto (se reemplaza en SETUP-004).

**API:** ninguno (se instala `routes/api.php` con `install:api` en AUTH-008).

**Validaciones:** N/A.

**Reglas de negocio:** N/A.

**Permisos:** N/A.

**Tests requeridos:** test de humo: `GET /` responde 200.

**Criterios de aceptación:**
- `./vendor/bin/sail up -d` levanta app, MySQL, Redis y Mailpit.
- `sail artisan migrate` corre sin errores.
- `.env.example` contiene todas las variables del README sin valores secretos.
- `.env` en `.gitignore`.
- Ramas `main` y `develop` creadas; trabajo en `feature/SETUP-001-proyecto-base`.

**Definition of Done:** código implementado · test de humo en verde · README actualizado · sin secretos en repo · PR revisado.

**Prioridad:** P0
