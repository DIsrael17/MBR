# Deployment

> Estado: **BORRADOR** — se completa en Fase 16. Hosting por definir (Q-24).

## Ambientes
| Ambiente | Rama | URL (ejemplo) | Notas |
|---|---|---|---|
| Local | feature/* | http://localhost | Sail: PHP, MySQL, Redis, Mailpit |
| Staging | develop | staging.dominio.mx | Datos demo, `APP_DEBUG=false`, correo a Mailpit/sandbox, `noindex` |
| Producción | main | dominio.mx | Deploy por tag/merge a main |

## Requisitos de servidor
PHP 8.3+ (extensiones: bcmath, ctype, curl, exif, fileinfo, gd o imagick, intl, mbstring, openssl, pdo_mysql, redis, xml, zip), MySQL 8, Redis, Nginx, Supervisor (Horizon), cron (scheduler), Node solo en build, SSL (Let's Encrypt).

## Pasos de deploy (zero-downtime vía Forge/Envoyer/Ploi o Laravel Cloud)
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize          # config, route, view, event cache
php artisan storage:link
php artisan horizon:terminate # reinicia workers
```

## Procesos
- **Queue workers:** Horizon (colas `default`, `media`, `notifications`, `analytics`).
- **Scheduler:** `* * * * * php artisan schedule:run` → agregación de analítica (diaria), sitemap (diario), purga de eventos/historial, tipo de cambio, expiración de destacadas.
- **Backups:** spatie/laravel-backup diario (BD + storage) a bucket separado, retención 30 días.
- **Logs:** canal `daily` + servicio externo (Sentry / Flare) para errores.
- **CI/CD:** GitHub Actions — lint + tests en cada PR; deploy automático a staging al merge en `develop`; deploy a producción manual/aprobado desde `main`.

## Variables de entorno críticas
Ver `.env.example` y README. Secretos solo en el gestor del hosting / GitHub Secrets, nunca en el repo.
