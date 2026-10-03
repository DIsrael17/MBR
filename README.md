# Plataforma Inmobiliaria

Plataforma web (Laravel) para publicar, buscar y contactar sobre propiedades inmobiliarias en venta, renta, preventa, traspaso y renta temporal. Incluye paneles para agentes, inmobiliarias y administradores, y una API REST v1 para futuras apps móviles.

> **Estado actual:** Fase 1 — proyecto base (Laravel 13, Sail). Los módulos funcionales se agregan tarea por tarea según el [backlog](docs/backlog.md).

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

## Requisitos
- Docker + Docker Compose (Laravel Sail). Contenedores: PHP 8.4 (`laravel.test`), MySQL 8.4, Redis, Mailpit.
- Alternativa sin Docker: PHP 8.3+, Composer 2, Node 20+, MySQL 8, Redis 7.

## Instalación local
```bash
git clone <repo> plataforma-inmobiliaria && cd plataforma-inmobiliaria
cp .env.example .env            # ajustar WWWUSER/WWWGROUP con `id -u` / `id -g`

# Instalar dependencias PHP sin tener PHP local
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install --ignore-platform-reqs

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```
| Servicio | URL |
|---|---|
| App | http://localhost |
| Mailpit | http://localhost:8025 |
| MySQL | `localhost:3306` (usuario `sail` / `password`) |

Comandos que se agregarán en tareas posteriores: `migrate --seed` con catálogos y super admin (AUTH-005, CAT-*), `locations:import` (CAT-004), `DemoSeeder` (datos demo), paneles `/admin` y `/panel` (SETUP-006).

> Tip: crea un alias `alias sail='./vendor/bin/sail'`.

## Variables de entorno (principales)
| Variable | Descripción |
|---|---|
| `APP_NAME`, `APP_ENV`, `APP_URL`, `APP_DEBUG`, `APP_TIMEZONE` (`America/Mexico_City`), `APP_LOCALE` (`es`) | Aplicación |
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
./vendor/bin/sail test            # usa la base `testing` creada por Sail
./vendor/bin/sail pint --test
```
Larastan y Pest se agregan en SETUP-002.

## Flujo Git
`main` (producción) · `develop` (staging) · `feature/<ID>-descripcion` · `bugfix/*` · `hotfix/*`. Un PR por tarea del backlog, commits con Conventional Commits (`feat(PROP-001): ...`).

## Deploy
Ver [docs/deployment.md](docs/deployment.md).
