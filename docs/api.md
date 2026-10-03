# API REST v1

> Estado: **PROPUESTA**. Base: `/api/v1`. JSON, autenticación Sanctum (Bearer token). Documentación OpenAPI generada con Scramble en `/docs/api` (solo local/staging o protegida).

## Convenciones
- Respuestas con **API Resources**; colecciones paginadas: `{ data: [], links: {}, meta: {} }`.
- Errores: `422` validación `{ message, errors: { campo: [] } }`, `401`, `403`, `404`, `429`.
- Filtros por query string con los mismos nombres que la web (`operation`, `type`, `price_min`, `price_max`, `state`, `municipality`, `neighborhood`, `bedrooms_min`, `bathrooms_min`, `parking_min`, `area_min`, `area_max`, `features[]`, `furnished`, `pets`, `published_since`, `sort`).
- Nunca se exponen campos privados de ubicación (BR-PROP-10).
- Versionado por prefijo; cambios incompatibles → `/api/v2`.

## Endpoints iniciales
| Método | Ruta | Auth | MVP | Descripción |
|---|---|---|:-:|---|
| POST | /auth/register, /auth/login | – | ✔ | Registro / token |
| POST | /auth/logout | ✔ | ✔ | Revoca token |
| GET | /auth/me | ✔ | ✔ | Usuario actual |
| POST | /auth/forgot-password, /auth/reset-password | – | ✔ | Recuperación |
| PATCH | /users/me | ✔ | ✔ | Actualizar perfil |
| GET | /operation-types, /property-types, /features | – | ✔ | Catálogos (cacheados) |
| GET | /locations/states, /states/{state}/municipalities, /municipalities/{m}/neighborhoods, /locations/postal-codes/{cp} | – | ✔ | Ubicaciones |
| GET | /properties | – | ✔ | Búsqueda con filtros + paginación |
| GET | /properties/map | – | Fase 7 | Puntos para mapa por bbox |
| GET | /properties/{slug} | – | ✔ | Detalle público |
| GET | /me/properties | ✔ | ✔ | Mis propiedades |
| POST | /properties | ✔ | ✔ | Crear (borrador) |
| PATCH | /properties/{id} | ✔ | ✔ | Actualizar |
| POST | /properties/{id}/photos | ✔ | ✔ | Subir foto |
| PATCH | /properties/{id}/photos/order | ✔ | ✔ | Reordenar / principal |
| DELETE | /properties/{id}/photos/{media} | ✔ | ✔ | Eliminar foto |
| POST | /properties/{id}/transitions | ✔ | ✔ | `{ to: "pending_review" }` etc. |
| DELETE | /properties/{id} | ✔ | ✔ | Archivar |
| GET/POST/DELETE | /me/favorites, /me/favorites/{property} | ✔ | ✔ | Favoritos |
| POST | /properties/{slug}/leads | – | ✔ | Contacto (rate-limited) |
| GET | /me/leads, PATCH /me/leads/{id} | ✔ | ✔ | Leads del agente |
| POST | /properties/{slug}/reports | – | ✔ | Reportar |
| POST | /properties/{slug}/events | – | ✔ | Analítica (phone_click, whatsapp_click, share) |
| GET | /me/notifications, POST /me/notifications/{id}/read | ✔ | ✔ | Notificaciones DB |
| GET/POST | /conversations, /conversations/{id}/messages | ✔ | Fase 10 | Mensajería |
| GET/POST/DELETE | /me/saved-searches | ✔ | Post-MVP | Búsquedas guardadas |

Moderación y administración se operan desde el panel Filament (no se exponen por API en MVP).
