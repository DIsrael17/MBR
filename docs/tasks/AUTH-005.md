# AUTH-005 — Roles y permisos

**Objetivo:** Controlar qué puede hacer cada tipo de usuario según la matriz de authorization.md.

**Descripción:** Instalar spatie/laravel-permission, crear enum `RoleName`, seeder `RolesAndPermissionsSeeder` idempotente, `AdminUserSeeder` desde env, `Gate::before` para `super_admin`, restricción de acceso a paneles Filament (`canAccessPanel`). Asignar rol `user` al registrarse.

**Dependencias:** AUTH-001, SETUP-006; **Q-01** (¿particulares publican?), **Q-12** (moderator).

**Archivos o módulos afectados:** `app/Enums/RoleName.php`, `app/Models/User.php` (`HasRoles`, `FilamentUser`), `database/seeders/*`, `app/Providers/AppServiceProvider.php`, `config/permission.php`.

**Base de datos:** migraciones del paquete (roles, permissions, pivotes).

**Backend:** seeders; helper `User::isAdmin()`; acceso a `/admin` con `admin.access`, a `/panel` con `agent_panel.access`.

**Frontend:** N/A (menús condicionados con `@can`).

**API:** N/A.

**Validaciones:** N/A.

**Reglas de negocio:** matriz de authorization.md.

**Permisos:** los definidos en authorization.md.

**Tests requeridos:** seeder idempotente; usuario nuevo tiene rol `user`; `agent` → 403 en `/admin`; `moderator` accede a `/admin`; visitante → redirect a login en `/panel`; `super_admin` pasa cualquier Gate.

**Criterios de aceptación:** roles y permisos sembrados; accesos a paneles correctos; tests en verde.

**Definition of Done:** código · tests · authorization.md actualizado con decisiones finales · PR.

**Prioridad:** P0
