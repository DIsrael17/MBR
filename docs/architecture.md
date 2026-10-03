# Arquitectura

> Estado: **PROPUESTA — pendiente de aprobación**. Decisiones justificadas en [decisions.md](decisions.md) (ADR-xx).

## 1. Stack tecnológico propuesto
| Capa | Tecnología | ADR |
|---|---|---|
| Lenguaje / framework | PHP 8.3+, Laravel 12.x (versión estable vigente al iniciar) | ADR-01 |
| Base de datos | MySQL 8.0 (alternativa: PostgreSQL 16) | ADR-02 |
| Frontend público | Blade + Livewire 3 + Alpine.js + Tailwind CSS (Vite) | ADR-03 |
| Paneles (admin / agente / inmobiliaria) | Filament | ADR-04 |
| Auth web / API | Fortify (starter kit) / Sanctum | ADR-05 |
| Roles y permisos | spatie/laravel-permission | ADR-06 |
| Imágenes | spatie/laravel-medialibrary (conversiones WebP en cola) | ADR-07 |
| Storage | local (dev), S3-compatible (prod) | ADR-08 |
| Cache / colas | Redis + Horizon | ADR-09 |
| Mapas | Leaflet + OpenStreetMap tras interfaz intercambiable | ADR-10 |
| Búsqueda | Eloquent filters (MVP) → Scout + Meilisearch | ADR-11 |
| Auditoría | spatie/laravel-activitylog | ADR-13 |
| SEO | spatie/laravel-sitemap + componentes Blade | ADR-14 |
| Calidad | Pest, Pint, Larastan, GitHub Actions | ADR-15, 18 |
| API docs | Scramble (OpenAPI) | ADR-16 |
| Local | Laravel Sail (Docker) + Mailpit | ADR-19 |

## 2. Vista general
```
                 ┌──────────────────────────────────────────────┐
 Navegador ─────►│ Web (Blade/Livewire)   Paneles (Filament)    │
 App móvil ─────►│ API REST /api/v1 (Sanctum, Resources)        │
                 ├──────────────────────────────────────────────┤
                 │ HTTP: Controllers delgados, Form Requests,   │
                 │       Policies, Middleware, Rate limiters    │
                 ├──────────────────────────────────────────────┤
                 │ Dominio: Actions, Services, Enums, DTOs,     │
                 │          Events/Listeners, Notifications     │
                 ├──────────────────────────────────────────────┤
                 │ Persistencia: Eloquent Models, Query Filters │
                 └───────┬───────────────┬──────────────┬───────┘
                         ▼               ▼              ▼
                     MySQL           Redis (cache,   S3 (media)
                                     colas, sesión)
                 Workers (Horizon): imágenes, correos, notificaciones,
                 agregación de analítica, alertas.  Scheduler: cron.
```

## 3. Principios
1. **Controllers delgados:** validan (Form Request), autorizan (Policy) y delegan a una Action/Service; devuelven vista, Resource o redirect.
2. **Una Action por caso de uso** con escritura (`CreateProperty`, `SubmitPropertyForReview`, `ApproveProperty`, `CreateLead`…). Reutilizadas por web, API y Filament — **sin duplicar lógica**.
3. **Services** para lógica transversal sin estado de caso de uso (`PriceCalculator`, `ApproximateLocationService`, `SlugGenerator`, `CurrencyConverter`, `SeoBuilder`).
4. **Enums** nativos PHP para estados y valores fijos (`PropertyStatus`, `LeadStatus`, `ReportReason`, `Currency`, `PricePeriod`). Catálogos editables por admin (tipos, operaciones, amenidades) son **tablas**, no enums.
5. **Repositories solo cuando aporten valor** (no se crean en MVP; el query de búsqueda vive en `PropertySearch` + filtros).
6. **DTOs** solo donde un mismo input llega de varias fuentes (ej. `PropertySearchCriteria` desde web, API y búsqueda guardada).
7. **Eventos de dominio** desacoplan efectos secundarios (notificaciones, analítica, auditoría).
8. **Proveedores externos detrás de interfaces** (`GeocoderInterface`, `MapProvider` JS, `CaptchaVerifier`, `ExchangeRateProvider`) registradas en Service Providers.
9. `Model::shouldBeStrict()` en local/test (previene N+1, atributos inexistentes).

## 4. Estructura de carpetas
```
app/
├── Actions/
│   ├── Properties/ (CreateProperty, UpdateProperty, SubmitPropertyForReview, TransitionPropertyStatus, ...)
│   ├── Moderation/ (ApproveProperty, RejectProperty, RequestPropertyChanges, BlockProperty)
│   ├── Leads/      (CreateLead, UpdateLeadStatus)
│   ├── Favorites/  (ToggleFavorite)
│   └── Agencies/   (InviteAgent, AcceptAgencyInvitation, RemoveAgent, AssignPropertyToAgent)
├── DTOs/           (PropertySearchCriteria, PropertyData)
├── Enums/          (PropertyStatus, LeadStatus, ReportReason, ReportStatus, Currency, PricePeriod, RoleName)
├── Events/  Listeners/  Notifications/  Jobs/
├── Exceptions/     (InvalidStatusTransition)
├── Filament/
│   ├── Admin/      (Resources, Pages, Widgets)  → /admin
│   └── Agent/      (Resources, Pages, Widgets)  → /panel (agente e inmobiliaria)
├── Http/
│   ├── Controllers/
│   │   ├── Web/    (HomeController, PropertyController, SearchController, SeoLandingController, SitemapController)
│   │   └── Api/V1/ (AuthController, PropertyController, FavoriteController, LeadController, ...)
│   ├── Requests/   (Web/ y Api/V1/)
│   ├── Resources/V1/
│   └── Middleware/
├── Livewire/       (SearchFilters, PropertyWizard/*, FavoriteButton, ContactForm, PropertyMap)
├── Models/
├── Policies/
├── Queries/        (PropertySearch + Filters/*)
├── Services/       (Pricing/, Location/, Seo/, Media/, Analytics/)
└── Support/        (Contracts/ para interfaces de proveedores)
database/ (migrations, factories, seeders, data/ para CSV de SEPOMEX/INEGI)
resources/views/ (layouts, components, pages, livewire)
routes/ (web.php, api.php, console.php)
tests/ (Unit/, Feature/, Feature/Api/, Feature/Authorization/, Architecture/)
docs/
```

## 5. Módulos y responsabilidades
| Módulo | Responsabilidad | Tablas principales |
|---|---|---|
| Identity | Registro, login, perfil, roles | users, roles, permissions |
| Catalog | Tipos, operaciones, amenidades, ubicaciones | property_types, operation_types, features, states, municipalities, neighborhoods |
| Listings | Propiedades, estados, multimedia, slugs | properties, property_feature, property_status_history, property_slugs, media |
| Moderation | Revisión y reportes | property_status_history, reports |
| Search | Filtros, ordenamiento, mapa, landings SEO | (lectura sobre properties) |
| Engagement | Favoritos, historial, leads, mensajes, búsquedas guardadas | favorites, property_views, leads, lead_status_history, conversations, messages, saved_searches |
| Agencies | Agentes, inmobiliarias, invitaciones | agencies, agent_profiles, agency_invitations |
| Analytics | Eventos y agregados | property_events, property_daily_stats |
| Platform | Configuración, auditoría, notificaciones | settings, activity_log, notifications, exchange_rates |

## 6. Búsqueda
- `PropertySearch` recibe un `PropertySearchCriteria` y aplica filtros encadenables (`OperationFilter`, `TypeFilter`, `PriceRangeFilter`, `LocationFilter`, `BedroomsFilter`, `AreaRangeFilter`, `FeaturesFilter`, `BoundingBoxFilter`…).
- Siempre: `status IN (published, reserved)`, paginación (24/página), eager loading de `mainImage`, `propertyType`, `operationType`, `municipality`.
- Relevancia (MVP): destacadas primero → completitud del anuncio → recencia.
- Las URLs de búsqueda usan query string (`/buscar?operacion=renta&tipo=departamento&estado=cdmx`) y las landings SEO usan rutas limpias (Q-20) que precargan el mismo criterio.

## 7. Mapa
- Backend expone `GET /api/v1/properties/map?bbox=minLng,minLat,maxLng,maxLat&...filtros` → JSON ligero (id, slug, precio formateado, thumb, `public_lat`, `public_lng`), máx. 500 puntos; clustering en cliente (Leaflet.markercluster).
- Índice compuesto `(status, public_lat, public_lng)`; migración a índice espacial si se usa PostGIS/MySQL SPATIAL.

## 8. Seguridad (resumen)
Ver [authorization.md](authorization.md) y [authentication.md](authentication.md). Rate limiters nombrados: `login`, `register`, `leads`, `reports`, `api`. Descripciones de propiedades se guardan como texto plano (o HTML sanitizado con `mews/purifier` si se habilita editor enriquecido). Headers de seguridad (CSP, HSTS, X-Frame-Options) vía middleware.

## 9. Rendimiento
Paginación obligatoria, índices por filtro frecuente, cache de catálogos (`rememberForever` + invalidación en observer), cache de conteos del Home (10 min), imágenes WebP con `srcset` y `loading="lazy"`, colas para todo lo que no sea respuesta inmediata, Laravel Debugbar/Telescope solo en local.

## 10. Extensibilidad prevista (sin implementar)
- **Monetización:** columnas `is_featured`, `featured_until`, `expires_at` en properties; futuras tablas `plans`, `subscriptions`, `listing_boosts` ligadas a `users`/`agencies` (polimórfico `billable`).
- **Mensajería en tiempo real:** Laravel Reverb sobre las mismas tablas `conversations/messages`.
- **Multimedia extra:** colecciones adicionales de medialibrary (`videos`, `floor_plans`, `documents`) + campo `virtual_tour_url`.
- **Login social:** Socialite + tabla `social_accounts`.
- **Multi-país:** tabla `countries` y `states.country_code` ya preparado.
