# Backlog

> Estado: **PROPUESTA — pendiente de aprobación**. Prioridades: **P0** crítico · **P1** alta (MVP) · **P2** media (post-MVP) · **P3** baja.
> Columna **MVP**: ✔ = entra en el MVP (sección 42). **Bloq.** = depende de una pregunta abierta de [decisions.md](decisions.md).
> Cada tarea, antes de iniciarse, se detalla en `docs/tasks/<ID>.md` con el formato obligatorio (sección 39). Ya detalladas: ver [tasks/](tasks/).
> Definition of Done común a todas: código + tests pasando + Pint/Larastan sin errores + docs actualizadas + PR revisado y enfocado en una sola tarea.
> Tamaño: **S** ≤ ½ día · **M** ≈ 1 día · **L** 2–3 días (estimación de esfuerzo humano).

## Fase 0 — Análisis
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| DOC-001 | Documentación inicial de análisis (este paquete) | P0 | ✔ | L | — | Docs en `/docs`; preguntas abiertas respondidas; plan aprobado por el cliente |

## Fase 1 — Configuración inicial
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| SETUP-001 | Crear proyecto Laravel con Sail (PHP, MySQL, Redis, Mailpit), `.env.example`, README | P0 | ✔ | M | DOC-001, Q-24 | `sail up` levanta todo; `/` responde 200; `.env.example` documentado; sin secretos en repo |
| SETUP-002 | Calidad: Pest, Pint, Larastan, arch tests, `shouldBeStrict` | P0 | ✔ | S | SETUP-001 | `pint --test`, `phpstan`, `pest` pasan en limpio |
| SETUP-003 | CI GitHub Actions (lint + análisis + tests con MySQL) | P0 | ✔ | S | SETUP-002 | PR ejecuta pipeline; falla si algún check falla |
| SETUP-004 | Frontend base: Tailwind, Livewire, Alpine, layout público responsive, componentes UI (botón, input, card, modal) | P0 | ✔ | M | SETUP-001 | Layout mobile-first con header/footer; build Vite ok; componentes reutilizables |
| SETUP-005 | Storage (public/S3), colas Redis + Horizon, mail Mailpit, timezone `America/Mexico_City`, locale `es_MX` | P0 | ✔ | S | SETUP-001 | Job de prueba se procesa; correo llega a Mailpit; disco S3 configurable por env |
| SETUP-006 | Instalar Filament: paneles `/admin` y `/panel` vacíos | P0 | ✔ | S | SETUP-001 | Paneles cargan; acceso protegido en AUTH-005 |

## Fase 2 — Usuarios y autenticación
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| AUTH-001 | Registro, login, logout (starter kit) + consentimiento de privacidad | P0 | ✔ | M | SETUP-004 | Flujos funcionan; checkbox obligatorio; tests feature |
| AUTH-002 | Recuperación y cambio de contraseña | P0 | ✔ | S | AUTH-001 | Email de reset llega; token expira; tests |
| AUTH-003 | Verificación de email (middleware `verified` en rutas sensibles) | P0 | ✔ | S | AUTH-001 | No verificado no puede publicar/favoritear; tests |
| AUTH-004 | Perfil: datos, teléfono, WhatsApp, avatar | P1 | ✔ | M | AUTH-001, SETUP-005 | Avatar validado (jpg/png/webp ≤2MB); usuario no edita a otro |
| AUTH-005 | Roles y permisos (spatie), enum `RoleName`, seeder, `Gate::before` super_admin, acceso a paneles | P0 | ✔ | M | AUTH-001, SETUP-006, **Q-01, Q-12** | Matriz de authorization.md sembrada; agente recibe 403 en `/admin`; tests |
| AUTH-006 | Bloqueo de usuarios (login denegado, sesiones invalidadas) | P1 | ✔ | S | AUTH-005 | Usuario bloqueado no entra; tests |
| AUTH-007 | Rate limiters nombrados (login, register, password-reset, api, leads, reports) | P0 | ✔ | S | AUTH-001 | 429 al exceder; tests |
| AUTH-008 | API auth Sanctum (`/api/v1/auth/*`) | P1 | ✔ | M | AUTH-005 | register/login/logout/me funcionan con token; tests API |
| AUTH-009 | Auditoría base (activitylog) para acciones admin | P1 | ✔ | S | AUTH-005 | Cambios hechos por admins quedan registrados |
| AUTH-010 | Suite base de tests de autorización | P0 | ✔ | S | AUTH-005 | Tests de authorization.md en verde (se amplía por módulo) |

## Fase 3 — Catálogos
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| CAT-001 | Tipos de operación: migration, model, seeder, CRUD admin | P0 | ✔ | S | AUTH-005 | 5 operaciones sembradas; admin agrega/desactiva sin código |
| CAT-002 | Tipos de propiedad con `applicable_fields` y slug plural | P0 | ✔ | S | AUTH-005 | 16 tipos sembrados; CRUD admin |
| CAT-003 | Características/amenidades + `property_type_feature` | P0 | ✔ | M | CAT-002 | Admin define qué amenidades aplican por tipo |
| CAT-004 | Estados y municipios (import INEGI, comando `locations:import`) | P0 | ✔ | M | SETUP-001, **Q-07** | 32 estados y ~2,470 municipios importados idempotentemente |
| CAT-005 | Colonias y códigos postales (SEPOMEX) | P1 | ✔ | M | CAT-004 | Búsqueda por CP devuelve colonias; import idempotente |
| CAT-006 | Cache de catálogos + endpoints API de catálogos/ubicaciones | P1 | ✔ | S | CAT-001..005 | Respuestas cacheadas e invalidadas al editar; tests API |
| CAT-007 | Tipo de cambio y settings (límites, máx. fotos) | P1 | ✔ | S | AUTH-005, **Q-09** | Admin edita tipo de cambio; `price_mxn` se recalcula vía job |

## Fase 4 — Propiedades
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| PROP-001 | Migration + modelo `Property` + factory + relaciones | P0 | ✔ | M | CAT-001..004, **Q-03, Q-09, Q-10** | Migración con índices de database.md; factory con states; tests de relaciones |
| PROP-002 | Servicios: `CodeGenerator`, `SlugGenerator`, `PriceCalculator` (MXN, precio/m², precio anterior) | P0 | ✔ | M | PROP-001 | Unit tests de cálculos y unicidad |
| PROP-003 | Ubicación privada + `ApproximateLocationService` | P0 | ✔ | S | PROP-001, **Q-16** | Coordenadas públicas a 300–500 m; reales nunca en serialización pública; tests |
| PROP-004 | Enum `PropertyStatus` + `TransitionPropertyStatus` + `property_status_history` + eventos | P0 | ✔ | M | PROP-001, **Q-06** | Solo transiciones de business-rules.md; historial registrado; unit tests de toda la matriz |
| PROP-005 | `PropertyPolicy` | P0 | ✔ | S | PROP-001, AUTH-005 | Reglas de authorization.md; tests de autorización |
| PROP-006 | Actions `CreateProperty` / `UpdateProperty` + Form Requests | P0 | ✔ | M | PROP-002..005 | Validaciones de BR-PROP; reutilizables por web/API/Filament; tests |
| PROP-007 | Fotografías: medialibrary, validación, conversiones WebP en cola, orden, principal, borrar EXIF | P0 | ✔ | L | PROP-006, SETUP-005 | Reglas BR-MEDIA; tests con `Storage::fake` |
| PROP-008 | Características dinámicas por tipo (campos y amenidades aplicables) | P1 | ✔ | M | PROP-006, CAT-003 | Campos no aplicables se ignoran/null; tests |
| PROP-009 | Historial de slugs y redirección 301 | P2 | – | S | PROP-002 | Slug antiguo redirige 301 |

## Fase 5 — Publicación y moderación
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| PUB-001 | Wizard (Livewire) pasos 1–2: operación y tipo; crea borrador | P1 | ✔ | M | PROP-006 | Al terminar paso 2 existe `draft`; se puede retomar |
| PUB-002 | Paso 3: título, descripción, precio, moneda, periodo, campos físicos dinámicos | P1 | ✔ | M | PUB-001, PROP-008 | Campos según tipo; validación en vivo |
| PUB-003 | Paso 4: ubicación (estado→municipio→colonia, CP, pin en mapa, privacidad) | P1 | ✔ | L | PUB-001, CAT-005, PROP-003 | Cascada funciona; pin guarda lat/lng; toggle privacidad |
| PUB-004 | Paso 5: subir, ordenar (drag & drop) y elegir foto principal | P1 | ✔ | M | PROP-007, PUB-001 | Upload múltiple con progreso; orden persistente |
| PUB-005 | Paso 6: amenidades | P1 | ✔ | S | PUB-001, PROP-008 | Solo amenidades del tipo |
| PUB-006 | Pasos 7–8: vista previa y enviar a revisión / publicar | P1 | ✔ | M | PUB-002..005, PROP-004, **Q-04** | Preview igual a página pública; valida BR-PROP-08 |
| PUB-007 | Edición de propiedad existente + re-moderación | P1 | ✔ | M | PUB-006, **Q-05** | Cambios sensibles → `pending_review`; precio no |
| PUB-008 | Acciones del dueño: pausar, reactivar, reservar, vendida, rentada, archivar | P1 | ✔ | S | PROP-004 | Botones según estado; tests |
| MOD-001 | Cola de moderación (Filament): aprobar, rechazar (motivo), solicitar cambios, bloquear | P0 | ✔ | M | PROP-004, AUTH-005 | Moderador procesa pendientes; motivo obligatorio; auditado |
| MOD-002 | Notificaciones: aprobada, rechazada, cambios solicitados (email + DB) | P1 | ✔ | S | MOD-001, NOTIF-001 | Dueño recibe notificación; tests con `Notification::fake` |
| MOD-003 | Reportes: formulario público + gestión en admin | P1 | ✔ | M | DET-001, MOD-001, **Q-18** | Reglas BR-RPT; prioridad por umbral |

## Fase 6 — Buscador, detalle y Home
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| SRCH-001 | `PropertySearch` + `PropertySearchCriteria` + filtros básicos (operación, tipo, precio, ubicación) | P0 | ✔ | M | PROP-001 | Unit/feature tests por filtro; solo publicadas/reservadas |
| SRCH-002 | Filtros avanzados (recámaras, baños, estac., superficie, amenidades, amueblada, mascotas, fecha) | P1 | ✔ | M | SRCH-001 | Tests por filtro y combinados |
| SRCH-003 | Ordenamientos (recientes, precio ↑↓, superficie ↑↓, relevancia) | P1 | ✔ | S | SRCH-001 | Tests de orden |
| SRCH-004 | Página de resultados (Livewire): filtros, chips, paginación, URL compartible | P0 | ✔ | L | SRCH-001, SETUP-004 | Filtros reflejados en query string; mobile-friendly |
| SRCH-005 | Optimización: índices, eager loading, conteo de queries | P1 | ✔ | S | SRCH-002 | Listado ≤ N queries; EXPLAIN usa índices |
| SRCH-006 | API `GET /api/v1/properties` con filtros | P1 | ✔ | S | SRCH-002, API-001 | Contrato JSON documentado; tests |
| DET-001 | Página `/propiedades/{slug}`: datos, características, agente/inmobiliaria, estado vendida/rentada | P0 | ✔ | M | PROP-007 | 200 publicada; 404 borrador ajeno; 410 archivada |
| DET-002 | Galería con lightbox, imágenes responsive y lazy | P1 | ✔ | S | DET-001 | srcset WebP; accesible por teclado |
| DET-003 | Mapa con ubicación aproximada (Leaflet tras `MapProvider`) | P1 | ✔ | S | DET-001, PROP-003, **Q-02** | Círculo aproximado; coordenadas reales no en HTML |
| DET-004 | Propiedades similares y relacionadas | P2 | ✔ | S | DET-001, SRCH-001 | Misma operación/tipo/municipio ± 20 % precio |
| DET-005 | Compartir, botón WhatsApp (`wa.me`), teléfono, con registro de clics | P1 | ✔ | S | DET-001, ANL-001 | Clics registrados como eventos |
| HOME-001 | Home: buscador principal, destacadas, recientes, tipos, ciudades populares | P1 | ✔ | M | SRCH-004, **Q-15** | Secciones cacheadas; Lighthouse móvil ≥ 85 |
| HOME-002 | Home: agentes destacados, inmobiliarias, contenido SEO editable | P2 | – | S | HOME-001, AGT-005 | — |

## Fase 7 — Mapa (post-MVP)
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| MAP-001 | Endpoint `/api/v1/properties/map` por bbox + filtros | P2 | – | S | SRCH-002 | ≤ 500 puntos; solo coordenadas públicas |
| MAP-002 | Vista de búsqueda por mapa (markers, clusters, popup, "buscar en esta área") | P2 | – | L | MAP-001 | Pan/zoom actualizan resultados |

## Fase 8 — Favoritos
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| FAV-001 | Agregar/eliminar/listar favoritos (web + API) | P1 | ✔ | M | DET-001, AUTH-003 | Único por usuario; visitante → login; tests |
| FAV-002 | Historial de propiedades vistas | P2 | – | S | DET-001 | Últimas 100; purga programada |
| FAV-003 | Alertas en favoritos (precio, disponibilidad) | P2 | – | M | FAV-001, NOTIF-001 | Notifica al cambiar precio |

## Fase 9 — Leads
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| LEAD-001 | Formulario de contacto + `CreateLead` + antispam (honeypot, rate limit, captcha) | P0 | ✔ | M | DET-001, AUTH-007, **Q-17, Q-22** | Reglas BR-LEAD; tests |
| LEAD-002 | Notificación de nuevo lead (email + DB) en cola | P1 | ✔ | S | LEAD-001, NOTIF-001 | Agente recibe correo |
| LEAD-003 | Gestión de leads en `/panel`: listado, estados, historial, notas | P1 | ✔ | M | LEAD-001, SETUP-006 | Agente solo ve sus leads; tests |
| LEAD-004 | Leads a nivel inmobiliaria | P1 | ✔ | S | LEAD-003, AGT-002 | agency_admin ve todos los de su agencia |

## Fase 10 — Mensajería (post-MVP)
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| MSG-001 | Tablas conversations/participants/messages + policies | P2 | – | M | LEAD-001, **Q-19** | Solo participantes acceden |
| MSG-002 | UI mensajes, no leídos, notificaciones | P2 | – | L | MSG-001 | Marcar leído; contador |
| MSG-003 | API mensajería | P2 | – | S | MSG-001 | Tests API |
| MSG-004 | Tiempo real (Reverb), adjuntos | P3 | – | L | MSG-002 | — |

## Fase 11 — Agentes e inmobiliarias
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| AGT-001 | Perfil de agente + solicitud de rol + verificación por admin | P1 | ✔ | M | AUTH-005 | Agente aprobado obtiene rol y perfil |
| AGT-002 | Inmobiliaria: perfil, `agency_user` (owner/admin) | P1 | ✔ | M | AUTH-005, **Q-03** | CRUD de perfil por su admin |
| AGT-003 | Invitar / aceptar / remover agentes | P1 | ✔ | M | AGT-001, AGT-002 | Reglas BR-AGT; tests |
| AGT-004 | Asignar/reasignar propiedades a agentes | P1 | ✔ | S | AGT-003, PROP-005 | Solo dentro de la misma agencia |
| AGT-005 | Páginas públicas de agente e inmobiliaria con sus propiedades | P1 | ✔ | M | AGT-001, AGT-002, **Q-02** | SEO básico; solo perfiles verificados |
| AGT-006 | Dashboard de agente (totales por estado, leads, visitas, favoritos) | P1 | ✔ | M | LEAD-003, ANL-001 | Widgets con datos propios |
| AGT-007 | Dashboard de inmobiliaria + estadísticas por agente | P2 | – | M | AGT-006 | — |

## Fase 12 — Administración
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| ADM-001 | Dashboard admin: métricas y gráficas | P1 | ✔ | M | MOD-001, LEAD-001 | Conteos de sección 18 cacheados |
| ADM-002 | Gestión de usuarios (roles, bloqueo, verificación) | P1 | ✔ | M | AUTH-006 | Auditado |
| ADM-003 | Gestión de propiedades (filtros, destacar, editar) | P1 | ✔ | S | MOD-001 | Destacar con fecha fin |
| ADM-004 | Gestión de agentes e inmobiliarias (verificar) | P1 | ✔ | S | AGT-002 | — |
| ADM-005 | Pantalla de configuración (settings) | P2 | – | S | CAT-007 | — |
| ADM-006 | Visor de auditoría | P2 | – | S | AUTH-009 | — |

## Fase 13 — SEO
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| SEO-001 | Componente meta: title, description, canonical, Open Graph, Twitter | P1 | ✔ | S | SETUP-004 | Todas las páginas públicas con metas únicas |
| SEO-002 | Schema.org JSON-LD (RealEstateListing/Offer, BreadcrumbList, Organization, RealEstateAgent) | P1 | ✔ | S | DET-001 | Válido en Rich Results Test |
| SEO-003 | Sitemap (diario) + robots.txt por ambiente | P1 | ✔ | S | DET-001 | Staging `Disallow: /` |
| SEO-004 | Landings `/{operacion}/{tipo}/{estado}/{municipio}/{colonia}` | P1 | ✔ | M | SRCH-004, **Q-20** | 404 si combinación inválida; noindex si 0 resultados |
| SEO-005 | Reglas de indexación: noindex vendidas, 410 archivadas | P1 | ✔ | S | DET-001, **Q-13** | Tests |

## Fase 14 — Notificaciones
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| NOTIF-001 | Infraestructura: canales mail + database, plantillas, centro de notificaciones (campana) | P1 | ✔ | M | AUTH-001, SETUP-005 | Lista, marca leído; en cola |
| NOTIF-002 | Notificación de cambio de precio a quienes tienen favorita | P2 | – | S | FAV-003 | — |
| NOTIF-003 | Búsquedas guardadas + alertas (instantánea/diaria/semanal) | P2 | – | L | SRCH-002, NOTIF-001 | Match correcto; sin duplicados |

## Analítica
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| ANL-001 | Registro de eventos (visitas deduplicadas, clics, compartidos) | P1 | ✔ | M | DET-001, **Q-26** | Bots excluidos; 1 visita/24 h/sesión |
| ANL-002 | Agregación diaria/mensual + gráficas de agente | P2 | – | M | ANL-001 | Job diario; gráficas en panel |

## API
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| API-001 | Base API v1: Resources, manejo de errores, Scramble | P1 | ✔ | S | AUTH-008 | `/docs/api` generado |
| API-002 | Endpoints de propiedades del usuario (CRUD, fotos, transiciones) | P1 | ✔ | M | PROP-007, PROP-004, API-001 | Reutiliza Actions; tests API |
| API-003 | Endpoints de favoritos, leads, reportes, notificaciones, eventos | P1 | ✔ | M | FAV-001, LEAD-003, NOTIF-001 | Tests API |

## Fase 15 — Calidad
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| QA-001 | Revisión de N+1 y rendimiento con 50k propiedades demo | P1 | ✔ | M | SRCH-005 | Objetivos de RNF-02 medidos |
| QA-002 | Tests de seguridad: XSS, privacidad de ubicación, rate limits, headers | P1 | ✔ | S | LEAD-001, DET-001 | Suite verde |
| QA-003 | Revisión de accesibilidad y responsive | P1 | ✔ | S | HOME-001 | Sin errores críticos axe/Lighthouse |

## Fase 16 — Deployment
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| DEP-001 | Staging: servidor, BD, Redis, S3, workers, scheduler, deploy desde `develop` | P1 | ✔ | M | SETUP-003, **Q-24** | Staging accesible y protegido |
| DEP-002 | Producción: SSL, dominio, colas, scheduler, cache, logs | P1 | ✔ | M | DEP-001 | Checklist deployment.md completo |
| DEP-003 | Backups + monitoreo de errores (Sentry/Flare) | P1 | ✔ | S | DEP-002 | Restauración probada |
| DEP-004 | CD a producción con aprobación | P2 | – | S | DEP-002 | — |

## Monetización (solo preparación, P3)
| ID | Tarea | P | MVP | Tam. | Depende de | Criterios de aceptación |
|---|---|---|:-:|:-:|---|---|
| MON-001 | Diseño de planes/suscripciones/boosts (doc) | P3 | – | S | Reglas comerciales del cliente | Documento aprobado |

## Resumen
- **Total de tareas:** 104 · **MVP:** 86 · **Post-MVP:** 18.
- **Ruta crítica del MVP:** SETUP-001 → AUTH-001 → AUTH-005 → CAT-001..004 → PROP-001 → PROP-004/006/007 → PUB-* / MOD-001 → SRCH-001/004 → DET-001 → LEAD-001 → paneles → SEO → QA → DEP.
