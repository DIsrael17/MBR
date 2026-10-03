# Plataforma Inmobiliaria

Plataforma web (Laravel) para publicar, buscar y contactar sobre propiedades inmobiliarias en venta, renta, preventa, traspaso y renta temporal. Incluye paneles para agentes, inmobiliarias y administradores, y una API REST v1 para futuras apps móviles.

> **Estado actual: Fase 0 (análisis y planificación).** Aún no existe código de aplicación. Este README describe la instalación prevista y se actualizará en la tarea SETUP-001.

## Documentación
| Documento | Contenido |
|---|---|
| [docs/requirements.md](docs/requirements.md) | Requerimientos, alcance MVP |
| [docs/decisions.md](docs/decisions.md) | **Preguntas abiertas** y decisiones técnicas (ADR) |
| [docs/business-rules.md](docs/business-rules.md) | Reglas de negocio y máquina de estados |
| [docs/architecture.md](docs/architecture.md) | Stack, capas, estructura de carpetas |
| [docs/database.md](docs/database.md) | Esquema, índices, seeders |
| [docs/authentication.md](docs/authentication.md) / [docs/authorization.md](docs/authorization.md) | Auth, roles, permisos, policies |
| [docs/api.md](docs/api.md) | Endpoints `/api/v1` |
| [docs/testing.md](docs/testing.md) | Estrategia de pruebas |
| [docs/deployment.md](docs/deployment.md) | Ambientes y despliegue |
| [docs/roadmap.md](docs/roadmap.md) | Fases e hitos |
| [docs/backlog.md](docs/backlog.md) | Backlog completo con IDs, dependencias y criterios |
| [docs/tasks/](docs/tasks/) | Fichas detalladas de tareas (formato obligatorio) |

## Requisitos (previstos)
- Docker + Docker Compose (Laravel Sail) **o** PHP 8.3+, Composer 2, Node 20+, MySQL 8, Redis 7.

## Instalación local (prevista)
```bash
git clone <repo> plataforma-inmobiliaria && cd plataforma-inmobiliaria
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed          # catálogos + super admin
./vendor/bin/sail artisan locations:import        # estados, municipios, colonias
./vendor/bin/sail artisan db:seed --class=DemoSeeder   # opcional: datos demo
./vendor/bin/sail artisan storage:link
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```
App: http://localhost · Admin: http://localhost/admin · Panel agente: http://localhost/panel · Mailpit: http://localhost:8025

## Variables de entorno (principales)
| Variable | Descripción |
|---|---|
| `APP_NAME`, `APP_ENV`, `APP_URL`, `APP_DEBUG` | Aplicación |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Base de datos |
| `REDIS_HOST`, `QUEUE_CONNECTION`, `CACHE_STORE`, `SESSION_DRIVER` | Redis / colas / cache |
| `FILESYSTEM_DISK`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_ENDPOINT` | Almacenamiento S3-compatible |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_FROM_ADDRESS` | Correo |
| `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Super admin inicial (seeder) |
| `MAP_PROVIDER` (`leaflet`), `MAP_TILES_URL`, `GOOGLE_MAPS_KEY` (opcional) | Mapas |
| `CAPTCHA_PROVIDER`, `CAPTCHA_SITE_KEY`, `CAPTCHA_SECRET` | Anti-spam |
| `DEFAULT_CURRENCY` (`MXN`) | Moneda base |

Nunca subir `.env` ni credenciales al repositorio.

## Tests
```bash
./vendor/bin/sail test
./vendor/bin/pint --test && ./vendor/bin/phpstan analyse
```

## Flujo Git
`main` (producción) · `develop` (staging) · `feature/<ID>-descripcion` · `bugfix/*` · `hotfix/*`. Un PR por tarea del backlog, commits con Conventional Commits (`feat(PROP-001): ...`).

## Deploy
Ver [docs/deployment.md](docs/deployment.md).
