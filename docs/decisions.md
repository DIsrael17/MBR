# Decisiones y preguntas abiertas

Dos secciones:
1. **Preguntas abiertas (`Q-xx`)** — ambigüedades o conflictos del documento maestro. Cada una tiene una *propuesta por defecto*. Las marcadas **[BLOQUEANTE]** afectan la arquitectura y deben confirmarse antes de iniciar las tareas indicadas.
2. **Registro de decisiones (`ADR-xx`)** — decisiones técnicas propuestas; pasan a "Aceptada" cuando el cliente aprueba el plan.

## 1. Preguntas abiertas

| ID | Tema | Ambigüedad / conflicto | Propuesta por defecto | Bloquea |
|---|---|---|---|---|
| Q-01 **[BLOQUEANTE]** | ¿Particulares publican? | 2.2 dice "crear publicaciones si tiene permisos" sin definir quién otorga el permiso. | Usuario registrado puede publicar como **particular (dueño directo)** con límite configurable (p. ej. 3 activas) y siempre pasa por moderación. Permiso `properties.create` incluido en rol `user`. | AUTH-005, PROP-* |
| Q-02 | Alcance MVP del mapa y páginas públicas de agente/inmobiliaria | Sección 42 no incluye mapa ni páginas de agente/inmobiliaria; Home (33) y detalle (10) sí los piden. | MVP incluye: **mapa en detalle de propiedad** (ubicación aproximada) y **perfil público básico de agente e inmobiliaria**. Búsqueda por mapa (bounding box) en fase post-MVP inmediata (Fase 7). | MAP-*, AGT-* |
| Q-03 **[BLOQUEANTE]** | Agente ↔ inmobiliaria | ¿Puede existir agente independiente? ¿Un agente puede pertenecer a varias inmobiliarias? ¿De quién es la propiedad si el agente se va? | Agente puede ser independiente o pertenecer a **máximo una** inmobiliaria. Las propiedades creadas dentro de una inmobiliaria **pertenecen a la inmobiliaria** (`agency_id`) y se asignan a un agente (`agent_id`); si el agente sale, la inmobiliaria las reasigna. | AGT-*, PROP-001 |
| Q-04 **[BLOQUEANTE]** | ¿Quién publica sin revisión? | Paso 8: "publicar directamente dependiendo de los permisos". | MVP: **todas** las publicaciones nuevas pasan por revisión. Permiso `properties.publish_without_review` existe y el admin puede otorgarlo a agentes/inmobiliarias verificados. | PUB-* |
| Q-05 | Re-moderación al editar | No definido. | Cambios en precio/estado comercial **no** requieren revisión. Cambios en título, descripción, fotos, tipo u operación de una propiedad publicada la regresan a `pending_review` (la versión pública anterior se oculta). Alternativa más compleja: mantener versión publicada mientras se revisa la nueva (post-MVP). | PUB-* |
| Q-06 | Estados faltantes | Sección 16 pide "solicitar modificaciones" y "bloquear", pero no existen en la lista de estados (15). | Agregar estados `changes_requested` (Requiere cambios) y `blocked` (Bloqueada por admin). Total: 11 estados (ver business-rules.md). | PROP-004 |
| Q-07 **[BLOQUEANTE]** | País y catálogo de ubicaciones | Pide país, estado, municipio, ciudad, colonia, CP. ¿Solo México? ¿Fuente del catálogo? | MVP **solo México**, catálogo de estados y municipios INEGI + colonias/CP de SEPOMEX (carga vía comando artisan). Tabla `countries` no se crea aún; `states.country_code` = 'MX' para extender. "Ciudad" se modela como **municipio/alcaldía** (en CDMX: alcaldía). Ver Q-08. | CAT-004..006 |
| Q-08 | Municipio vs ciudad | En México no siempre coinciden (ej. Zona metropolitana de Monterrey = varios municipios). | Jerarquía: estado → municipio → colonia. "Ciudad" = municipio en MVP. Zonas/metropolitanas como agrupaciones (post-MVP). | CAT-*, SEO-* |
| Q-09 **[BLOQUEANTE]** | Monedas | "Moneda" sin lista; filtro de precio con monedas mezcladas. | Monedas: **MXN y USD**. Se guarda `price` + `currency` + `price_mxn` normalizado (tipo de cambio configurable por admin, actualizado manual o por job). Filtros y ordenamiento usan `price_mxn`; se muestra el precio original. | PROP-001, SRCH-* |
| Q-10 | Periodo de precio en renta | Renta mensual vs temporal (por noche/semana). | Campo `price_period`: `total` (venta/preventa/traspaso), `monthly`, `nightly`, `weekly`. Calendario de disponibilidad para vacacional: **post-MVP**. | PROP-001 |
| Q-11 | Campos dinámicos por tipo | "Campos dinámicos según el tipo". | Modelo híbrido: campos numéricos filtrables como **columnas** (`bedrooms`, `bathrooms`, `half_bathrooms`, `parking_spaces`, superficies, `floors`, `year_built`…); amenidades/características como catálogo `features` + pivote; tabla `property_type_feature` define qué características y qué campos aplican a cada tipo (`property_types.applicable_fields` JSON). | PROP-*, CAT-003 |
| Q-12 | Niveles de administración | Solo "Administrador". | Roles `super_admin` (configuración, usuarios admin) y `moderator` (moderación, reportes). | AUTH-005 |
| Q-13 | Propiedades vendidas/rentadas | ¿Siguen visibles públicamente? | Siguen accesibles por URL con distintivo "Vendida/Rentada", `noindex`, sin formulario de contacto y fuera del buscador. Archivadas/bloqueadas → 410 Gone. | SEO-*, PROP-004 |
| Q-14 | Vigencia de publicaciones | No mencionado. | Sin expiración en MVP; columna `expires_at` nullable preparada para planes. | — |
| Q-15 | Destacadas sin pagos | Home pide "propiedades destacadas" pero no hay pagos en MVP. | Admin marca manualmente `is_featured` + `featured_until`. | HOME-* |
| Q-16 | Ubicación aproximada | Método no definido. | Al guardar se calcula `public_lat/public_lng` con desplazamiento aleatorio **fijo** (≈ 300–500 m) y se muestra un círculo; coordenadas reales nunca salen del backend si `hide_exact_location = true` (default: **true**). | PROP-003, MAP-* |
| Q-17 | Privacidad y legales | Formularios capturan datos personales. | Requiere **aviso de privacidad** y **términos** proporcionados por el cliente; checkbox de consentimiento en registro y contacto. | AUTH-001, LEAD-001 |
| Q-18 | Reportes de visitantes | Sección 17 dice "usuarios". | Visitantes también pueden reportar (con email obligatorio + rate limit + captcha); registrados sin captcha. | RPT-001 |
| Q-19 | Leads vs mensajería | Ambos capturan contacto; mensajería no está en MVP. | MVP: el formulario crea un **lead** y notifica al agente por email/DB. En Fase 10, si el usuario está autenticado, el lead abre además una conversación. | LEAD-*, MSG-* |
| Q-20 | Slugs de páginas geográficas | `/venta/casas/ciudad-de-mexico` choca con municipios homónimos (p. ej. "Benito Juárez" existe en varios estados). | `/venta/casas/{estado}` y `/venta/casas/{estado}/{municipio}` y `/venta/casas/{estado}/{municipio}/{colonia}`. | SEO-004 |
| Q-21 | Precio por m² | ¿Sobre qué superficie? | `price_per_m2 = price_mxn / built_area` si existe; si no (terrenos) `/ land_area`. Calculado y almacenado. | PROP-001 |
| Q-22 | Anti-spam en contacto | No mencionado. | Rate limit por IP/email + honeypot; Cloudflare Turnstile o reCAPTCHA (requiere cuenta). | LEAD-001 |
| Q-23 | Idioma | No mencionado. | UI solo en español (es_MX), textos en archivos `lang/` para permitir i18n futura. | — |
| Q-24 **[BLOQUEANTE para Fase 1]** | Repositorio, hosting y servicios | No hay repo ni hosting definidos. | Repo en GitHub (lo crea el cliente y da acceso a Devin). Hosting propuesto: VPS (Laravel Forge/Ploi) o Laravel Cloud; decisión en Fase 16 pero conviene conocer el destino. | SETUP-* |
| Q-25 | Historial de propiedades vistas | ¿Retención? | Guardar últimas 100 vistas por usuario; purga mensual vía scheduler. | ANL-* |
| Q-26 | Contador de visitas | Bots y recargas inflan métricas. | Contar 1 visita por propiedad por sesión/IP por 24 h, excluyendo user-agents de bots; eventos crudos + agregados diarios. | ANL-* |

## 2. Registro de decisiones técnicas (propuestas)

| ID | Decisión | Alternativas consideradas | Motivo | Estado |
|---|---|---|---|---|
| ADR-01 | **Laravel 12.x** (o la versión estable vigente al iniciar SETUP-001) sobre **PHP 8.3+** | Versiones anteriores | Versión estable mantenida; se fija en `composer.json`. | Propuesta |
| ADR-02 | **MySQL 8.0** (o PostgreSQL 16) | SQLite (solo tests) | Ampliamente soportado por hostings; soporte espacial suficiente para bounding box. Tests con MySQL en CI para paridad. Si el cliente prefiere PostgreSQL + PostGIS, el diseño no cambia. | Propuesta |
| ADR-03 | Frontend público: **Blade + Livewire 3 + Alpine.js + Tailwind CSS** (Vite) | Inertia + Vue/React con SSR | HTML server-side nativo (SEO) sin infraestructura SSR de Node; un solo lenguaje para el equipo. | Propuesta |
| ADR-04 | Panel admin y paneles de agente/inmobiliaria: **Filament** (v3/v4 estable) | Panel a la medida en Blade | Reduce semanas de CRUD, tablas, filtros, gráficas; soporta multi-panel y policies de Laravel. | Propuesta |
| ADR-05 | Autenticación web: **Laravel Fortify** (vía starter kit Livewire); API: **Laravel Sanctum** (tokens) | Passport, JWT | Oficiales, simples; Sanctum cubre apps móviles propias. Socialite para login social (post-MVP). | Propuesta |
| ADR-06 | Roles/permisos: **spatie/laravel-permission** | Implementación propia | Estándar de facto, probado, cache de permisos. | Propuesta |
| ADR-07 | Imágenes: **spatie/laravel-medialibrary** (+ conversiones WebP en cola) | Intervention manual + tabla propia | Orden, conversiones, responsive images, S3. Reemplaza la tabla `property_images` por `media` (ver database.md). | Propuesta |
| ADR-08 | Almacenamiento: disco `local/public` en dev, **S3-compatible** (AWS S3 / Cloudflare R2 / DO Spaces) en prod | Disco local en prod | Escalable, CDN-ready. | Propuesta |
| ADR-09 | Colas y cache: **Redis** + **Laravel Horizon** | database queue | Rendimiento y monitoreo. En local puede usarse `database`. | Propuesta |
| ADR-10 | Mapas: **Leaflet + OpenStreetMap** detrás de una interfaz `MapProvider` (frontend) y `GeocoderInterface` (backend) | Google Maps, Mapbox | Sin costo ni API key para MVP; intercambiable por Google/Mapbox. | Propuesta |
| ADR-11 | Búsqueda MVP: **Eloquent + query filters** con índices; post-MVP: **Laravel Scout + Meilisearch** | Elasticsearch | Suficiente para decenas de miles de registros; Scout permite migrar sin cambiar controladores. | Propuesta |
| ADR-12 | Máquina de estados de propiedad: **PHP Enum `PropertyStatus` + `PropertyStatusTransitioner` (Action)** con historial | spatie/laravel-model-states | Reglas explícitas y testeables, sin dependencia extra. | Propuesta |
| ADR-13 | Auditoría: **spatie/laravel-activitylog** | Tabla propia | Probado, polimórfico. | Propuesta |
| ADR-14 | SEO: **spatie/laravel-sitemap** + componente Blade propio para meta/OG/JSON-LD | artesaos/seotools | Control total, poco código. | Propuesta |
| ADR-15 | Testing: **Pest**; calidad: **Laravel Pint**, **Larastan** (nivel 6 inicial) | PHPUnit puro | Sintaxis concisa sobre PHPUnit; Pint/Larastan en CI. | Propuesta |
| ADR-16 | Documentación de API: **Scramble** (OpenAPI generado desde código) | Scribe, Swagger manual | Se mantiene sincronizada automáticamente. | Propuesta |
| ADR-17 | Correo: **Mailpit** local; **Amazon SES / Postmark / Resend** en prod | — | Proveedor final lo decide el cliente. | Propuesta |
| ADR-18 | CI: **GitHub Actions** (Pint, Larastan, Pest con MySQL) | — | Integrado con GitHub. | Propuesta |
| ADR-19 | Entorno local: **Laravel Sail** (Docker: PHP, MySQL, Redis, Mailpit) | Herd, Valet | Paridad entre desarrolladores y CI. | Propuesta |
| ADR-20 | Ramas: `main` (producción), `develop` (integración/staging), `feature/<ID>-slug`, `bugfix/*`, `hotfix/*`; 1 PR = 1 tarea del backlog; Conventional Commits | Trunk-based | Pedido explícito en el documento maestro. | Propuesta |
