# Estrategia de testing

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md).

## Herramientas
- **Pest** (sobre PHPUnit) — unit, feature, API, arquitectura (`arch()` tests: controllers no usan `DB::` directo, models no dependen de HTTP, etc.).
- **Laravel Pint** (estilo), **Larastan** nivel 6 (subir progresivamente).
- Base de datos de tests: MySQL en CI (paridad); SQLite en memoria permitido localmente para velocidad salvo tests marcados `@group mysql` (búsqueda/índices).
- `Model::shouldBeStrict()` activo en tests → detecta N+1.
- `Storage::fake()`, `Queue::fake()`, `Notification::fake()`, `Mail::fake()`, `Http::fake()`.

## Pirámide
| Tipo | Qué cubre | Ejemplos |
|---|---|---|
| Unit | Enums, servicios puros, cálculos | `PropertyStatus::canTransitionTo()`, `PriceCalculator` (precio/m², MXN), `ApproximateLocationService` (offset dentro de rango), `SlugGenerator` |
| Feature (web/Livewire) | Flujos completos | registro, login, verificación, wizard, editar, publicar, buscar con filtros, favoritos, contacto, moderación |
| Autorización | Policies y rutas | ver authorization.md, sección "Tests obligatorios" |
| Validación | Form Requests | campos requeridos, tipos de archivo, tamaños, rangos |
| API | `/api/v1` | contrato JSON (`assertJsonStructure`), códigos, paginación, no fuga de datos privados |
| Seguridad | Rate limiting, XSS, privacidad | descripción con `<script>` se escapa; coordenadas privadas no aparecen en HTML/JSON |
| Rendimiento | N+1, nº de queries | listado de 24 propiedades ejecuta ≤ N queries |

## Reglas
- Toda tarea del backlog incluye sus tests (Definition of Done).
- CI bloquea merge si Pint, Larastan o Pest fallan.
- Cobertura objetivo: ≥ 80 % en `app/Actions`, `app/Services`, `app/Enums`, `app/Policies`.

## Comandos
```bash
./vendor/bin/sail test            # o: php artisan test
./vendor/bin/sail test --parallel
./vendor/bin/pint --test
./vendor/bin/phpstan analyse
```
