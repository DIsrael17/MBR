# PROP-004 — Estados y transiciones de propiedad

**Objetivo:** Garantizar que una propiedad solo cambie de estado según reglas explícitas y con trazabilidad.

**Descripción:** Enum `PropertyStatus` con `label()`, `color()`, `allowedTransitions()`; Action `TransitionPropertyStatus` que valida transición, permiso del actor (vía policy) y condiciones (BR-PROP-08 para enviar/publicar), persiste en transacción, registra `property_status_history` y despacha eventos.

**Dependencias:** PROP-001; **Q-06** (estados `changes_requested` y `blocked`).

**Archivos o módulos afectados:** `app/Enums/PropertyStatus.php`, `app/Actions/Properties/TransitionPropertyStatus.php`, `app/Exceptions/InvalidStatusTransition.php`, `app/Models/PropertyStatusHistory.php`, `app/Events/Property*.php`, migración `property_status_history`.

**Base de datos:** tabla `property_status_history` (database.md §3.3).

**Backend:** ver descripción; `published_at` en primera publicación; motivo obligatorio para rechazar, solicitar cambios y bloquear.

**Frontend:** N/A (los botones se agregan en PUB-008 y MOD-001).

**API:** N/A (API-002).

**Validaciones:** `reason` requerido (≥ 10 car.) para `rejected`, `changes_requested`, `blocked`.

**Reglas de negocio:** tabla de transiciones BR-STATUS; BR-STATUS-01..05.

**Permisos:** dueño para transiciones de dueño; `properties.moderate` para las de moderación.

**Tests requeridos:** unit test para **cada** par (desde, hacia) permitido y no permitido; historial creado; eventos despachados (`Event::fake`); `sold` no permitido en operación de renta; dueño no puede aprobar su propia propiedad.

**Criterios de aceptación:** imposible cambiar `status` fuera de la Action (asignación directa bloqueada por `$guarded`/arch test); matriz completa cubierta por tests.

**Definition of Done:** código · tests · business-rules.md sincronizado · PR.

**Prioridad:** P0
