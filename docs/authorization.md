# Autorización

> Estado: **APROBADO (2026-10-03)** — decisiones en [decisions.md](decisions.md).
Implementación: spatie/laravel-permission (roles + permisos) + **Policies** por modelo para reglas de propiedad (ownership). Los permisos dicen *qué acción puede hacer un rol*; la policy decide *sobre qué registro*.

## Roles
| Rol (`RoleName` enum) | Descripción |
|---|---|
| `user` | Usuario registrado (incluye particular que publica, Q-01) |
| `agent` | Agente inmobiliario |
| `agency_admin` | Administrador de una inmobiliaria (además puede ser agente) |
| `moderator` | Moderación y reportes |
| `super_admin` | Acceso total (Gate::before) |

## Permisos
`properties.create`, `properties.publish_without_review`, `properties.moderate`, `properties.feature`, `leads.view_own`, `leads.view_agency`, `agency.manage_agents`, `agency.manage_properties`, `reports.manage`, `catalogs.manage`, `users.manage`, `settings.manage`, `admin.access`, `agent_panel.access`.

## Matriz rol × permiso
| Permiso | user | agent | agency_admin | moderator | super_admin |
|---|:-:|:-:|:-:|:-:|:-:|
| properties.create | ✔ (límite, Q-01) | ✔ | ✔ | – | ✔ |
| properties.publish_without_review | – | otorgable | otorgable | – | ✔ |
| leads.view_own | ✔ | ✔ | ✔ | – | ✔ |
| leads.view_agency | – | – | ✔ | – | ✔ |
| agency.manage_agents / manage_properties | – | – | ✔ | – | ✔ |
| agent_panel.access | ✔* | ✔ | ✔ | – | ✔ |
| properties.moderate, reports.manage | – | – | – | ✔ | ✔ |
| properties.feature | – | – | – | – | ✔ |
| catalogs.manage, users.manage, settings.manage | – | – | – | – | ✔ |
| admin.access | – | – | – | ✔ | ✔ |

\* El particular usa el mismo panel con menú reducido ("Mis propiedades", "Mis contactos").

## Policies (reglas sobre registros)
**PropertyPolicy**
- `view`: público si `published|reserved|sold|rented`; en otro estado solo dueño, agente asignado, admins de su inmobiliaria o moderador.
- `create`: permiso `properties.create`, email verificado, no bloqueado, dentro del límite (BR-PROP-11).
- `update`: dueño (`user_id`), agente asignado, o `agency_admin` de `agency_id`. Nunca si `blocked`.
- `delete/archive`: dueño o `agency_admin`.
- `transition($to)`: delega en reglas de BR-STATUS.
- `moderate`: `properties.moderate`.

**LeadPolicy** — `view/update`: agente asignado, `agency_admin` de la inmobiliaria o super_admin. Visitante/otros: nunca.
**FavoritePolicy** — solo el propio usuario.
**AgencyPolicy** — `update/manageAgents`: `agency_user` con rol owner/admin.
**AgentProfilePolicy** — `update`: el propio agente.
**UserPolicy** — `update`: el propio usuario o super_admin; nadie modifica datos privados de otro.
**ReportPolicy** — `create`: cualquiera (rate-limited); `manage`: `reports.manage`.

## Accesos a paneles
- `/admin` (Filament Admin): `admin.access`. Un agente recibe **403**.
- `/panel` (Filament Agente): `agent_panel.access`; los recursos se filtran por `user_id`/`agent_id`/`agency_id` (scoping global en el panel).

## Tests obligatorios de autorización (ver testing.md)
Usuario no edita propiedad ajena · agente no entra a `/admin` · visitante no crea propiedades · usuario no modifica perfil de otro · agente no ve leads de otro agente · agente de inmobiliaria A no ve datos de B · usuario bloqueado no inicia sesión · API devuelve 403/404 equivalentes.
