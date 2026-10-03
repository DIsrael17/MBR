# Roadmap

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md). Hitos en orden de dependencia; las fechas se fijan por hito.

| Hito | Contenido | Tareas | Entregable verificable |
|---|---|---|---|
| **M0 — Plan aprobado** | Respuesta a preguntas abiertas, aprobación de stack | DOC-001 | Docs actualizados con decisiones "Aceptadas" |
| **M1 — Base técnica** | Proyecto, calidad, CI, frontend base, auth completa, roles | SETUP-*, AUTH-* | Registro/login/roles funcionando; CI verde |
| **M2 — Catálogos y propiedades** | Catálogos, ubicaciones, modelo de propiedad, estados, fotos | CAT-*, PROP-* | Propiedades creadas desde admin con fotos |
| **M3 — Publicación y moderación** | Wizard, edición, moderación, reportes | PUB-*, MOD-* | Agente publica → moderador aprueba |
| **M4 — Descubrimiento** | Búsqueda, detalle, Home, SEO básico | SRCH-*, DET-*, HOME-001, SEO-* | Visitante busca, filtra y ve detalle indexable |
| **M5 — Interacción** | Favoritos, leads, notificaciones, analítica básica | FAV-001, LEAD-*, NOTIF-001, ANL-001 | Visitante contacta, agente gestiona lead |
| **M6 — Paneles** | Agentes, inmobiliarias, admin, API | AGT-*, ADM-*, API-* | Dashboards con datos reales; API documentada |
| **M7 — Lanzamiento MVP** | QA, staging, producción | QA-*, DEP-* | MVP en producción |
| **M8 — Post-MVP 1** | Búsqueda por mapa, historial, alertas de favoritos, estadísticas | MAP-*, FAV-002/003, ANL-002, AGT-007 | — |
| **M9 — Post-MVP 2** | Mensajería, búsquedas guardadas, login social | MSG-*, NOTIF-002/003 | — |
| **M10 — Futuro** | Monetización, Meilisearch, multimedia (video/360°/planos), WhatsApp API, app móvil, IA/recomendaciones | MON-* y nuevas | — |

## Reglas de trabajo (sección 40)
Una tarea por PR, en orden de dependencia; tests junto con la funcionalidad; no avanzar a tareas bloqueadas por preguntas `Q-xx` sin respuesta; documentar decisiones en `decisions.md`.
