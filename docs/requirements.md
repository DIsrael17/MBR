# Requerimientos

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md). Fuente: documento maestro del proyecto. Ambigüedades resueltas en decisions.md (prefijo `Q-`).

## 1. Objetivo
Plataforma web inmobiliaria (Laravel) para publicar, administrar, buscar, filtrar y contactar sobre propiedades en venta, renta, preventa, traspaso y renta temporal/vacacional. Debe ser escalable, segura, mantenible y extensible (API REST versionada para futuras apps móviles).

## 2. Alcance del MVP (sección 42 del documento maestro)
| Incluido en MVP | Fuera del MVP (fases posteriores) |
|---|---|
| Registro / login / recuperación / verificación de email | Login social (Google, Facebook, Apple) |
| Usuarios y roles (visitante, usuario, agente, inmobiliaria, admin) | Pagos, suscripciones, planes, publicidad |
| Propiedades (CRUD, estados, características, ubicación, precio) | Chat en tiempo real, adjuntos y notas de voz |
| Catálogos: tipos de propiedad, operación (venta/renta mínimo), amenidades, ubicaciones | WhatsApp API (solo enlace `wa.me` en MVP) |
| Fotografías (galería, orden, principal, thumbnails, WebP) | Video, tour virtual, 360°, planos, PDF |
| Buscador con filtros, ordenamiento y paginación | Búsquedas guardadas + alertas |
| Página pública SEO de propiedad | Recomendaciones / IA |
| Favoritos | Notificaciones de cambio de precio en favoritos |
| Contacto con agente (leads) + estados de lead | Mensajería usuario ↔ agente (Fase 10) |
| Panel básico de agente | Estadísticas avanzadas / gráficas detalladas |
| Panel básico de administrador + moderación + reportes | App móvil |
| SEO básico (meta, canonical, OG, sitemap, Schema.org) | Páginas geográficas avanzadas / contenido SEO editorial |
| Tests + documentación | CDN, búsqueda full-text externa (Meilisearch) |

> **Nota:** la búsqueda por mapa (sección 9) y la página pública de inmobiliaria/agente no aparecen en la lista MVP de la sección 42, aunque el Home (sección 33) pide "agentes destacados" e "inmobiliarias". Ver `Q-02`.

## 3. Roles
| Rol | Resumen | Notas |
|---|---|---|
| Visitante | Navega, busca, ve detalle, comparte, envía contacto, se registra | Sin sesión |
| Usuario registrado | Perfil, favoritos, historial de vistas, contactar; publicar **solo si tiene permiso** | Ver `Q-01` (¿particulares pueden publicar?) |
| Agente | Publica/gestiona propiedades, fotos, leads, estadísticas, perfil profesional | Puede ser independiente o pertenecer a una inmobiliaria (`Q-03`) |
| Inmobiliaria | Perfil de empresa, gestiona agentes, asigna propiedades, estadísticas, página pública | Se modela como entidad `agencies` administrada por uno o más usuarios con rol `agency_admin` |
| Administrador | Todo el panel: usuarios, catálogos, moderación, reportes, configuración | Propuesta: separar `admin` y `moderator` (`Q-12`) |

Matriz de permisos detallada: [authorization.md](authorization.md).

## 4. Requerimientos funcionales (resumen por módulo)
IDs `RF-xx` se referencian desde el backlog.

- **RF-01 Autenticación:** registro, login, logout, recuperación y cambio de contraseña, verificación de email, perfil con foto.
- **RF-02 Roles y permisos:** roles y permisos configurables; autorización por policies.
- **RF-03 Catálogos:** tipos de operación, tipos de propiedad, características/amenidades, ubicaciones (estados, municipios/ciudades, colonias). Administrables sin cambiar código.
- **RF-04 Propiedades:** datos generales, precio/moneda, precio anterior, precio por m² (calculado), características físicas, características dinámicas por tipo, ubicación con privacidad, estados con transiciones controladas, historial de estados.
- **RF-05 Multimedia:** múltiples fotos JPG/JPEG/PNG/WEBP, principal, orden, borrado, compresión, thumbnails, validación de formato/tamaño.
- **RF-06 Wizard de publicación:** 8 pasos (operación → tipo → información → ubicación → fotos → amenidades → vista previa → publicar/enviar a revisión) con guardado como borrador en cada paso.
- **RF-07 Moderación:** aprobar, rechazar con motivo, solicitar cambios, bloquear.
- **RF-08 Búsqueda:** filtros (operación, tipo, precio min/max, estado, ciudad, colonia, recámaras, baños, estacionamientos, superficie min/max, características, amueblada, mascotas, fecha de publicación), ordenamientos (recientes, precio ↑↓, superficie ↑↓, relevancia), paginación.
- **RF-09 Mapa:** marcadores, popup con precio y foto, zoom/pan, búsqueda en área visible (bounding box), ubicación aproximada. Proveedor intercambiable.
- **RF-10 Página de propiedad:** URL `/propiedades/{slug}`, galería, características, mapa, agente/inmobiliaria, WhatsApp, teléfono, formulario, similares/relacionadas, SEO completo.
- **RF-11 Favoritos:** agregar, eliminar, listar.
- **RF-12 Leads:** formulario de contacto (nombre, correo, teléfono, WhatsApp, mensaje), asignación al agente responsable, estados del pipeline.
- **RF-13 Mensajería** (post-MVP): conversaciones usuario ↔ agente, leídos, notificaciones.
- **RF-14 Agentes e inmobiliarias:** perfiles, relación agente/inmobiliaria, asignación de propiedades, páginas públicas.
- **RF-15 Reportes de propiedades:** motivos catalogados; gestión por admin.
- **RF-16 Panel admin:** dashboard con métricas y gráficas, gestión de entidades, auditoría.
- **RF-17 Panel agente:** totales por estado, leads, visitas, favoritos.
- **RF-18 SEO:** meta title/description, canonical, OG, Schema.org, sitemap, robots, páginas indexables por operación/tipo/ubicación.
- **RF-19 Notificaciones:** email + base de datos (nuevo lead, propiedad aprobada/rechazada; resto post-MVP).
- **RF-20 Búsquedas guardadas** (post-MVP).
- **RF-21 Analítica:** visitas, contactos, clics en teléfono/WhatsApp, favoritos, compartidos; agregados diarios/mensuales.
- **RF-22 API REST v1** documentada (`/api/v1/...`).
- **RF-23 Monetización:** solo preparar arquitectura (flag `is_featured`, `featured_until`); sin pagos.

## 5. Requerimientos no funcionales
- **RNF-01 Seguridad:** policies/gates, Form Requests, CSRF, escape de salida (XSS), ORM (SQLi), rate limiting (login, contacto, reportes, API), validación de archivos (MIME real + tamaño + dimensiones), sanitización de HTML de descripciones, logs, auditoría de acciones administrativas, secretos solo en variables de entorno.
- **RNF-02 Rendimiento:** paginación obligatoria, eager loading (`Model::preventLazyLoading()` en dev/test), índices, cache (Redis), colas para imágenes/correo/notificaciones, lazy loading de imágenes, objetivo TTFB < 500 ms en listado con 50k propiedades (a validar).
- **RNF-03 Mantenibilidad:** arquitectura por capas (Actions/Services), PSR-12 con Laravel Pint, análisis estático (Larastan), tests en CI.
- **RNF-04 UX:** responsive, mobile-first, accesible (WCAG 2.1 AA como objetivo), interfaz en español.
- **RNF-05 SEO:** HTML renderizado en servidor para páginas públicas.
- **RNF-06 Ambientes:** local, staging, producción; configuración 100% por `.env`.
- **RNF-07 Privacidad:** cumplimiento de la LFPDPPP (México) — aviso de privacidad y consentimiento en formularios (`Q-17`). Dirección exacta nunca se expone si el anunciante la marcó como privada (ni en HTML, ni en API, ni en JSON del mapa).
