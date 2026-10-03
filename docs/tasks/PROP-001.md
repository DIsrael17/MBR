# PROP-001 — Modelo de propiedades

**Objetivo:** Crear la estructura base para almacenar propiedades inmobiliarias.

**Descripción:** Migración `properties` según database.md §3.3, modelo `Property` con casts (enums `PropertyStatus`, `Currency`, `PricePeriod`), relaciones y factory con states.

**Dependencias:** AUTH-005, CAT-001, CAT-002, CAT-004; **Q-03, Q-09, Q-10**.

**Archivos o módulos afectados:** `database/migrations/*_create_properties_table.php`, `app/Models/Property.php`, `app/Enums/{PropertyStatus,Currency,PricePeriod}.php`, `database/factories/PropertyFactory.php`.

**Base de datos:** tabla `properties` con FKs (user, agency, agent, property_type, operation_type, state, municipality, neighborhood), índices listados en database.md, softDeletes.

**Backend:** relaciones `owner`, `agent`, `agency`, `propertyType`, `operationType`, `state`, `municipality`, `neighborhood`; scopes `publiclyVisible()`, `searchable()`; `$hidden`/serialización pública sin campos privados.

**Frontend:** N/A.

**API:** N/A (API-002).

**Validaciones:** se implementan en PROP-006 (título requerido, precio > 0, `operation_type_id` y `property_type_id` existentes y activos).

**Reglas de negocio:** BR-PROP-01, BR-PROP-02, BR-PROP-10.

**Permisos:** se implementan en PROP-005.

**Tests requeridos:** migración corre y hace rollback; factory crea propiedad válida; relaciones devuelven modelos correctos; `toArray()` público no incluye `street`, `latitude`, `longitude` cuando `hide_exact_location = true`; scope `searchable` solo devuelve `published`/`reserved`.

**Criterios de aceptación:** migración funciona; relaciones funcionan; tests pasan.

**Definition of Done:** código · tests · database.md actualizado si hubo cambios · Pint/Larastan limpio · PR.

**Prioridad:** P0
