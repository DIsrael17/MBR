# SETUP-002 — Herramientas de calidad

**Objetivo:** Garantizar estilo, análisis estático y pruebas desde el primer commit funcional.

**Descripción:** Instalar Pest (+ plugin Laravel y arch), Laravel Pint (preset laravel), Larastan (nivel 6), configurar `Model::shouldBeStrict(! app()->isProduction())`, scripts de composer (`lint`, `analyse`, `test`).

**Dependencias:** SETUP-001.

**Archivos o módulos afectados:** `composer.json`, `pint.json`, `phpstan.neon`, `tests/Pest.php`, `tests/ArchTest.php`, `app/Providers/AppServiceProvider.php`.

**Base de datos:** N/A. Tests usan base `testing` (MySQL en Sail).

**Backend:** configuración de strict mode.

**Frontend:** N/A.

**API:** N/A.

**Validaciones:** N/A.

**Reglas de negocio:** N/A.

**Permisos:** N/A.

**Tests requeridos:** arch tests: no `dd/dump/ray` en `app/`; Controllers no usan `DB` facade; Enums en `App\Enums` son enums; Models extienden `Model`.

**Criterios de aceptación:**
- `composer lint`, `composer analyse`, `composer test` pasan.
- Acceder a una relación no cargada en tests lanza excepción (strict mode).

**Definition of Done:** herramientas instaladas · comandos documentados en README y testing.md · todo en verde.

**Prioridad:** P0
