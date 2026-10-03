# AUTH-001 — Registro, login y logout

**Objetivo:** Permitir a los visitantes crear una cuenta e iniciar/cerrar sesión de forma segura.

**Descripción:** Instalar el starter kit oficial de Livewire (Fortify) y adaptarlo al layout de SETUP-004 y al español. Agregar campos `phone` (opcional) y checkbox de aceptación de términos y aviso de privacidad (guardar `terms_accepted_at`).

**Dependencias:** SETUP-004; Q-17 (textos legales — se usan placeholders hasta recibirlos).

**Archivos o módulos afectados:** `app/Actions/Fortify/*`, `app/Models/User.php`, vistas de auth, `lang/es/*`, rutas.

**Base de datos:** migración que agrega a `users`: `phone` null, `whatsapp` null, `avatar_path` null, `is_blocked` bool default false, `blocked_reason` null, `last_login_at` null, `terms_accepted_at` null, `softDeletes`.

**Backend:** `CreateNewUser` valida y guarda consentimiento; listener de `Login` actualiza `last_login_at`.

**Frontend:** páginas `/registro`, `/login` (rutas en español con nombres estándar), responsive y accesibles.

**API:** N/A (AUTH-008).

**Validaciones:** `name` requerido ≤ 100; `email` requerido, email:rfc,dns, único; `password` `Password::defaults()` (mín. 8, mezcla de mayúsc./números en producción) confirmado; `phone` opcional regex 10 dígitos MX; `terms` accepted.

**Reglas de negocio:** BR-USR-01 (email único). Nuevo usuario recibe rol `user` (cuando exista AUTH-005).

**Permisos:** rutas de registro/login solo para invitados (`guest`); logout requiere `auth`.

**Tests requeridos:** registro exitoso; email duplicado rechazado; sin aceptar términos rechazado; login correcto/incorrecto; logout; usuario autenticado redirigido desde `/login`.

**Criterios de aceptación:** flujos funcionan en móvil y escritorio; mensajes en español; CSRF activo; tests en verde.

**Definition of Done:** código · tests · docs (authentication.md) · Pint/Larastan limpio · PR.

**Prioridad:** P0
