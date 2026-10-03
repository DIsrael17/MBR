# Reglas de negocio

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md). Las reglas marcadas con `(Q-xx)` se derivan de la decisión correspondiente en decisions.md.

## BR-PROP — Propiedades
- **BR-PROP-01** Toda propiedad tiene exactamente un tipo de propiedad y un tipo de operación activos del catálogo.
- **BR-PROP-02** Toda propiedad tiene un **propietario de la publicación**: un usuario (`user_id`, quien la creó) y opcionalmente una inmobiliaria (`agency_id`) y un agente responsable (`agent_id`). Si `agency_id` está definido, la propiedad pertenece a la inmobiliaria. (Q-03)
- **BR-PROP-03** `code` (código interno) es único, se genera automáticamente con el formato `INM-{YYYY}-{000001}` y no es editable.
- **BR-PROP-04** `slug` se genera del título + operación + tipo + municipio; es único; si cambia el título de una propiedad publicada se conserva el slug anterior y se crea redirección 301 (tabla `property_slugs`).
- **BR-PROP-05** Precio > 0 obligatorio para publicar. `previous_price` se llena automáticamente cuando el precio cambia (no editable por el usuario).
- **BR-PROP-06** `price_mxn` se recalcula cuando cambia precio, moneda o tipo de cambio. (Q-09)
- **BR-PROP-07** `price_per_m2` = `price_mxn / built_area`, o `/ land_area` si no hay construida; null si ninguna. (Q-21)
- **BR-PROP-08** Para publicar se requieren: título (10–120 car.), descripción (≥ 50 car.), precio, operación, tipo, estado, municipio, coordenadas y **al menos 1 fotografía** (propuesta: mínimo 3).
- **BR-PROP-09** Los campos físicos que no aplican al tipo (ej. recámaras en un terreno) se ocultan en el formulario y se guardan como null. (Q-11)
- **BR-PROP-10** La dirección exacta y las coordenadas reales son privadas cuando `hide_exact_location = true` (default). Públicamente solo se exponen estado, municipio, colonia y coordenadas aproximadas. (Q-16)
- **BR-PROP-11** Un particular puede tener como máximo N propiedades activas (configurable, default 3). (Q-01)

## BR-STATUS — Estados y transiciones
Estados: `draft` (Borrador), `pending_review` (Pendiente de revisión), `changes_requested` (Requiere cambios), `published` (Publicada), `paused` (Pausada), `reserved` (Reservada), `sold` (Vendida), `rented` (Rentada), `rejected` (Rechazada), `blocked` (Bloqueada), `archived` (Archivada). (Q-06)

| Desde → Hacia | Quién | Condición |
|---|---|---|
| draft → pending_review | Dueño | Cumple BR-PROP-08 |
| draft → published | Dueño con `publish_without_review` | Cumple BR-PROP-08 (Q-04) |
| pending_review → published | Moderador | — |
| pending_review → changes_requested | Moderador | Motivo obligatorio |
| pending_review → rejected | Moderador | Motivo obligatorio (catálogo + texto) |
| changes_requested → pending_review | Dueño | Tras editar |
| rejected → draft | Dueño | Para corregir y reenviar |
| published → paused / paused → published | Dueño | Reactivar no requiere revisión si no hubo cambios de contenido |
| published → reserved / reserved → published | Dueño | — |
| published, reserved → sold | Dueño | Solo operaciones venta/preventa/traspaso |
| published, reserved → rented | Dueño | Solo operaciones renta/temporal |
| published → pending_review | Sistema | Edición de contenido sensible (Q-05) |
| cualquiera (excepto archived) → blocked | Moderador | Motivo obligatorio |
| blocked → draft | Moderador | — |
| cualquiera → archived | Dueño o admin | Soft-delete lógico; no visible |

- **BR-STATUS-01** Toda transición se ejecuta solo vía `PropertyStatusTransitioner`; transiciones no listadas lanzan `InvalidStatusTransition`.
- **BR-STATUS-02** Toda transición registra fila en `property_status_history` (de, a, usuario, motivo, fecha).
- **BR-STATUS-03** `published_at` se fija en la primera publicación; `updated_at` en cada cambio.
- **BR-STATUS-04** Solo `published` y `reserved` aparecen en el buscador; `sold`/`rented` accesibles por URL con `noindex` (Q-13).
- **BR-STATUS-05** Eventos: `PropertySubmitted`, `PropertyApproved`, `PropertyRejected`, `PropertyChangesRequested`, `PropertyPriceChanged` disparan notificaciones.

## BR-MEDIA — Fotografías
- **BR-MEDIA-01** Formatos: JPG, JPEG, PNG, WEBP (validación por MIME real). Máx. 10 MB por archivo (configurable), mín. 800×600 px, máx. 40 fotos por propiedad (configurable).
- **BR-MEDIA-02** La primera foto es la principal salvo que el usuario elija otra; siempre existe exactamente una principal si hay fotos.
- **BR-MEDIA-03** Se generan en cola: `thumb` (400×300), `card` (800×600), `large` (1600 px ancho) en WebP; se eliminan metadatos EXIF (privacidad GPS).
- **BR-MEDIA-04** Agregar/eliminar fotos de una propiedad publicada la manda a revisión (Q-05).

## BR-LEAD — Leads
- **BR-LEAD-01** Cualquiera (visitante o registrado) puede enviar contacto sobre una propiedad `published`/`reserved`.
- **BR-LEAD-02** Campos obligatorios: nombre, correo o teléfono (al menos uno), mensaje, consentimiento de privacidad (Q-17). WhatsApp opcional.
- **BR-LEAD-03** El lead se asigna al `agent_id` de la propiedad; si no hay, al dueño (`user_id`); las inmobiliarias ven todos los leads de sus propiedades.
- **BR-LEAD-04** Estados: `new` → `contacted` → `following_up` → `visit_scheduled` → `negotiating` → `closed` | `not_interested`. Transiciones libres hacia adelante y a `not_interested`; se registra historial.
- **BR-LEAD-05** Rate limit: 5 leads/hora por IP y 3 por propiedad/email/día. (Q-22)
- **BR-LEAD-06** Los datos del lead solo los ven el agente asignado, admins de su inmobiliaria y administradores de la plataforma.

## BR-FAV — Favoritos
- **BR-FAV-01** Solo usuarios registrados; un favorito por usuario-propiedad (único).
- **BR-FAV-02** Si la propiedad deja de estar publicada, el favorito se conserva y se muestra como "no disponible".

## BR-RPT — Reportes
- **BR-RPT-01** Motivos: información falsa, propiedad inexistente, precio incorrecto, fotografías incorrectas, fraude, contenido inapropiado, duplicada, otro (texto obligatorio).
- **BR-RPT-02** Estados de reporte: `open` → `in_review` → `resolved` | `dismissed`; resolución con nota y acción tomada.
- **BR-RPT-03** Un usuario/email solo puede reportar una vez la misma propiedad.
- **BR-RPT-04** N reportes abiertos (configurable, default 5) sobre una propiedad la marcan para revisión prioritaria (no la bloquean automáticamente).

## BR-AGT — Agentes e inmobiliarias
- **BR-AGT-01** Un agente pertenece a 0 o 1 inmobiliaria. (Q-03)
- **BR-AGT-02** El `agency_admin` invita agentes por email; el agente acepta la invitación.
- **BR-AGT-03** Al remover un agente, sus propiedades de la inmobiliaria quedan sin agente y deben reasignarse; sus propiedades personales (sin `agency_id`) siguen siendo suyas.
- **BR-AGT-04** Perfiles públicos solo para agentes/inmobiliarias con perfil completo y aprobado por admin (verificación manual en MVP).

## BR-USR — Usuarios
- **BR-USR-01** Email único y verificado para publicar, contactar con sesión o reportar sin captcha.
- **BR-USR-02** Usuario bloqueado no puede iniciar sesión; sus propiedades pasan a `blocked`.
- **BR-USR-03** Eliminación de cuenta: soft delete + anonimización de datos personales tras 30 días (Q-17).
